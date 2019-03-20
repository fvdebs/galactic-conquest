<?php

declare(strict_types=1);

namespace GC\Technology\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Faction\Model\Faction;
use GC\Universe\Model\Universe;

/**
 * @Table(name="technology")
 * @Entity(repositoryClass="GC\Technology\Model\TechnologyRepository")
 */
class Technology
{
    public const FEATURE_ALLIANCE_FINANCE = 'alliance.finance';

    public const FEATURE_GALAXY_FINANCE = 'galaxy.finance';
    public const FEATURE_GALAXY_TACTIC = 'galaxy.tactic';
    public const FEATURE_GALAXY_TACTIC_INCOMING = 'galaxy.tactic.incoming';
    public const FEATURE_GALAXY_TACTIC_FLEET = 'galaxy.tactic.fleet';

    public const FEATURE_PLAYER_TRADE = 'player.trade';

    /**
     * @var int
     *
     * @Column(name="technology_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $technologyId;

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
     * @var string|null
     *
     * @Column(name="feature_key", type="string", length=150, nullable=true)
     */
    private $featureKey;

    /**
     * @var bool
     *
     * @Column(name="is_alliance_technology", type="boolean", nullable=false)
     */
    private $isAllianceTechnology;

    /**
     * @var bool
     *
     * @Column(name="is_player_technology", type="boolean", nullable=false)
     */
    private $isPlayerTechnology;

    /**
     * @var bool
     *
     * @Column(name="is_galaxy_technology", type="boolean", nullable=false)
     */
    private $isGalaxyTechnology;

    /**
     * @var int
     *
     * @Column(name="metal_cost", type="integer", nullable=false)
     */
    private $metalCost;

    /**
     * @var int
     *
     * @Column(name="crystal_cost", type="integer", nullable=false)
     */
    private $crystalCost;

    /**
     * @var int
     *
     * @Column(name="metal_production", type="integer", nullable=false)
     */
    private $metalProduction;

    /**
     * @var int
     *
     * @Column(name="crystal_production", type="integer", nullable=false)
     */
    private $crystalProduction;

    /**
     * @var int
     *
     * @Column(name="ticks_to_build", type="integer", nullable=false)
     */
    private $ticksToBuild;

    /**
     * @var \GC\Faction\Model\Faction
     *
     * @ManyToOne(targetEntity="\GC\Faction\Model\Faction")
     * @JoinColumn(name="faction_id", referencedColumnName="faction_id", nullable=false)
     */
    private $faction;

    /**
     * @var \GC\Technology\Model\TechnologyCondition[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Technology\Model\TechnologyCondition", mappedBy="sourceTechnology", cascade={"all"}, orphanRemoval=true)
     */
    private $technologyConditions;

    /**
     * @var \GC\Universe\Model\Universe
     *
     * @ManyToOne(targetEntity="GC\Universe\Model\Universe", inversedBy="technologies")
     * @JoinColumn(name="universe_id", referencedColumnName="universe_id", nullable=false)
     */
    private $universe;

    /**
     * @param string $name
     * @param \GC\Faction\Model\Faction $faction
     */
    public function __construct(string $name, Faction $faction)
    {
        $this->technologyConditions = new ArrayCollection();
        $this->universe = $faction->getUniverse();
        $this->name = $name;
        $this->faction = $faction;
        $this->description = '';
        $this->isAllianceTechnology = false;
        $this->isPlayerTechnology = false;
        $this->isGalaxyTechnology = false;
        $this->metalCost = 5000;
        $this->crystalCost = 5000;
        $this->ticksToBuild = 10;
        $this->crystalProduction = 0;
        $this->metalProduction = 0;
    }

    /**
     * @return int
     */
    public function getTechnologyId(): int
    {
        return $this->technologyId;
    }

    /**
     * @return int
     */
    public function getMetalProduction(): int
    {
        return $this->metalProduction;
    }

    /**
     * @param int $metalProduction
     *
     * @return void
     */
    public function setMetalProduction(int $metalProduction): void
    {
        $this->metalProduction = $metalProduction;
    }

    /**
     * @return int
     */
    public function getCrystalProduction(): int
    {
        return $this->crystalProduction;
    }

    /**
     * @param int $crystalProduction
     *
     * @return void
     */
    public function setCrystalProduction(int $crystalProduction): void
    {
        $this->crystalProduction = $crystalProduction;
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
     * @return string|null
     */
    public function getFeatureKey(): ?string
    {
        return $this->featureKey;
    }

    /**
     * @param string|null $featureKey
     *
     * @return void
     */
    public function setFeatureKey(?string $featureKey): void
    {
        $this->featureKey = $featureKey;
    }

    /**
     * @return bool
     */
    public function isAllianceTechnology(): bool
    {
        return $this->isAllianceTechnology;
    }

    /**
     * @param bool $isAllianceTechnology
     *
     * @return void
     */
    public function setIsAllianceTechnology(bool $isAllianceTechnology): void
    {
        $this->isAllianceTechnology = $isAllianceTechnology;
    }

    /**
     * @return bool
     */
    public function isPlayerTechnology(): bool
    {
        return $this->isPlayerTechnology;
    }

    /**
     * @param bool $isPlayerTechnology
     *
     * @return void
     */
    public function setIsPlayerTechnology(bool $isPlayerTechnology): void
    {
        $this->isPlayerTechnology = $isPlayerTechnology;
    }

    /**
     * @return bool
     */
    public function isGalaxyTechnology(): bool
    {
        return $this->isGalaxyTechnology;
    }

    /**
     * @param bool $isGalaxyTechnology
     *
     * @return void
     */
    public function setIsGalaxyTechnology(bool $isGalaxyTechnology): void
    {
        $this->isGalaxyTechnology = $isGalaxyTechnology;
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
     * @param \GC\Technology\Model\Technology $targetTechnology
     *
     * @return \GC\Technology\Model\TechnologyCondition
     */
    public function addTechnologyCondition(Technology $targetTechnology): TechnologyCondition
    {
        $technologyCondition = new TechnologyCondition($this, $targetTechnology);
        $this->technologyConditions->add($technologyCondition);

        return $technologyCondition;
    }

    /**
     * @return \GC\Technology\Model\TechnologyCondition[]
     */
    public function getTechnologyConditions(): array
    {
        return $this->technologyConditions->getValues();
    }

    /**
     * @return int
     */
    public function calculateMetalIncomePerDay(): int
    {
        return $this->universe->calculateTicksPerDay() * $this->getMetalProduction();
    }

    /**
     * @return int
     */
    public function calculateCrystalIncomePerDay(): int
    {
        return $this->universe->calculateTicksPerDay() * $this->getCrystalProduction();
    }
}
