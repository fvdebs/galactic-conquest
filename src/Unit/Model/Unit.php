<?php

declare(strict_types=1);

namespace GC\Unit\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Faction\Model\Faction;
use GC\Technology\Model\Technology;
use GC\Universe\Model\Universe;

/**
 * @Table(name="unit")
 * @Entity(repositoryClass="GC\Unit\Model\UnitRepository")
 */
class Unit
{
    public const GROUPING_SCAN = 'SCAN';
    public const GROUPING_DEFENSE = 'DEFENSE';
    public const GROUPING_OFFENSIVE = 'OFFENSIVE';

    /**
     * @var int
     *
     * @Column(name="unit_id", type="integer", nullable=false)
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
     * @var string
     *
     * @Column(name="grouping", type="string", length=150, nullable=false)
     */
    private $grouping;

    /**
     * @var string
     *
     * @Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var bool
     *
     * @Column(name="is_stationary", type="boolean", nullable=false)
     */
    private $isStationary;

    /**
     * @var int
     *
     * @Column(name="crystal_cost", type="integer", nullable=false)
     */
    private $crystalCost;

    /**
     * @var int
     *
     * @Column(name="metal_cost", type="integer", nullable=false)
     */
    private $metalCost;

    /**
     * @var int
     *
     * @Column(name="ticks_to_build", type="integer", nullable=false)
     */
    private $ticksToBuild;

    /**
     * @var int
     *
     * @Column(name="carrier_space", type="integer", nullable=false)
     */
    private $carrierSpace;

    /**
     * @var int
     *
     * @Column(name="carrier_space_consumption", type="integer", nullable=false)
     */
    private $carrierSpaceConsumption;

    /**
     * @var int
     *
     * @Column(name="extractor_steal_amount", type="integer", nullable=false)
     */
    private $extractorStealAmount;
    /**
     * @var \GC\Faction\Model\Faction
     *
     * @ManyToOne(targetEntity="\GC\Faction\Model\Faction")
     * @JoinColumn(name="faction_id", referencedColumnName="faction_id", nullable=false)
     */
    private $faction;

    /**
     * @var int
     *
     * @Column(name="extractor_guard_amount", type="integer", nullable=false)
     */
    private $extractorGuardAmount;

    /**
     * @var int
     *
     * @Column(name="scan_relais_factor", type="integer", nullable=false)
     */
    private $scanRelaisFactor;

    /**
     * @var int
     *
     * @Column(name="scan_blocker_factor", type="integer", nullable=false)
     */
    private $scanBlockerFactor;

    /**
     * @var \GC\Unit\Model\UnitCombatSetting[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Unit\Model\UnitCombatSetting", mappedBy="sourceUnit", cascade={"all"}, orphanRemoval=true)
     */
    private $unitCombatSettings;

    /**
     * @var \GC\Universe\Model\Universe
     *
     * @ManyToOne(targetEntity="\GC\Universe\Model\Universe", inversedBy="units")
     * @JoinColumn(name="universe_id", referencedColumnName="universe_id", nullable=false)
     */
    private $universe;

    /**
     * @var \GC\Technology\Model\Technology|null
     *
     * @OneToOne(targetEntity="\GC\Technology\Model\Technology")
     * @JoinColumn(name="technology_id", referencedColumnName="technology_id", nullable=true)
     */
    private $requiredTechnology;

    /**
     * @param string $name
     * @param \GC\Faction\Model\Faction $faction
     * @param string $grouping
     */
    public function __construct(string $name, Faction $faction, string $grouping)
    {
        $this->unitCombatSettings = new ArrayCollection();
        $this->universe = $faction->getUniverse();
        $this->name = $name;
        $this->description = '';
        $this->isStationary = false;
        $this->crystalCost = 5000;
        $this->metalCost = 5000;
        $this->ticksToBuild = 10;
        $this->carrierSpace = 0;
        $this->carrierSpaceConsumption = 0;
        $this->extractorGuardAmount = 0;
        $this->extractorStealAmount = 0;
        $this->grouping = $grouping;
        $this->scanRelaisFactor = 0;
        $this->scanBlockerFactor = 0;
        $this->faction = $faction;
    }

    /**
     * @return int
     */
    public function getUnitId(): int
    {
        return $this->unitId;
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
    public function getGrouping(): string
    {
        return $this->grouping;
    }

    /**
     * @param string $grouping
     *
     * @return void
     */
    public function setGrouping(string $grouping): void
    {
        $this->grouping = $grouping;
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
     * @return bool
     */
    public function isStationary(): bool
    {
        return $this->isStationary;
    }

    /**
     * @param bool $isStationary
     *
     * @return void
     */
    public function setIsStationary(bool $isStationary): void
    {
        $this->isStationary = $isStationary;
    }

    /**
     * @return int
     */
    public function getCrystalCost(): int
    {
        return $this->crystalCost;
    }

    /**
     * @param int $crystalCost
     *
     * @return void
     */
    public function setCrystalCost(int $crystalCost): void
    {
        $this->crystalCost = $crystalCost;
    }

    /**
     * @return int
     */
    public function getMetalCost(): int
    {
        return $this->metalCost;
    }

    /**
     * @param int $metalCost
     *
     * @return void
     */
    public function setMetalCost(int $metalCost): void
    {
        $this->metalCost = $metalCost;
    }

    /**
     * @return int
     */
    public function getTicksToBuild(): int
    {
        return $this->ticksToBuild;
    }

    /**
     * @param int $ticksToBuild
     *
     * @return void
     */
    public function setTicksToBuild(int $ticksToBuild): void
    {
        $this->ticksToBuild = $ticksToBuild;
    }

    /**
     * @return int
     */
    public function getCarrierSpace(): int
    {
        return $this->carrierSpace;
    }

    /**
     * @param int $carrierSpace
     *
     * @return void
     */
    public function setCarrierSpace(int $carrierSpace): void
    {
        $this->carrierSpace = $carrierSpace;
    }

    /**
     * @return int
     */
    public function getCarrierSpaceConsumption(): int
    {
        return $this->carrierSpaceConsumption;
    }

    /**
     * @param int $carrierSpaceConsumption
     *
     * @return void
     */
    public function setCarrierSpaceConsumption(int $carrierSpaceConsumption): void
    {
        $this->carrierSpaceConsumption = $carrierSpaceConsumption;
    }

    /**
     * @return int
     */
    public function getExtractorStealAmount(): int
    {
        return $this->extractorStealAmount;
    }

    /**
     * @param int $extractorStealAmount
     *
     * @return void
     */
    public function setExtractorStealAmount(int $extractorStealAmount): void
    {
        $this->extractorStealAmount = $extractorStealAmount;
    }

    /**
     * @return int
     */
    public function getExtractorGuardAmount(): int
    {
        return $this->extractorGuardAmount;
    }

    /**
     * @param int $extractorGuardAmount
     *
     * @return void
     */
    public function setExtractorGuardAmount(int $extractorGuardAmount): void
    {
        $this->extractorGuardAmount = $extractorGuardAmount;
    }

    /**
     * @return int
     */
    public function getScanRelaisFactor(): int
    {
        return $this->scanRelaisFactor;
    }

    /**
     * @param int $scanRelaisFactor
     *
     * @return void
     */
    public function setScanRelaisFactor(int $scanRelaisFactor): void
    {
        $this->scanRelaisFactor = $scanRelaisFactor;
    }

    /**
     * @return int
     */
    public function getScanBlockerFactor(): int
    {
        return $this->scanBlockerFactor;
    }

    /**
     * @param int $scanBlockerFactor
     *
     * @return void
     */
    public function setScanBlockerFactor(int $scanBlockerFactor): void
    {
        $this->scanBlockerFactor = $scanBlockerFactor;
    }

    /**
     * @return \GC\Faction\Model\Faction
     */
    public function getFaction(): Faction
    {
        return $this->faction;
    }

    /**
     * @param \GC\Faction\Model\Faction $faction
     *
     * @return void
     */
    public function setFaction(Faction $faction): void
    {
        $this->faction = $faction;
    }

    /**
     * @return \GC\Universe\Model\Universe
     */
    public function getUniverse(): Universe
    {
        return $this->universe;
    }

    /**
     * @return \GC\Technology\Model\Technology|null
     */
    public function getRequiredTechnology(): ?Technology
    {
        return $this->requiredTechnology;
    }

    /**
     * @param \GC\Technology\Model\Technology|null $requiredTechnology
     */
    public function setRequiredTechnology(?Technology $requiredTechnology): void
    {
        $this->requiredTechnology = $requiredTechnology;
    }

    /**
     * @param \GC\Unit\Model\Unit $targetUnit
     * @param int $distribution
     * @param string $attackPower
     *
     * @return \GC\Unit\Model\UnitCombatSetting
     */
    public function addUnitCombatSetting(Unit $targetUnit, int $distribution, string $attackPower): UnitCombatSetting
    {
        $unitCombatSetting = new UnitCombatSetting($this, $targetUnit, $distribution, $attackPower);
        $this->unitCombatSettings->add($unitCombatSetting);

        return $unitCombatSetting;
    }

    /**
     * @return \GC\Unit\Model\UnitCombatSetting[]
     */
    public function getUnitCombatSettings(): array
    {
        return $this->unitCombatSettings->getValues();
    }
}
