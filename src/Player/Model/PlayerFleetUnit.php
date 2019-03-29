<?php

declare(strict_types=1);

namespace GC\Player\Model;

use GC\Unit\Model\Unit;

/**
 * @Table(name="player_fleet_unit")
 * @Entity
 */
class PlayerFleetUnit
{
    /**
     * @var int
     *
     * @Column(name="player_fleet_unit_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $playerFleetUnitId;

    /**
     * @var int
     *
     * @Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var \GC\Player\Model\PlayerFleet
     *
     * @ManyToOne(targetEntity="GC\Player\Model\PlayerFleet", inversedBy="playerFleetUnits")
     * @JoinColumn(name="player_fleet_id", referencedColumnName="player_fleet_id", nullable=false)
     */
    private $playerFleet;

    /**
     * @var \GC\Unit\Model\Unit
     *
     * @ManyToOne(targetEntity="\GC\Unit\Model\Unit")
     * @JoinColumn(name="unit_id", referencedColumnName="unit_id", nullable=false)
     */
    private $unit;

    /**
     * @param \GC\Player\Model\PlayerFleet $playerFleet
     * @param \GC\Unit\Model\Unit $unit
     */
    public function __construct(PlayerFleet $playerFleet, Unit $unit)
    {
        $this->playerFleet = $playerFleet;
        $this->unit = $unit;
        $this->quantity = 0;
    }

    /**
     * @return int
     */
    public function getPlayerFleetUnitId(): int
    {
        return $this->playerFleetUnitId;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet
     */
    public function getPlayerFleet(): PlayerFleet
    {
        return $this->playerFleet;
    }

    /**
     * @return \GC\Unit\Model\Unit
     */
    public function getUnit(): Unit
    {
        return $this->unit;
    }

    /**
     * @param int $quantity
     *
     * @return void
     */
    public function increaseQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    /**
     * @param int $quantity
     *
     * @return void
     */
    public function decreaseQuantity(int $quantity): void
    {
        $this->quantity -= $quantity;

        if ($this->quantity < 0) {
            $this->quantity = 0;
        }
    }
}
