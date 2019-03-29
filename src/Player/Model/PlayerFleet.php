<?php

declare(strict_types=1);

namespace GC\Player\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Unit\Model\Unit;

/**
 * @Table(name="player_fleet")
 * @Entity
 */
class PlayerFleet
{
    public const MISSION_TYPE_ATTACK = 'attack';
    public const MISSION_TYPE_DEFEND = 'defend';
    public const MISSION_TYPE_RECALL = 'recall';

    /**
     * @var int
     *
     * @Column(name="player_fleet_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $playerFleetId;

    /**
     * @var bool
     *
     * @Column(name="is_orbit", type="boolean", nullable=false)
     */
    private $isOrbit;

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
     * @var bool
     *
     * @Column(name="is_stationary", type="boolean", nullable=false)
     */
    private $isStationary;

    /**
     * @var bool
     *
     * @Column(name="is_movable", type="boolean", nullable=false)
     */
    private $isMovable;

    /**
     * @var string
     *
     * @Column(name="mission_type", type="string", length=100, nullable=true)
     */
    private $missionType;

    /**
     * @var int|null
     *
     * @Column(name="ticks_left_until_mission_completed", type="integer", nullable=true)
     */
    private $ticksLeftUntilMissionCompleted;

    /**
     * @var int|null
     *
     * @Column(name="ticks_left_until_mission_reach", type="integer", nullable=true)
     */
    private $ticksLeftUntilMissionReach;

    /**
     * @var \GC\Player\Model\Player
     *
     * @ManyToOne(targetEntity="GC\Player\Model\Player", inversedBy="playerFleets")
     * @JoinColumn(name="player_id", referencedColumnName="player_id", nullable=false)
     */
    private $player;

    /**
     * @var \GC\Player\Model\Player|null
     *
     * @ManyToOne(targetEntity="Player")
     * @JoinColumn(name="target_player_id", referencedColumnName="player_id", nullable=true)
     */
    private $targetPlayer;

    /**
     * @var \GC\Player\Model\PlayerFleetUnit[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\PlayerFleetUnit", mappedBy="playerFleet", cascade={"all"}, orphanRemoval=true)
     */
    private $playerFleetUnits;

    /**
     * @param \GC\Player\Model\Player $player
     */
    public function __construct(Player $player)
    {
        $this->playerFleetUnits = new ArrayCollection();
        $this->player = $player;
        $this->isOffensive = false;
        $this->isDefensive = false;
        $this->isOrbit = false;
        $this->isStationary = false;
        $this->isMovable = false;
    }

    /**
     * @return int
     */
    public function getPlayerFleetId(): int
    {
        return $this->playerFleetId;
    }

    /**
     * @return bool
     */
    public function isOrbit(): bool
    {
        return $this->isOrbit;
    }

    /**
     * @param bool $isOrbit
     *
     * @return void
     */
    public function setIsOrbit(bool $isOrbit): void
    {
        $this->isOrbit = $isOrbit;
    }

    /**
     * @return bool
     */
    public function isStationary(): bool
    {
        return $this->isStationary;
    }

    /**
     * @param bool $isStationary
     */
    public function setIsStationary(bool $isStationary): void
    {
        $this->isStationary = $isStationary;
    }

    /**
     * @return bool
     */
    public function isOffensive(): bool
    {
        return $this->isOffensive;
    }

    /**
     * @param bool $isOffensive
     *
     * @return void
     */
    public function setIsOffensive(bool $isOffensive): void
    {
        $this->isOffensive = $isOffensive;
    }

    /**
     * @return bool
     */
    public function isDefensive(): bool
    {
        return $this->isDefensive;
    }

    /**
     * @param bool $isDefensive
     *
     * @return void
     */
    public function setIsDefensive(bool $isDefensive): void
    {
        $this->isDefensive = $isDefensive;
    }

    /**
     * @return bool
     */
    public function isMovable(): bool
    {
        return $this->isMovable;
    }

    /**
     * @param bool $isMovable
     */
    public function setIsMovable(bool $isMovable): void
    {
        $this->isMovable = $isMovable;
    }

    /**
     * @return string
     */
    public function getMissionType(): ?string
    {
        return $this->missionType;
    }

    /**
     * @param string|null $missionType
     *
     * @return void
     */
    public function setMissionType(?string $missionType): void
    {
        $this->missionType = $missionType;
    }

    /**
     * @return int|null
     */
    public function getTicksLeftUntilMissionCompleted(): ?int
    {
        return $this->ticksLeftUntilMissionCompleted;
    }

    /**
     * @param int|null $ticksLeftUntilMissionCompleted
     *
     * @return void
     */
    public function setTicksLeftUntilMissionCompleted(?int $ticksLeftUntilMissionCompleted): void
    {
        $this->ticksLeftUntilMissionCompleted = $ticksLeftUntilMissionCompleted;
    }

    /**
     * @return int|null
     */
    public function getTicksLeftUntilMissionReach(): ?int
    {
        return $this->ticksLeftUntilMissionReach;
    }

    /**
     * @param int|null $ticksLeftUntilMissionReach
     *
     * @return void
     */
    public function setTicksLeftUntilMissionReach(?int $ticksLeftUntilMissionReach): void
    {
        $this->ticksLeftUntilMissionReach = $ticksLeftUntilMissionReach;
    }

    /**
     * @return \GC\Player\Model\Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param \GC\Player\Model\Player $player
     *
     * @return void
     */
    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    /**
     * @return \GC\Player\Model\Player
     */
    public function getTargetPlayer(): ?Player
    {
        return $this->targetPlayer;
    }

    /**
     * @param \GC\Player\Model\Player|null $targetPlayer
     *
     * @return void
     */
    public function setTargetPlayer(?Player $targetPlayer): void
    {
        $this->targetPlayer = $targetPlayer;
    }

    /**
     * @return \GC\Player\Model\PlayerFleetUnit[]
     */
    public function getPlayerFleetUnits(): array
    {
        return $this->playerFleetUnits->getValues();
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     * @param int $quantity
     *
     * @return void
     */
    public function increaseUnitQuantity(Unit $unit, int $quantity): void
    {
        $playerFleetUnit = $this->getPlayerFleetUnitByUnitId($unit->getUnitId());
        if ($playerFleetUnit === null) {
            $playerFleetUnit = $this->createPlayerFleetUnit($unit);
        }

        $playerFleetUnit->increaseQuantity($quantity);
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     * @param int $quantity
     *
     * @return void
     */
    public function decreaseUnitQuantity(Unit $unit, int $quantity): void
    {
        $playerFleetUnit = $this->getPlayerFleetUnitByUnitId($unit->getUnitId());
        if ($playerFleetUnit === null) {
            return;
        }

        $playerFleetUnit->decreaseQuantity($quantity);
    }

    /**
     * @param int $unitId
     *
     * @return \GC\Player\Model\PlayerFleetUnit|null
     */
    public function getPlayerFleetUnitByUnitId(int $unitId): ?PlayerFleetUnit
    {
        foreach ($this->getPlayerFleetUnits() as $playerFleetUnit) {
            if ($playerFleetUnit->getUnit()->getUnitId() === $unitId) {
                return $playerFleetUnit;
            }
        }

        return null;
    }

    /**
     * @param \GC\Player\Model\PlayerFleet $playerFleet
     * @param int $unitId
     * @param int $quantity
     *
     * @return void
     */
    public function moveUnitTo(PlayerFleet $playerFleet, int $unitId, int $quantity): void
    {
        $playerFleetUnitFrom = $this->getPlayerFleetUnitByUnitId($unitId);

        if ($playerFleetUnitFrom === null) {
            return;
        }

        if ($quantity > $playerFleetUnitFrom->getQuantity()) {
            $quantity = $playerFleetUnitFrom->getQuantity();
        }

        if ($quantity <= 0) {
            return;
        }

        $playerFleetUnitFrom->decreaseQuantity($quantity);
        $playerFleet->increaseUnitQuantity($playerFleetUnitFrom->getUnit(), $quantity);

        if ($playerFleetUnitFrom->getQuantity() === 0) {
            $this->removePlayerFleetUnit($playerFleetUnitFrom);
        }
    }

    /**
     * @param \GC\Player\Model\PlayerFleetUnit $playerFleetUnit
     */
    protected function removePlayerFleetUnit(PlayerFleetUnit $playerFleetUnit): void
    {
        $this->playerFleetUnits->removeElement($playerFleetUnit);
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return int
     */
    public function getUnitQuantityOf(Unit $unit): int
    {
        $playerFleetUnit = $this->getPlayerFleetUnitByUnitId($unit->getUnitId());
        if ($playerFleetUnit === null) {
            return 0;
        }

        return $playerFleetUnit->getQuantity();
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return \GC\Player\Model\PlayerFleetUnit
     */
    protected function createPlayerFleetUnit(Unit $unit): PlayerFleetUnit
    {
        $playerFleetUnit = new PlayerFleetUnit($this, $unit);
        $this->playerFleetUnits->add($playerFleetUnit);

        return $playerFleetUnit;
    }

    /**
     * @return int
     */
    public function calculateUnitPoints(): int
    {
        $calculation = 0;

        foreach ($this->getPlayerFleetUnits() as $playerFleetUnit) {
            $calculation += $playerFleetUnit->getQuantity() * $playerFleetUnit->getUnit()->getMetalCost();
            $calculation += $playerFleetUnit->getQuantity() * $playerFleetUnit->getUnit()->getCrystalCost();
        }

        return (int) \round($calculation);
    }

    /**
     * @return bool
     */
    public function isBusy(): bool
    {
        return $this->missionType !== null;
    }

    /**
     * @return bool
     */
    public function isRecalling(): bool
    {
        return $this->missionType === static::MISSION_TYPE_RECALL;
    }

    /**
     * @return bool
     */
    public function isAttacking(): bool
    {
        return $this->missionType === static::MISSION_TYPE_ATTACK;
    }

    /**
     * @return bool
     */
    public function isDefending(): bool
    {
        return $this->missionType === static::MISSION_TYPE_DEFEND;
    }
}
