<?php

declare(strict_types=1);

namespace GC\Player\Model;

/**
 * @Table(name="player_fleet", indexes={@Index(name="fk-player_fleet-player_id", columns={"player_id"}), @Index(name="fk-player_fleet-target_player_id", columns={"target_player_id"})})
 * @Entity
 */
class PlayerFleet
{
    /**
     * @var int
     *
     * @Column(name="player_fleet_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $playerFleetId;

    /**
     * @var bool
     *
     * @Column(name="is_home_fleet", type="boolean", nullable=false)
     */
    private $isHomeFleet;

    /**
     * @var bool
     *
     * @Column(name="is_offensive", type="boolean", nullable=false)
     */
    private $isOffensive;

    /**
     * @var bool
     *
     * @Column(name="is_defensive", type="boolean", nullable=false)
     */
    private $isDefensive;

    /**
     * @var string|null
     *
     * @Column(name="mission_type", type="string", length=100, nullable=true)
     */
    private $missionType;

    /**
     * @var int|null
     *
     * @Column(name="ticks_left_until_turn_back", type="integer", nullable=true)
     */
    private $ticksLeftUntilTurnBack;

    /**
     * @var \Player
     *
     * @ManyToOne(targetEntity="Player")
     * @JoinColumns({
     *   @JoinColumn(name="player_id", referencedColumnName="player_id")
     * })
     */
    private $player;

    /**
     * @var \Player
     *
     * @ManyToOne(targetEntity="Player")
     * @JoinColumns({
     *   @JoinColumn(name="target_player_id", referencedColumnName="player_id")
     * })
     */
    private $targetPlayer;


}
