<?php

declare(strict_types=1);

namespace GC\Unit\Model;

/**
 * @Table(name="unit", indexes={@Index(name="fk-unit-faction_id", columns={"faction_id"})})
 * @Entity
 */
class Unit
{
    /**
     * @var int
     *
     * @Column(name="unit_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $unitId;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var bool|null
     *
     * @Column(name="is_stationary", type="boolean", nullable=true)
     */
    private $isStationary;

    /**
     * @var int|null
     *
     * @Column(name="crystal_cost", type="integer", nullable=true)
     */
    private $crystalCost;

    /**
     * @var int|null
     *
     * @Column(name="metal_cost", type="integer", nullable=true)
     */
    private $metalCost;

    /**
     * @var int|null
     *
     * @Column(name="ticks_to_build", type="integer", nullable=true)
     */
    private $ticksToBuild;

    /**
     * @var int|null
     *
     * @Column(name="carrier_space", type="integer", nullable=true)
     */
    private $carrierSpace;

    /**
     * @var int|null
     *
     * @Column(name="carrier_space_consumption", type="integer", nullable=true)
     */
    private $carrierSpaceConsumption;

    /**
     * @var int|null
     *
     * @Column(name="extractor_steal_amount", type="integer", nullable=true)
     */
    private $extractorStealAmount;

    /**
     * @var int|null
     *
     * @Column(name="extractor_guard_amount", type="integer", nullable=true)
     */
    private $extractorGuardAmount;

    /**
     * @var \Faction
     *
     * @ManyToOne(targetEntity="Faction")
     * @JoinColumns({
     *   @JoinColumn(name="faction_id", referencedColumnName="faction_id")
     * })
     */
    private $faction;


}
