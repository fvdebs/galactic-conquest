<?php

declare(strict_types=1);

namespace GC\Tick\Model;

use Doctrine\ORM\EntityManager;

final class TickRepository
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function universeIncreaseTick(int $universeId): int
    {
        $sql = '
             UPDATE universe
             SET 
                 universe.tick_current = universe.tick_current + 1 ,
                 universe.last_tick_at = NOW()
             WHERE universe.universe_id = ?
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerUpdateResourceIncomePerTickFor(int $universeId): int
    {
        $sql = '
             UPDATE player
                INNER JOIN universe 
                    ON player.universe_id = ?
             SET 
                 player.metal_per_tick = 
                    FLOOR(
                        -- extractor metal income
                        (
                            universe.extractor_metal_income * player.extractor_metal
                        ) +
                        IFNULL(
                            -- technology metal production
                            (
                                SELECT SUM(technology.metal_production)
                                FROM technology
                                WHERE technology.technology_id IN (
                                    SELECT technology_id
                                    FROM player_technology
                                    WHERE player_technology.player_id = player.player_id 
                                        AND player_technology.ticks_left = 0
                                )
                            )
                        , 0)
                    )
                 ,
                 player.crystal_per_tick = 
                    FLOOR(
                        -- extractor crystal income
                        (
                            universe.extractor_crystal_income * player.extractor_crystal
                        ) +
                        IFNULL(
                            -- technology crystal production
                            (
                                SELECT SUM(technology.crystal_production)
                                FROM technology
                                WHERE technology.technology_id IN (
                                    SELECT technology_id
                                    FROM player_technology
                                    WHERE player_technology.player_id = player.player_id 
                                        AND player_technology.ticks_left = 0
                                )
                            )
                        , 0)
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerIncreaseResourceIncomeFor(int $universeId): int
    {
        $sql = '
             UPDATE player
                 INNER JOIN universe 
                    ON player.universe_id = ?
                 INNER JOIN galaxy 
                    ON player.galaxy_id = galaxy.galaxy_id
             SET 
                 player.metal = 
                    FLOOR(
                        player.metal + 
                        (
                            -- subtract galaxy tax from metal income
                            player.metal_per_tick -
                            ((player.metal_per_tick / 100) * galaxy.tax_metal)
                        )
                    ),
                player.crystal = 
                    FLOOR(
                        player.crystal + 
                        (
                            -- subtract galaxy tax from crystal income
                            player.crystal_per_tick -
                            ((player.crystal_per_tick / 100) * galaxy.tax_crystal)
                        )
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerDecreaseTechnologyConstructionTicksFor(int $universeId): int
    {
        $sql = '
            UPDATE player_technology
                INNER JOIN player 
                    ON player_technology.player_id = player.player_id
                    AND player.universe_id = ?
            SET 
                player_technology.ticks_left = player_technology.ticks_left - 1
            WHERE 
                  player_technology.ticks_left > 0
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerDecreaseUnitConstructionTicksFor(int $universeId): int
    {
        $sql = '
            UPDATE player_unit_construction
                INNER JOIN player 
                    ON player_unit_construction.player_id = player.player_id
                    AND player.universe_id = ?
            SET 
                player_unit_construction.ticks_left = player_unit_construction.ticks_left - 1
            WHERE 
                  player_unit_construction.ticks_left > 0
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * only works with unique constraints.
     *
     * @param int $universeId
     *
     * @return int
     */
    public function playerFinishUnitConstructions(int $universeId): int
    {
        $sql = '
            INSERT INTO player_fleet_unit
            SELECT 
                   null,
                   (
                       SELECT 
                              player_fleet.player_fleet_id
                       FROM 
                            player_fleet
                       WHERE 
                             player_fleet.is_orbit = 1
                             AND player_fleet.player_id = player_unit_construction.player_id
                   ) AS playerFleetId,
                   player_unit_construction.unit_id,
                   player_unit_construction.quantity
            FROM player_unit_construction
                INNER JOIN player
                    ON player.player_id = player_unit_construction.player_id
                    AND player.universe_id = ?
            WHERE player_unit_construction.ticks_left = 0
            ON DUPLICATE KEY UPDATE 
                player_fleet_unit.quantity = player_fleet_unit.quantity + player_unit_construction.quantity, 
                player_fleet_unit.unit_id = player_unit_construction.unit_id
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerDeleteFinishedUnitConstructions(int $universeId): int
    {
        $sql = '
            DELETE player_unit_construction
            FROM player_unit_construction 
            INNER JOIN player
                    ON player.player_id = player_unit_construction.player_id
                    AND player.universe_id = :universeId
            WHERE player_unit_construction.ticks_left = 0
        ';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute([':universeId' => $universeId]);

        return $stmt->rowCount();
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerFleetDecreaseTicksLeftUntilMissionCompletedFor(int $universeId): int
    {
        $sql = '
            UPDATE player_fleet
                INNER JOIN player 
                    ON player_fleet.player_id = player.player_id
                    AND player.universe_id = ?
            SET 
                player_fleet.ticks_left_until_mission_completed = player_fleet.ticks_left_until_mission_completed - 1
            WHERE 
                player_fleet.is_movable = 1
                AND player_fleet.mission_type IS NOT NULL 
                AND player_fleet.ticks_left_until_mission_completed IS NOT NULL 
                AND player_fleet.ticks_left_until_mission_completed > 0
                AND player_fleet.ticks_left_until_mission_reach = 0
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerFleetDecreaseTicksLeftUntilMissionReachFor(int $universeId): int
    {
        $sql = '
            UPDATE player_fleet
                INNER JOIN player 
                    ON player_fleet.player_id = player.player_id
                    AND player.universe_id = ?
            SET 
                player_fleet.ticks_left_until_mission_reach = player_fleet.ticks_left_until_mission_reach - 1
            WHERE 
                player_fleet.is_movable = 1
                AND player_fleet.mission_type IS NOT NULL 
                AND player_fleet.ticks_left_until_mission_reach IS NOT NULL 
                AND player_fleet.ticks_left_until_mission_reach > 0
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerFleetClearMissionFor(int $universeId): int
    {
        $sql = '
            UPDATE player_fleet
                INNER JOIN player 
                    ON player_fleet.player_id = player.player_id
                    AND player.universe_id = ?
            SET 
                player_fleet.mission_type = NULL,
                player_fleet.mission_type_original = NULL,
                player_fleet.ticks_left_until_mission_reach = 0,
                player_fleet.ticks_left_until_mission_completed = 0,
                player_fleet.target_player_id = NULL
            WHERE 
                player_fleet.is_movable = 1
                AND player_fleet.ticks_left_until_mission_reach = 0
                AND player_fleet.ticks_left_until_mission_completed = 0
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerCalculatePointsFor(int $universeId): int
    {
        $sql = '
            UPDATE player
                INNER JOIN universe 
                    ON player.universe_id = ?
            SET 
                player.points = 
                    FLOOR(
                        -- resource points
                        (
                            (player.metal + player.crystal) / universe.resource_points_divider
                        ) +
                        -- extractor points
                        (
                            (player.extractor_metal + player.extractor_metal) / universe.extractor_points
                        ) + 
                        -- technology points
                        (
                            IFNULL(
                                (SELECT (SUM(technology.metal_cost) + SUM(technology.crystal_cost)) AS score
                                    FROM technology
                                        INNER JOIN player_technology 
                                            ON player_technology.technology_id = technology.technology_id
                                                AND player_technology.player_id = 23
                                                AND player_technology.ticks_left = 0
                                    WHERE player_technology.player_id = player.player_id)
                                , 0)
                        ) + 
                        -- unit points
                        (
                            IFNULL(
                                (
                                    SELECT 
                                        SUM(
                                            (player_fleet_unit.quantity * 
                                                (
                                                    SELECT SUM(unit.metal_cost) + SUM(unit.crystal_cost) as total 
                                                    FROM unit WHERE unit.unit_id = player_fleet_unit.unit_id
                                                )
                                            )
                                        ) as total
                                    FROM `player_fleet_unit`
                                        INNER JOIN player_fleet
                                            ON player_fleet.player_fleet_id = player_fleet_unit.player_fleet_id
                                    WHERE player_fleet.player_id = player.player_id
                                )
                             , 0)
                        )
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function playerUpdateRankingPositionFor(int $universeId): int
    {
        $sql = '
            UPDATE 
                player 
            INNER JOIN 
                (
                    SELECT 
                        player.player_id,
                        @rowNumber := @rowNumber + 1 AS orderedRankingPosition
                    FROM player, (SELECT @rowNumber := 0) var
                    WHERE player.universe_id = ?
                    ORDER BY player.points DESC
                ) AS orderJoin
            ON player.player_id = orderJoin.player_id
            SET 
                player.ranking_position = orderJoin.orderedRankingPosition;
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function galaxyDecreaseTechnologyConstructionTicksFor(int $universeId): int
    {
        $sql = '
            UPDATE galaxy_technology
                INNER JOIN galaxy 
                    ON galaxy_technology.galaxy_id = galaxy.galaxy_id
                    AND galaxy.universe_id = ?
            SET 
                galaxy_technology.ticks_left = galaxy_technology.ticks_left - 1
            WHERE 
                  galaxy_technology.ticks_left > 0
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function galaxyIncreaseExtractorPointsFor(int $universeId): int
    {
        $sql = '
            UPDATE galaxy
                INNER JOIN universe 
                    ON galaxy.universe_id = ?
            SET 
                galaxy.extractor_points = 
                    galaxy.extractor_points +
                    FLOOR(
                        IFNULL(
                            (
                                SELECT SUM(player.extractor_metal) + SUM(player.extractor_crystal) AS totalExtractors
                                FROM player
                                WHERE player.galaxy_id = galaxy.galaxy_id
                            )
                        ,0) /
                        (
                            IFNULL(
                                (
                                    SELECT count(player.player_id)
                                    FROM player
                                    WHERE player.galaxy_id = galaxy.galaxy_id
                                )
                            , 0)
                        )
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function galaxyUpdateRankingPositionFor(int $universeId): int
    {
        $sql = '
            UPDATE 
                galaxy 
            INNER JOIN 
                (
                    SELECT 
                        galaxy.galaxy_id,
                        @rowNumber := @rowNumber + 1 AS orderedRankingPosition
                    FROM galaxy, (SELECT @rowNumber := 0) var
                    WHERE galaxy.universe_id = ?
                    ORDER BY galaxy.extractor_points DESC
                ) AS orderJoin
            ON galaxy.galaxy_id = orderJoin.galaxy_id
            SET 
                galaxy.ranking_position = orderJoin.orderedRankingPosition;
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function galaxyUpdateResourceIncomePerTickFor(int $universeId): int
    {
        $sql = '
             UPDATE galaxy
                 INNER JOIN universe 
                    ON galaxy.universe_id = ?
             SET 
                 galaxy.metal_per_tick = 
                    FLOOR(
                        (
                            -- metal tax
                            IFNULL(
                                (
                                    SELECT 
                                           FLOOR(
                                               (SUM(player.metal_per_tick) / 100) * galaxy.tax_metal
                                           )
                                    FROM player
                                    WHERE player.galaxy_id = galaxy.galaxy_id
                                )
                            , 0)
                        )
                    ),
                galaxy.crystal_per_tick = 
                    FLOOR(
                        (
                            -- metal tax
                            IFNULL(
                                (
                                    SELECT 
                                       FLOOR(
                                           (SUM(player.crystal_per_tick) / 100) * galaxy.tax_crystal
                                       )
                                    FROM player
                                    WHERE player.galaxy_id = galaxy.galaxy_id
                                )
                            , 0)
                        )
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function galaxyIncreaseResourceIncomeFor(int $universeId): int
    {
        $sql = '
             UPDATE galaxy
                 INNER JOIN universe 
                    ON galaxy.universe_id = ?
                 INNER JOIN alliance
                    ON galaxy.alliance_id = alliance.alliance_id
             SET 
                 galaxy.metal = 
                    FLOOR(
                        galaxy.metal + 
                        (
                            -- subtract alliance tax from galaxy metal income
                            galaxy.metal_per_tick -
                            ((galaxy.metal_per_tick / 100) * alliance.tax_metal)
                        )
                    ),
                galaxy.crystal = 
                    FLOOR(
                        galaxy.crystal + 
                        (
                            -- subtract alliance tax from galaxy crystal income
                            galaxy.crystal_per_tick -
                            ((galaxy.crystal_per_tick / 100) * alliance.tax_crystal)
                        )
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function galaxyCalculateAveragePlayerPointsFor(int $universeId): int
    {
        $sql = '
            UPDATE galaxy
                INNER JOIN universe 
                    ON galaxy.universe_id = ?
            SET 
                galaxy.average_points = 
                    FLOOR(
                        IFNULL(
                            (
                                SELECT SUM(player.points) AS totalPoints
                                FROM player
                                WHERE player.galaxy_id = galaxy.galaxy_id
                            ) /
                            (
                                SELECT count(player.player_id)
                                FROM player
                                WHERE player.galaxy_id = galaxy.galaxy_id
                            )
                        , 0)
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function allianceDecreaseTechnologyConstructionTicksFor(int $universeId): int
    {
        $sql = '
            UPDATE alliance_technology
                INNER JOIN alliance 
                    ON alliance_technology.alliance_id = alliance.alliance_id
                    AND alliance.universe_id = ?
            SET 
                alliance_technology.ticks_left = alliance_technology.ticks_left - 1
            WHERE 
                  alliance_technology.ticks_left > 0
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function allianceIncreaseExtractorPointsFor(int $universeId): int
    {
        $sql = '
            UPDATE alliance
                INNER JOIN universe 
                    ON alliance.universe_id = ?
            SET 
                alliance.extractor_points = 
                    alliance.extractor_points +
                    FLOOR(
                        IFNULL(
                            (
                                SELECT SUM(galaxy.extractor_points) AS totalPoints
                                FROM galaxy
                                WHERE galaxy.alliance_id = alliance.alliance_id
                            ) /
                            (
                                SELECT count(galaxy.galaxy_id)
                                FROM galaxy
                                WHERE galaxy.alliance_id = alliance.alliance_id
                            )
                        , 0)
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function allianceUpdateRankingPositionFor(int $universeId): int
    {
        $sql = '
            UPDATE 
                alliance 
            INNER JOIN 
                (
                    SELECT 
                        alliance.alliance_id,
                        @rowNumber := @rowNumber + 1 AS orderedRankingPosition
                    FROM alliance, (SELECT @rowNumber := 0) var
                    WHERE alliance.universe_id = ?
                    ORDER BY alliance.extractor_points DESC
                ) AS orderJoin
            ON alliance.alliance_id = orderJoin.alliance_id
            SET 
                alliance.ranking_position = orderJoin.orderedRankingPosition;
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function allianceIncreaseResourceIncomeFor(int $universeId): int
    {
        $sql = '
             UPDATE alliance
                 INNER JOIN universe 
                    ON alliance.universe_id = ?
             SET 
                 alliance.metal = 
                    FLOOR(
                        alliance.metal + 
                        (
                            -- metal tax
                            IFNULL(
                                (
                                    SELECT 
                                           FLOOR(
                                               (SUM(galaxy.metal_per_tick) / 100) * alliance.tax_metal
                                           )
                                    FROM galaxy
                                    WHERE galaxy.alliance_id = alliance.alliance_id
                                )
                            , 0)
                        )
                    ),
                alliance.crystal = 
                    FLOOR(
                        alliance.crystal + 
                        (
                            -- metal tax
                            IFNULL(
                                (
                                    SELECT 
                                       FLOOR(
                                           (SUM(galaxy.crystal_per_tick) / 100) * alliance.tax_crystal
                                       )
                                    FROM galaxy
                                    WHERE galaxy.alliance_id = alliance.alliance_id
                                )
                            , 0)
                        )
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }

    /**
     * @param int $universeId
     *
     * @return int
     */
    public function allianceCalculateAveragePlayerPointsFor(int $universeId): int
    {
        $sql = '
            UPDATE alliance
                INNER JOIN universe 
                    ON alliance.universe_id = ?
            SET 
                alliance.average_points = 
                    FLOOR(
                        IFNULL(
                            (
                                SELECT SUM(galaxy.average_points) AS totalPoints
                                FROM galaxy
                                WHERE galaxy.alliance_id = alliance.alliance_id
                            ) /
                            (
                                SELECT count(galaxy.galaxy_id)
                                FROM galaxy
                                WHERE galaxy.alliance_id = alliance.alliance_id
                            )
                        , 0)
                    )
        ';

        return $this->getEntityManager()
            ->getConnection()
            ->executeUpdate($sql, [$universeId]);
    }
}
