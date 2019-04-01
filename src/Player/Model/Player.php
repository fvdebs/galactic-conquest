<?php

declare(strict_types=1);

namespace GC\Player\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Faction\Model\Faction;
use GC\Galaxy\Model\Galaxy;
use GC\Scan\Model\Scan;
use GC\Technology\Model\Technology;
use GC\Unit\Model\Unit;
use GC\Universe\Model\Universe;
use GC\User\Model\User;

/**
 * @Table(name="player")
 * @Entity(repositoryClass="GC\Player\Model\PlayerRepository")
 */
class Player
{
    /**
     * @var int
     *
     * @Column(name="player_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $playerId;

    /**
     * @var int
     *
     * @Column(name="galaxy_position", type="integer", nullable=false)
     */
    private $galaxyPosition;

    /**
     * @var int
     *
     * @Column(name="metal", type="integer", nullable=false)
     */
    private $metal;

    /**
     * @var int
     *
     * @Column(name="crystal", type="integer", nullable=false)
     */
    private $crystal;

    /**
     * @var int
     *
     * @Column(name="extractor_metal", type="integer", nullable=false)
     */
    private $extractorMetal;

    /**
     * @var int
     *
     * @Column(name="extractor_crystal", type="integer", nullable=false)
     */
    private $extractorCrystal;

    /**
     * @var int
     *
     * @Column(name="scan_relays", type="integer", nullable=false)
     */
    private $scanRelays;

    /**
     * @var int
     *
     * @Column(name="scan_blocker", type="integer", nullable=false)
     */
    private $scanBlocker;

    /**
     * @var int
     *
     * @Column(name="alliance_scan_relays", type="integer", nullable=false)
     */
    private $allianceScanRelays;

    /**
     * @var int
     *
     * @Column(name="points", type="integer", nullable=false)
     */
    private $points;

    /**
     * @var bool
     *
     * @Column(name="is_admiral", type="boolean", nullable=false)
     */
    private $isAdmiral;
    /**
     * @var bool
     *
     * @Column(name="is_commander", type="boolean", nullable=false)
     */
    private $isCommander;

    /**
     * @var int
     *
     * @Column(name="ranking_position", type="integer", nullable=false)
     */
    private $rankingPosition;

    /**
     * @var \GC\Faction\Model\Faction
     *
     * @ManyToOne(targetEntity="\GC\Faction\Model\Faction")
     * @JoinColumn(name="faction_id", referencedColumnName="faction_id", nullable=false)
     */
    private $faction;

    /**
     * @var \GC\Galaxy\Model\Galaxy
     *
     * @ManyToOne(targetEntity="\GC\Galaxy\Model\Galaxy", inversedBy="players")
     * @JoinColumn(name="galaxy_id", referencedColumnName="galaxy_id", nullable=false)
     */
    private $galaxy;

    /**
     * @var \GC\Universe\Model\Universe
     *
     * @ManyToOne(targetEntity="\GC\Universe\Model\Universe")
     * @JoinColumn(name="universe_id", referencedColumnName="universe_id", nullable=false)
     */
    private $universe;

    /**
     * @var \GC\User\Model\User
     *
     * @ManyToOne(targetEntity="\GC\User\Model\User", inversedBy="players")
     * @JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @var \GC\Player\Model\PlayerFleet[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\PlayerFleet", mappedBy="player", cascade={"all"}, orphanRemoval=true)
     */
    private $playerFleets;

    /**
     * @var \GC\Player\Model\PlayerFleet[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\PlayerFleet", mappedBy="targetPlayer", cascade={"all"}, orphanRemoval=true)
     */
    private $targetPlayerFleets;

    /**
     * @var \GC\Player\Model\PlayerTechnology[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\PlayerTechnology", mappedBy="player", cascade={"all"}, orphanRemoval=true)
     */
    private $playerTechnologies;

    /**
     * @var \GC\Player\Model\PlayerUnitConstruction[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\PlayerUnitConstruction", mappedBy="player", cascade={"all"}, orphanRemoval=true)
     */
    private $playerUnitConstructions;

    /**
     * @var \GC\Player\Model\PlayerCombatReport[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\PlayerCombatReport", mappedBy="player", cascade={"all"}, orphanRemoval=true)
     * @OrderBy({"createdAt" = "ASC"})
     */
    private $playerCombatReports;

    /**
     * @var \GC\Scan\Model\Scan[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Scan\Model\Scan", mappedBy="player", cascade={"all"}, orphanRemoval=true)
     */
    private $scans;

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Faction\Model\Faction $faction
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     */
    public function __construct(User $user, Faction $faction, Galaxy $galaxy)
    {
        $this->playerFleets = new ArrayCollection();
        $this->targetPlayerFleets = new ArrayCollection();
        $this->playerTechnologies = new ArrayCollection();
        $this->playerUnitConstructions = new ArrayCollection();
        $this->playerCombatReports = new ArrayCollection();
        $this->scans = new ArrayCollection();

        $this->user = $user;
        $this->faction = $faction;
        $this->universe = $galaxy->getUniverse();
        $this->galaxyPosition = $galaxy->getNextFreeGalaxyPosition();
        $this->galaxy = $galaxy;
        $this->metal = 5000;
        $this->crystal = 5000;
        $this->scanRelays = 0;
        $this->scanBlocker = 0;
        $this->allianceScanRelays = 0;
        $this->points = 0;
        $this->rankingPosition = 0;
        $this->extractorMetal = 0;
        $this->extractorCrystal = 0;
        $this->isAdmiral = false;
        $this->isCommander = false;

        $playerFleetOrbit = $this->createPlayerFleet();
        $playerFleetOrbit->setIsOrbit(true);

        $playerFleetStationary = $this->createPlayerFleet();
        $playerFleetStationary->setIsStationary(true);

        $this->calculatePoints();
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
    }

    /**
     * @return bool
     */
    public function isAdmiral(): bool
    {
        return $this->isAdmiral;
    }

    /**
     * @return bool
     */
    public function isCommander(): bool
    {
        return $this->isCommander;
    }

    /**
     * @return int
     */
    public function getGalaxyPosition(): int
    {
        return $this->galaxyPosition;
    }

    /**
     * @return int
     */
    public function getMetal(): int
    {
        return $this->metal;
    }

    /**
     * @return int
     */
    public function getCrystal(): int
    {
        return $this->crystal;
    }

    /**
     * @return int
     */
    public function getExtractorMetal(): int
    {
        return $this->extractorMetal;
    }

    /**
     * @return int
     */
    public function getExtractorCrystal(): int
    {
        return $this->extractorCrystal;
    }

    /**
     * @return int
     */
    public function getScanRelays(): int
    {
        return $this->scanRelays;
    }

    /**
     * @return int
     */
    public function getScanBlocker(): int
    {
        return $this->scanBlocker;
    }

    /**
     * @return int
     */
    public function getAllianceScanRelays(): int
    {
        return $this->allianceScanRelays;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getRankingPosition(): int
    {
        return $this->rankingPosition;
    }

    /**
     * @param int $rankingPosition
     *
     * @return void
     */
    public function setRankingPosition(int $rankingPosition): void
    {
        $this->rankingPosition = $rankingPosition;
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
     * @return \GC\Galaxy\Model\Galaxy
     */
    public function getGalaxy(): Galaxy
    {
        return $this->galaxy;
    }

    /**
     * @return \GC\Universe\Model\Universe
     */
    public function getUniverse(): Universe
    {
        return $this->universe;
    }

    /**
     * @return \GC\User\Model\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     */
    public function relocate(Galaxy $galaxy): void
    {
        $this->galaxy = $galaxy;
        $this->galaxyPosition = $galaxy->getNextFreeGalaxyPosition();
    }

    /**
     * @return int
     */
    public function getNumberOfExtractors(): int
    {
        return $this->extractorCrystal + $this->extractorMetal;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function buildMetalExtractors(int $number): void
    {
        $this->decreaseMetal($this->calculateMetalCostForExtractors($number, $this->getNumberOfExtractors()));
        $this->extractorMetal += $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function buildCrystalExtractors(int $number): void
    {
        $this->decreaseMetal($this->calculateMetalCostForExtractors($number, $this->getNumberOfExtractors()));
        $this->extractorCrystal += $number;
    }

    /**
     * @return int
     */
    public function calculateMetalCostForNextExtractor(): int
    {
        return $this->calculateMetalCostForExtractors(1, $this->getNumberOfExtractors());
    }

    /**
     * @param int $number
     * @param int $startExtractors
     *
     * @return int
     */
    public function calculateMetalCostForExtractors(int $number, int $startExtractors): int
    {
        $resourceCostPerExtractor = $this->universe->getExtractorStartCost();
        $metalCostForNextExtractor = $startExtractors * $resourceCostPerExtractor;
        $metalCost = 0;

        while ($number > 0) {
            $metalCost += $metalCostForNextExtractor;
            $metalCostForNextExtractor += $resourceCostPerExtractor;
            $number--;
        }

        return $metalCost;
    }

    /**
     * @return int
     */
    public function calculateMaxExtractorConstruction(): int
    {
        $extractorCost = $this->universe->getExtractorStartCost();
        $metal = $this->metal;
        $numberOfExtractors = $this->getNumberOfExtractors();

        $numberOfPossibleExtractors = 0;

        while ($metal >= $numberOfExtractors * $extractorCost) {
            $metal -= $numberOfExtractors * $extractorCost;
            $numberOfExtractors++;
            $numberOfPossibleExtractors++;
        }

        return $numberOfPossibleExtractors;
    }

    /**
     * @return int
     */
    public function calculatePoints(): int
    {
        $this->points = 0;
        $this->points += $this->calculateResourcePoints();
        $this->points += $this->calculateExtractorPoints();
        $this->points += $this->calculateTechnologyPoints();
        $this->points += $this->calculateUnitPoints();

        return $this->points;
    }

    /**
     * @return int
     */
    protected function calculateResourcePoints(): int
    {
        $calculation = ($this->metal + $this->crystal) / $this->universe->getResourcePointsDivider();

        return (int) \round($calculation);
    }

    /**
     * @return int
     */
    protected function calculateExtractorPoints(): int
    {
        $calculation = ($this->getExtractorMetal() + $this->getExtractorCrystal()) * $this->universe->getExtractorPoints();

        return (int) \round($calculation);
    }

    /**
     * @return int
     */
    protected function calculateTechnologyPoints(): int
    {
        $calculation = 0;

        foreach ($this->getPlayerTechnologies() as $playerTechnology) {
            $calculation += $playerTechnology->getTechnology()->getCrystalCost();
            $calculation += $playerTechnology->getTechnology()->getMetalCost();
        }

        return (int) \round($calculation);
    }

    /**
     * @return int
     */
    protected function calculateUnitPoints(): int
    {
        $calculation = 0;

        foreach ($this->getPlayerFleets() as $playerFleet) {
            $calculation += $playerFleet->calculateUnitPoints();
        }

        return (int) \round($calculation);
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function buildScanBlocker(int $number): void
    {
        $this->scanBlocker += $number;

        $this->decreaseMetal($this->universe->getScanBlockerMetalCost() * $number);
        $this->decreaseCrystal($this->universe->getScanBlockerCrystalCost() * $number);
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function buildScanRelays(int $number): void
    {
        $this->scanRelays += $number;

        $this->decreaseMetal($this->universe->getScanRelayMetalCost() * $number);
        $this->decreaseCrystal($this->universe->getScanRelayCrystalCost() * $number);
    }

    /**
     * @param int $metal
     * @param int $crystal
     *
     * @return bool
     */
    protected function hasResources(int $metal, int $crystal): bool
    {
        return $this->metal >= $metal && $this->crystal >= $crystal;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function setMetal(int $number): void
    {
        $this->metal = $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function setCrystal(int $number): void
    {
        $this->crystal = $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function increaseMetal(int $number): void
    {
        $this->metal += $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function decreaseMetal(int $number): void
    {
        $this->metal -= $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function increaseCrystal(int $number): void
    {
        $this->crystal += $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function decreaseCrystal(int $number): void
    {
        $this->crystal -= $number;
    }

    /**
     * @return int
     */
    public function calculateMetalIncomePerTick(): int
    {
        $calculation = $this->extractorMetal * $this->universe->getExtractorMetalIncome();

        return (int) \round($calculation);
    }

    /**
     * @return int
     */
    public function calculateCrystalIncomePerTick(): int
    {
        $calculation = $this->extractorCrystal * $this->universe->getExtractorCrystalIncome();

        return (int) \round($calculation);
    }

    /**
     * @return int
     */
    public function calculateMetalIncomePerDay(): int
    {
        return $this->universe->calculateTicksPerDay() * $this->calculateMetalIncomePerTick();
    }

    /**
     * @return int
     */
    public function calculateCrystalIncomePerDay(): int
    {
        return $this->universe->calculateTicksPerDay() * $this->calculateCrystalIncomePerTick();
    }

    /**
     * @return int
     */
    public function calculateMetalTaxPerTick(): int
    {
        return $this->galaxy->calculateMetalTaxFor($this);
    }

    /**
     * @return int
     */
    public function calculateMetalTaxPerDay(): int
    {
        return $this->galaxy->calculateMetalTaxFor($this) * $this->universe->calculateTicksPerDay();
    }

    /**
     * @return int
     */
    public function calculateCrystalTaxPerTick(): int
    {
        return $this->galaxy->calculateCrystalTaxFor($this);
    }

    /**
     * @return int
     */
    public function calculateCrystalTaxPerDay(): int
    {
        return $this->galaxy->calculateCrystalTaxFor($this) * $this->universe->calculateTicksPerDay();
    }

    /**
     * @return void
     */
    public function increaseResourceIncomePerTick(): void
    {
        $this->increaseMetal(
            $this->calculateMetalIncomePerTick() - $this->calculateMetalTaxPerTick()
        );

        $this->increaseCrystal(
            $this->calculateCrystalIncomePerTick() - $this->calculateCrystalTaxPerTick()
        );
    }

    /**
     * @return void
     */
    public function grantCommanderRole(): void
    {
        foreach ($this->galaxy->getPlayers() as $member) {
            $member->revokeCommanderRole();
        }

        $this->isCommander = true;
    }

    /**
     * @return void
     */
    public function revokeCommanderRole(): void
    {
        $this->isCommander = false;
    }

    /**
     * @return void
     */
    public function grantAdmiralRole(): void
    {
        if ($this->galaxy->getAlliance() === null) {
            return;
        }

        foreach ($this->galaxy->getAlliance()->getGalaxies() as $memberGalaxy) {
            foreach ($memberGalaxy->getPlayers() as $member) {
                $member->revokeAdmiralRole();
            }
        }

        $this->isAdmiral = true;
    }

    /**
     * @return void
     */
    public function revokeAdmiralRole(): void
    {
        $this->isAdmiral = false;
    }

    /**
     * @param \GC\Player\Model\Player $player
     *
     * @return bool
     */
    public function isAlliedWith(Player $player): bool
    {
        return false;
    }

    /**
     * @param \GC\Player\Model\Player $player
     *
     * @return bool
     */
    public function isInSameAllianceAs(Player $player): bool
    {
        $currentPlayersAlliance = $this->getGalaxy()->getAlliance();
        $playersAlliance = $player->getGalaxy()->getAlliance();

        if ($currentPlayersAlliance === null || $playersAlliance === null) {
            return false;
        }

        return $currentPlayersAlliance->getAllianceId() === $playersAlliance->getAllianceId();
    }

    /**
     * @param \GC\Player\Model\Player $player
     *
     * @return bool
     */
    public function isInSameGalaxyAs(Player $player): bool
    {
        return $player->getGalaxy()->getGalaxyId() === $this->getGalaxy()->getGalaxyId();
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     * @param int $quantity
     *
     * @return \GC\Player\Model\PlayerUnitConstruction
     */
    public function buildUnit(Unit $unit, int $quantity): PlayerUnitConstruction
    {
        $playerUnitConstruction = new PlayerUnitConstruction($this, $unit, $quantity);
        $this->playerUnitConstructions->add($playerUnitConstruction);

        $this->decreaseMetal($this->calculateMetalCostForUnit($unit, $quantity));
        $this->decreaseCrystal($this->calculateCrystalCostForUnit($unit, $quantity));

        return $playerUnitConstruction;
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     * @param int $quantity
     *
     * @return bool
     */
    public function hasResourcesForUnitAndQuantity(Unit $unit, int $quantity): bool
    {
        return $this->hasResources(
            $this->calculateMetalCostForUnit($unit, $quantity),
            $this->calculateCrystalCostForUnit($unit, $quantity)
        );
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return bool
     */
    public function hasUnitRequirementsFor(Unit $unit): bool
    {
        $requiredTechnology = $unit->getRequiredTechnology();
        if ($requiredTechnology === null) {
            return true;
        }

        return $this->isPlayerTechnologyCompleted($requiredTechnology);
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return bool
     */
    public function isUnitInConstruction(Unit $unit): bool
    {
        foreach ($this->getPlayerUnitConstructions() as $playerUnitConstruction) {
            if ($playerUnitConstruction->getUnit()->getUnitId() === $unit->getUnitId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return \GC\Player\Model\PlayerUnitConstruction|null
     */
    public function getPlayerUnitConstructionOf(Unit $unit): ?PlayerUnitConstruction
    {
        foreach ($this->getPlayerUnitConstructions() as $playerUnitConstruction) {
            if ($playerUnitConstruction->getUnit()->getUnitId() === $unit->getUnitId()) {
                return $playerUnitConstruction;
            }
        }

        return null;
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return int
     */
    public function calculateMaxUnits(Unit $unit): int
    {
        $maxUnitsMetal = \floor($this->getMetal() / $unit->getMetalCost());
        $maxUnitsCrystal = \floor($this->getCrystal() / $unit->getCrystalCost());

        if ($maxUnitsMetal <= $maxUnitsCrystal){
            return (int) \floor($maxUnitsMetal);
        }

        if ($maxUnitsCrystal <= $maxUnitsMetal){
            return (int) \floor($maxUnitsCrystal);
        }

        return 0;
    }

    /**
     * @return \GC\Player\Model\PlayerUnitConstruction[]
     */
    public function getPlayerUnitConstructions(): array
    {
        return $this->playerUnitConstructions->getValues();
    }

    /**
     * @return void
     */
    public function finishUnitConstructions(): void
    {
        foreach ($this->getPlayerUnitConstructions() as $playerUnitConstruction) {
            if ($playerUnitConstruction->getTicksLeft() > 0) {
                $playerUnitConstruction->decreaseTicksLeft();
            }

            if ($playerUnitConstruction->getTicksLeft() === 0) {
                if ($playerUnitConstruction->getUnit()->isStationary()) {
                    $playerFleet = $this->getPlayerFleetStationary();
                } else {
                    $playerFleet = $this->getPlayerFleetOrbit();
                }

                $playerFleet->increaseUnitQuantity(
                    $playerUnitConstruction->getUnit(),
                    $playerUnitConstruction->getQuantity()
                );

                $this->playerUnitConstructions->removeElement($playerUnitConstruction);
            }
        }
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     * @param int $quantity
     *
     * @return int
     */
    protected function calculateMetalCostForUnit(Unit $unit, int $quantity): int
    {
        return (int) \round($unit->getMetalCost() * $quantity);
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     * @param int $quantity
     *
     * @return int
     */
    protected function calculateCrystalCostForUnit(Unit $unit, int $quantity): int
    {
        return (int) \round($unit->getCrystalCost() * $quantity);
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return \GC\Player\Model\PlayerTechnology
     */
    public function createPlayerTechnology(Technology $technology): PlayerTechnology
    {
        $playerTechnology = new PlayerTechnology($this, $technology);
        $this->playerTechnologies->add($playerTechnology);

        $this->decreaseMetal($technology->getMetalCost());
        $this->decreaseCrystal($technology->getCrystalCost());

        return $playerTechnology;
    }

    /**
     * @return \GC\Player\Model\PlayerTechnology[]
     */
    public function getPlayerTechnologies(): array
    {
        return $this->playerTechnologies->getValues();
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return \GC\Player\Model\PlayerTechnology|null
     */
    public function getPlayerTechnologyByTechnology(Technology $technology): ?PlayerTechnology
    {
        foreach ($this->getPlayerTechnologies() as $playerTechnology) {
            if ($playerTechnology->getTechnology()->getTechnologyId() === $technology->getTechnologyId()) {
                return $playerTechnology;
            }
        }

        return null;
    }

    /**
     * @return \GC\Player\Model\PlayerTechnology[]
     */
    public function getPlayerTechnologiesInConstruction(): array
    {
        $playerTechnologies = [];

        foreach ($this->getPlayerTechnologies() as $playerTechnology) {
            if ($playerTechnology->isInConstruction()) {
                $playerTechnologies[] = $playerTechnology;
            }
        }

        return $playerTechnologies;
    }

    /**
     * @return \GC\Technology\Model\Technology[]
     */
    public function getTechnologiesInConstruction(): array
    {
        $technologies = [];

        foreach ($this->getPlayerTechnologiesInConstruction() as $playerTechnology) {
            $technologies[] = $playerTechnology->getTechnology();
        }

        return $technologies;
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return bool
     */
    public function isPlayerTechnologyInConstruction(Technology $technology): bool
    {
        $playerTechnology = $this->getPlayerTechnologyByTechnology($technology);

        if ($playerTechnology === null) {
            return false;
        }

        return $playerTechnology->isInConstruction();
    }

    /**
     * @return \GC\Player\Model\PlayerTechnology[]
     */
    public function getPlayerTechnologiesCompleted(): array
    {
        $playerTechnologies = [];

        foreach ($this->getPlayerTechnologies() as $playerTechnology) {
            if ($playerTechnology->isCompleted()) {
                $playerTechnologies[] = $playerTechnology;
            }
        }

        return $playerTechnologies;
    }

    /**
     * @return \GC\Technology\Model\Technology[]
     */
    public function getTechnologiesCompleted(): array
    {
        $technologies = [];

        foreach ($this->getPlayerTechnologiesCompleted() as $playerTechnology) {
            $technologies[] = $playerTechnology->getTechnology();
        }

        return $technologies;
    }

    /**
     * @return \GC\Technology\Model\Technology[]
     */
    public function getTechnologiesCompletedWithCrystalIncome(): array
    {
        $technologies = [];

        foreach ($this->getTechnologiesCompleted() as $technology) {
            if ($technology->getCrystalProduction() > 0) {
                $technologies[] = $technology;
            }
        }

        return $technologies;
    }

    /**
     * @return int
     */
    public function getTotalCrystalIncomeFromTechnologiesPerTick(): int
    {
        $income = 0;

        foreach ($this->getTechnologiesCompletedWithMetalIncome() as $technology) {
            $income += $technology->getCrystalProduction();
        }

        return $income;
    }

    /**
     * @return int
     */
    public function getTotalCrystalIncomeFromTechnologiesPerDay(): int
    {
        $income = 0;

        foreach ($this->getTechnologiesCompletedWithMetalIncome() as $technology) {
            $income += $technology->calculateCrystalIncomePerDay();
        }

        return $income;
    }

    /**
     * @return \GC\Technology\Model\Technology[]
     */
    public function getTechnologiesCompletedWithMetalIncome(): array
    {
        $technologies = [];

        foreach ($this->getTechnologiesCompleted() as $technology) {
            if ($technology->getMetalProduction() > 0) {
                $technologies[] = $technology;
            }
        }

        return $technologies;
    }

    /**
     * @return int
     */
    public function getTotalMetalIncomeFromTechnologiesPerTick(): int
    {
        $income = 0;

        foreach ($this->getTechnologiesCompletedWithMetalIncome() as $technology) {
            $income += $technology->getMetalProduction();
        }

        return $income;
    }

    /**
     * @return int
     */
    public function getTotalMetalIncomeFromTechnologiesPerDay(): int
    {
        $income = 0;

        foreach ($this->getTechnologiesCompletedWithMetalIncome() as $technology) {
            $income += $technology->calculateMetalIncomePerDay();
        }

        return $income;
    }

    /**
     * @return \GC\Technology\Model\Technology[]
     */
    public function getTechnologiesCompletedWithIncome(): array
    {
        $technologies = [];

        foreach ($this->getTechnologiesCompleted() as $technology) {
            if ($technology->getMetalProduction() > 0 || $technology->getCrystalProduction() > 0) {
                $technologies[] = $technology;
            }
        }

        return $technologies;
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return bool
     */
    public function isPlayerTechnologyCompleted(Technology $technology): bool
    {
        $playerTechnology = $this->getPlayerTechnologyByTechnology($technology);

        if ($playerTechnology === null) {
            return false;
        }

        return $playerTechnology->isCompleted();
    }

    /**
     * @param string $featureKey
     *
     * @return bool
     */
    public function hasTechnologyByFeatureKey(string $featureKey): bool
    {
        foreach ($this->getPlayerTechnologies() as $playerTechnology) {
            if ($playerTechnology->isCompleted() && $playerTechnology->getTechnology()->getFeatureKey() === $featureKey) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return bool
     */
    public function hasResourcesForTechnology(Technology $technology): bool
    {
        return $this->hasResources(
            $technology->getMetalCost(),
            $technology->getCrystalCost()
        );
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return bool
     */
    public function hasTechnologyRequirementsFor(Technology $technology): bool
    {
        foreach ($technology->getTechnologyConditions() as $technologyCondition) {
            if (!$this->isPlayerTechnologyCompleted($technologyCondition->getTargetTechnology())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function finishPlayerTechnologyConstructions(): void
    {
        foreach ($this->getPlayerTechnologiesInConstruction() as $playerTechnology) {
            $playerTechnology->decreaseTicksLeft();
        }
    }

    /**
     * @param string $dataJson
     *
     * @throws \Exception
     *
     * @return \GC\Player\Model\PlayerCombatReport
     */
    public function createPlayerCombatReport(string $dataJson): PlayerCombatReport
    {
        $playerCombatReport = new PlayerCombatReport($dataJson, $this);
        $this->playerCombatReports->add($playerCombatReport);

        return $playerCombatReport;
    }

    /**
     * @return \GC\Player\Model\PlayerCombatReport[]
     */
    public function getPlayerCombatReports(): array
    {
        return $this->playerCombatReports->getValues();
    }

    /**
     * @param string $dataJson
     *
     * @throws \Exception
     *
     * @return \GC\Scan\Model\Scan
     */
    public function createScan(string $dataJson): Scan
    {
        $scan = new Scan($dataJson, $this);
        $this->scans->add($scan);

        return $scan;
    }

    /**
     * @return \GC\Scan\Model\Scan[]
     */
    public function getScans(): array
    {
        return $this->scans->getValues();
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getTargetPlayerFleets(): array
    {
        return $this->targetPlayerFleets->getValues();
    }

    /**
     * @param \GC\Player\Model\PlayerFleet $playerFleet
     *
     * @return void
     */
    public function addTargetPlayerFleet(PlayerFleet $playerFleet): void
    {
        if (!$this->hasTargetPlayerFleet($playerFleet)) {
            $this->targetPlayerFleets->add($playerFleet);
        }
    }

    /**
     * @param \GC\Player\Model\PlayerFleet $playerFleet
     *
     * @return void
     */
    public function removeTargetPlayerFleet(PlayerFleet $playerFleet): void
    {
        if ($this->hasTargetPlayerFleet($playerFleet)) {
            $this->targetPlayerFleets->removeElement($playerFleet);
        }
    }

    /**
     * @param \GC\Player\Model\PlayerFleet $playerFleet
     *
     * @return bool
     */
    public function hasTargetPlayerFleet(PlayerFleet $playerFleet): bool
    {
        return $this->targetPlayerFleets->contains($playerFleet);
    }

    /**
     * @return \GC\Player\Model\PlayerFleet
     */
    public function createPlayerFleet(): PlayerFleet
    {
        $playerFleet = new PlayerFleet($this);
        $this->playerFleets->add($playerFleet);

        return $playerFleet;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getPlayerFleets(): array
    {
        return $this->playerFleets->getValues();
    }

    /**
     * @param int $playerFleetId
     *
     * @return \GC\Player\Model\PlayerFleet|null
     */
    public function getPlayerFleetById(int $playerFleetId): ?PlayerFleet
    {
        foreach ($this->getPlayerFleets() as $playerFleet) {
            if ($playerFleetId === $playerFleet->getPlayerFleetId()) {
                return $playerFleet;
            }
        }

        return null;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet|null
     */
    public function getPlayerFleetOrbit(): ?PlayerFleet
    {
        foreach ($this->getPlayerFleets() as $playerFleet) {
            if ($playerFleet->isOrbit()) {
                return $playerFleet;
            }
        }

        return null;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet|null
     */
    public function getPlayerFleetStationary(): PlayerFleet
    {
        foreach ($this->getPlayerFleets() as $playerFleet) {
            if ($playerFleet->isStationary()) {
                return $playerFleet;
            }
        }

        return null;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getPlayerFleetsMovable(): array
    {
        $playerFleets = [];

        foreach ($this->getPlayerFleets() as $playerFleet) {
            if ($playerFleet->isMovable()) {
                $playerFleets[] =  $playerFleet;
            }
        }

        return $playerFleets;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getPlayerFleetsOrbitAndMovable(): array
    {
        $playerFleets = [];

        foreach ($this->getPlayerFleets() as $playerFleet) {
            if ($playerFleet->isMovable()) {
                $playerFleets[] = $playerFleet;
            }
        }

        \array_unshift($playerFleets, $this->getPlayerFleetOrbit());

        return $playerFleets;
    }

    /**
     * @param \GC\Unit\Model\Unit $unit
     *
     * @return int
     */
    public function getPlayerFleetsUnitQuantityOf(Unit $unit): int
    {
        $quantity = 0;

        foreach ($this->getPlayerFleets() as $playerFleet) {
            $quantity += $playerFleet->getUnitQuantityOf($unit);
        }

        return $quantity;
    }

    /**
     * @return int
     */
    public function getUnitsMovableQuantity(): int
    {
        $quantity = 0;

        foreach ($this->getPlayerFleetsOrbitAndMovable() as $playerFleet) {
            foreach ($playerFleet->getPlayerFleetUnits() as $playerFleetUnit) {
                $quantity += $playerFleetUnit->getQuantity();
            }
        }

        return $quantity;
    }

    /**
     * @return int
     */
    public function getUnitsStationaryQuantity(): int
    {
        $quantity = 0;

        foreach ($this->getPlayerFleetStationary()->getPlayerFleetUnits() as $playerFleetUnit) {
            $quantity += $playerFleetUnit->getQuantity();
        }

        return $quantity;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getPlayerFleetsAttackingOriginalOrRecalling(): array
    {
        $playerFleets = [];

        foreach ($this->getPlayerFleetsMovable() as $playerFleet) {
            if ($playerFleet->isAttackingOriginal()) {
                $playerFleets[] = $playerFleet;
            }
        }

        return $playerFleets;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getPlayerFleetsDefendingOriginalOrRecalling(): array
    {
        $playerFleets = [];

        foreach ($this->getPlayerFleetsMovable() as $playerFleet) {
            if ($playerFleet->isDefendingOriginal()) {
                $playerFleets[] = $playerFleet;
            }
        }

        return $playerFleets;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getPlayerFleetsWhichAreAttackingThisPlayer(): array
    {
        $targetPlayerFleets = [];

        foreach ($this->getTargetPlayerFleets() as $targetPlayerFleet) {
            if ($targetPlayerFleet->isAttacking()) {
                $targetPlayerFleets[] = $targetPlayerFleet;
            }
        }

        return $targetPlayerFleets;
    }

    /**
     * @return \GC\Player\Model\PlayerFleet[]
     */
    public function getPlayerFleetsWhichAreDefendingThisPlayer(): array
    {
        $targetPlayerFleets = [];

        foreach ($this->getTargetPlayerFleets() as $targetPlayerFleet) {
            if ($targetPlayerFleet->isDefending()) {
                $targetPlayerFleets[] = $targetPlayerFleet;
            }
        }

        return $targetPlayerFleets;
    }

    /**
     * @param int[] $quantityArray
     * @param int[] $playerFleetFromArray
     * @param int[] $playerFleetToArray
     *
     * @return void
     */
    public function moveUnits(array $quantityArray, array $playerFleetFromArray, array $playerFleetToArray): void
    {
        foreach ($quantityArray as $unitId => $quantity) {
            $quantity = \trim($quantity);
            if (!\array_key_exists($unitId, $playerFleetFromArray)
                || !\array_key_exists($unitId, $playerFleetToArray)
                || !\is_numeric($quantity)
                || empty($quantity)) {

                continue;
            }

            $playerFleetFromId = (int) $playerFleetFromArray[$unitId];
            $playerFleetToId = (int) $playerFleetToArray[$unitId];

            if ($playerFleetFromId === $playerFleetToId) {
                continue;
            }

            $playerFleetFrom = $this->getPlayerFleetById($playerFleetFromId);
            $playerFleetTo = $this->getPlayerFleetById($playerFleetToId);

            if ($playerFleetFrom === null
                || $playerFleetTo === null
                || $playerFleetFrom->isBusy()
                || $playerFleetTo->isBusy()) {
                continue;
            }

            $playerFleetFrom->moveUnitTo($playerFleetTo, (int) $unitId, (int) $quantity);
        }
    }

    /**
     * @param \GC\Player\Model\Player $targetPlayer
     *
     * @return bool
     */
    public function isAttackingAndIsTarget(Player $targetPlayer): bool
    {
        foreach ($this->getPlayerFleetsMovable() as $playerFleet) {
            if ($playerFleet->isAttacking() && $playerFleet->isTarget($targetPlayer)) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @return void
     */
    public function movePlayerFleetsForward(): void
    {
        foreach ($this->getPlayerFleetsMovable() as $movablePlayerFleet) {
            $movablePlayerFleet->decreaseTicksLeft();
        }
    }

    /**
     *
     * @return void
     */
    public function clearOrRecallPlayerFleets(): void
    {
        foreach ($this->getPlayerFleetsMovable() as $movablePlayerFleet) {
            $movablePlayerFleet->clearOrRecall();
        }
    }
}
