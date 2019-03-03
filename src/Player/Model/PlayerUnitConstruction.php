<?php

declare(strict_types=1);

namespace GC\Player\Model;

use GC\Unit\Model\Unit;

/**
 * @Table(name="player_unit_construction")
 * @Entity
 */
class PlayerUnitConstruction
{
    /**
     * @var int
     *
     * @Column(name="player_unit_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $playerUnitId;

    /**
     * @var \GC\Player\Model\Player
     *
     * @ManyToOne(targetEntity="GC\Player\Model\Player", inversedBy="playerUnitConstructions")
     * @JoinColumn(name="player_id", referencedColumnName="player_id", nullable=false)
     */
    private $player;

    /**
     * @var \GC\Unit\Model\Unit
     *
     * @ManyToOne(targetEntity="\GC\Unit\Model\Unit")
     * @JoinColumn(name="unit_id", referencedColumnName="unit_id", nullable=false)
     */
    private $unit;

    /**
     * @var int
     *
     * @Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var int
     *
     * @Column(name="ticks_left", type="integer", nullable=false)
     */
    private $ticksLeft;

    /**
     * @param \GC\Player\Model\Player $player
     * @param \GC\Unit\Model\Unit $unit
     * @param int $quantity
     */
    public function __construct(Player $player, Unit $unit, int $quantity)
    {
        $this->player = $player;
        $this->unit = $unit;
        $this->quantity = $quantity;
        $this->ticksLeft = $unit->getTicksToBuild();
    }

    /**
     * @return int
     */
    public function getPlayerUnitId(): int
    {
        return $this->playerUnitId;
    }

    /**
     * @return \GC\Player\Model\Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return \GC\Unit\Model\Unit
     */
    public function getUnit():Unit
    {
        return $this->unit;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getTicksLeft(): int
    {
        return $this->ticksLeft;
    }

    /**
     * @return void
     */
    public function decreaseTicksLeft(): void
    {
        $this->ticksLeft = $this->ticksLeft - 1;
    }
}