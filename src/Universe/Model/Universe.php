<?php

declare(strict_types=1);

namespace GC\Universe\Model;

/**
 * @Table(name="universe")
 * @Entity
 */
class Universe
{
    /**
     * @var int
     *
     * @Column(name="universe_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $universeId;

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
     * @var \DateTime|null
     *
     * @Column(name="ticks_starting_at", type="datetime", nullable=true)
     */
    private $ticksStartingAt;

    /**
     * @var int|null
     *
     * @Column(name="tick_interval", type="integer", nullable=true)
     */
    private $tickInterval;

    /**
     * @var int|null
     *
     * @Column(name="tick_current", type="integer", nullable=true)
     */
    private $tickCurrent;

    /**
     * @var int|null
     *
     * @Column(name="ticks_attack", type="integer", nullable=true)
     */
    private $ticksAttack;

    /**
     * @var int|null
     *
     * @Column(name="ticks_defense", type="integer", nullable=true)
     */
    private $ticksDefense;

    /**
     * @var int|null
     *
     * @Column(name="scan_blocker_metal_cost", type="integer", nullable=true)
     */
    private $scanBlockerMetalCost;

    /**
     * @var int|null
     *
     * @Column(name="scan_blocker_crystal_cost", type="integer", nullable=true)
     */
    private $scanBlockerCrystalCost;

    /**
     * @var int|null
     *
     * @Column(name="scan_relay_metal_cost", type="integer", nullable=true)
     */
    private $scanRelayMetalCost;

    /**
     * @var int|null
     *
     * @Column(name="scan_relay_crystal_cost", type="integer", nullable=true)
     */
    private $scanRelayCrystalCost;

    /**
     * @var int|null
     *
     * @Column(name="status", type="integer", nullable=true)
     */
    private $status;


}
