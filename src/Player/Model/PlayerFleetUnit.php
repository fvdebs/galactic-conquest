<?php

declare(strict_types=1);

namespace GC\Player\Model;

/**
 * @Table(name="player_fleet_unit", indexes={@Index(name="fk-player_fleet_unit-unit_id", columns={"unit_id"}), @Index(name="fk-player_fleet_unit-player_fleet_id", columns={"player_fleet_id"})})
 * @Entity
 */
class PlayerFleetUnit
{
    /**
     * @var int
     *
     * @Column(name="player_fleet_unit_id", type="bigint", nullable=false)
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
     * @var \PlayerFleet
     *
     * @ManyToOne(targetEntity="PlayerFleet")
     * @JoinColumns({
     *   @JoinColumn(name="player_fleet_id", referencedColumnName="player_fleet_id")
     * })
     */
    private $playerFleet;

    /**
     * @var \Unit
     *
     * @ManyToOne(targetEntity="Unit")
     * @JoinColumns({
     *   @JoinColumn(name="unit_id", referencedColumnName="unit_id")
     * })
     */
    private $unit;


}
