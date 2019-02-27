<?php

declare(strict_types=1);

namespace GC\Universe\Model;

use DateTime;

/**
 * @Table(name="universe")
 * @Entity
 */
class Universe
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    /**
     * @var int
     *
     * @Column(name="universe_id", type="integer", nullable=false)
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
     * @var string
     *
     * @Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var DateTime
     *
     * @Column(name="ticks_starting_at", type="datetime", nullable=false)
     */
    private $ticksStartingAt;

    /**
     * @var int
     *
     * @Column(name="tick_interval", type="integer", nullable=false)
     */
    private $tickInterval;

    /**
     * @var int
     *
     * @Column(name="tick_current", type="integer", nullable=false)
     */
    private $tickCurrent;

    /**
     * @var int
     *
     * @Column(name="ticks_attack", type="integer", nullable=false)
     */
    private $ticksAttack;

    /**
     * @var int
     *
     * @Column(name="ticks_defense", type="integer", nullable=false)
     */
    private $ticksDefense;

    /**
     * @var int
     *
     * @Column(name="scan_blocker_metal_cost", type="integer", nullable=false)
     */
    private $scanBlockerMetalCost;

    /**
     * @var int
     *
     * @Column(name="scan_blocker_crystal_cost", type="integer", nullable=false)
     */
    private $scanBlockerCrystalCost;

    /**
     * @var int
     *
     * @Column(name="scan_relay_metal_cost", type="integer", nullable=false)
     */
    private $scanRelayMetalCost;

    /**
     * @var int
     *
     * @Column(name="scan_relay_crystal_cost", type="integer", nullable=false)
     */
    private $scanRelayCrystalCost;

    /**
     * @var int
     *
     * @Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @param string $name
     * @param DateTime|null $ticksStartingAt
     * @param int|null $tickInterval
     *
     * @throws \Exception
     */
    public function __construct(string $name, ?DateTime $ticksStartingAt = null, ?int $tickInterval = 15)
    {
        $this->name = $name;
        $this->description = '';
        $this->ticksStartingAt = $ticksStartingAt ?? (new DateTime())->modify('+ 1day');
        $this->tickInterval = $tickInterval;
        $this->tickCurrent = 0;
        $this->ticksAttack = 30;
        $this->ticksDefense = 20;
        $this->scanBlockerMetalCost = 5000;
        $this->scanBlockerCrystalCost = 2000;
        $this->scanRelayMetalCost = 2000;
        $this->scanRelayCrystalCost = 5000;
        $this->status = static::STATUS_INACTIVE;
    }

    /**
     * @return int
     */
    public function getUniverseId(): int
    {
        return (int) $this->universeId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return DateTime
     */
    public function getTicksStartingAt(): DateTime
    {
        return $this->ticksStartingAt;
    }

    /**
     * @param DateTime $ticksStartingAt
     *
     * @return void
     */
    public function setTicksStartingAt(DateTime $ticksStartingAt): void
    {
        $this->ticksStartingAt = $ticksStartingAt;
    }

    /**
     * @return int
     */
    public function getTickInterval(): int
    {
        return $this->tickInterval;
    }

    /**
     * @param int $tickInterval
     *
     * @return void
     */
    public function setTickInterval(int $tickInterval): void
    {
        $this->tickInterval = $tickInterval;
    }

    /**
     * @return int
     */
    public function getTickCurrent(): int
    {
        return $this->tickCurrent;
    }

    /**
     * @param int $tickCurrent
     *
     * @return void
     */
    public function setTickCurrent(int $tickCurrent): void
    {
        $this->tickCurrent = $tickCurrent;
    }

    /**
     * @return int
     */
    public function getTicksAttack(): int
    {
        return $this->ticksAttack;
    }

    /**
     * @param int $ticksAttack
     *
     * @return void
     */
    public function setTicksAttack(int $ticksAttack): void
    {
        $this->ticksAttack = $ticksAttack;
    }

    /**
     * @return int
     */
    public function getTicksDefense(): int
    {
        return $this->ticksDefense;
    }

    /**
     * @param int $ticksDefense
     *
     * @return void
     */
    public function setTicksDefense(int $ticksDefense): void
    {
        $this->ticksDefense = $ticksDefense;
    }

    /**
     * @return int
     */
    public function getScanBlockerMetalCost(): int
    {
        return $this->scanBlockerMetalCost;
    }

    /**
     * @param int $scanBlockerMetalCost
     *
     * @return void
     */
    public function setScanBlockerMetalCost(int $scanBlockerMetalCost): void
    {
        $this->scanBlockerMetalCost = $scanBlockerMetalCost;
    }

    /**
     * @return int
     */
    public function getScanBlockerCrystalCost(): int
    {
        return $this->scanBlockerCrystalCost;
    }

    /**
     * @param int $scanBlockerCrystalCost
     *
     * @return void
     */
    public function setScanBlockerCrystalCost(int $scanBlockerCrystalCost): void
    {
        $this->scanBlockerCrystalCost = $scanBlockerCrystalCost;
    }

    /**
     * @return int
     */
    public function getScanRelayMetalCost(): int
    {
        return $this->scanRelayMetalCost;
    }

    /**
     * @param int $scanRelayMetalCost
     *
     * @return void
     */
    public function setScanRelayMetalCost(int $scanRelayMetalCost): void
    {
        $this->scanRelayMetalCost = $scanRelayMetalCost;
    }

    /**
     * @return int
     */
    public function getScanRelayCrystalCost(): int
    {
        return $this->scanRelayCrystalCost;
    }

    /**
     * @param int $scanRelayCrystalCost
     *
     * @return void
     */
    public function setScanRelayCrystalCost(int $scanRelayCrystalCost): void
    {
        $this->scanRelayCrystalCost = $scanRelayCrystalCost;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return void
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }
}