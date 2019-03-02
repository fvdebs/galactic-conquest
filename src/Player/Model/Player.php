<?php

declare(strict_types=1);

namespace GC\Player\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Faction\Model\Faction;
use GC\Galaxy\Model\Galaxy;
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
     * @Column(name="metal", type="bigint", nullable=false)
     */
    private $metal;

    /**
     * @var int
     *
     * @Column(name="crystal", type="bigint", nullable=false)
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
     * @Column(name="points", type="bigint", nullable=false)
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
     * @ManyToOne(targetEntity="GC\Universe\Model\Universe", inversedBy="players")
     * @JoinColumn(name="universe_id", referencedColumnName="universe_id", nullable=false)
     */
    private $universe;

    /**
     * @var \GC\User\Model\User
     *
     * @ManyToOne(targetEntity="\GC\User\Model\User")
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
     * @param \GC\User\Model\User $user
     * @param \GC\Faction\Model\Faction $faction
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     */
    public function __construct(User $user, Faction $faction, Galaxy $galaxy)
    {
        $this->playerFleets = new ArrayCollection();
        $this->playerTechnologies = new ArrayCollection();
        $this->playerUnitConstructions = new ArrayCollection();
        $this->playerCombatReports = new ArrayCollection();

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
        $this->extractorMetal = $this->extractorMetal + $number;
        $this->decreaseMetal($this->calculateMetalCostForExtractors($number, $this->getNumberOfExtractors()));
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function buildCrystalExtractors(int $number): void
    {
        $this->extractorCrystal = $this->extractorCrystal + $number;
        $this->decreaseMetal($this->calculateMetalCostForExtractors($number, $this->getNumberOfExtractors()));
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

        $initialExtractorCost = $resourceCostPerExtractor;
        for ($currentExtractors = 0; $currentExtractors < $startExtractors; $currentExtractors++) {
            $initialExtractorCost = $resourceCostPerExtractor * $currentExtractors;
        }

        $total = 0;
        $currentCostForExtractor = $initialExtractorCost;
        for ($currentExtractors = 0; $currentExtractors < $number; $currentExtractors++) {
            $currentCostForExtractor = $currentCostForExtractor + $resourceCostPerExtractor;
            $total += $currentCostForExtractor;
        }

        return $total;
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

        return $this->points;
    }

    /**
     * @return int
     */
    protected function calculateResourcePoints(): int
    {
        $calculation = ($this->metal + $this->crystal) / $this->universe->getResourcePointsDivider();

        return (int) \round($calculation, 0, PHP_ROUND_HALF_UP);
    }

    /**
     * @return int
     */
    protected function calculateExtractorPoints(): int
    {
        $calculation = ($this->getExtractorMetal() + $this->getExtractorCrystal()) * $this->universe->getExtractorPoints();

        return (int) \round($calculation, 0, PHP_ROUND_HALF_UP);
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function buildScanBlocker(int $number): void
    {
        $this->scanBlocker = $this->scanBlocker + $number;
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
        $this->scanRelays = $this->scanRelays + $number;
        $this->decreaseMetal($this->universe->getScanRelayMetalCost() * $number);
        $this->decreaseCrystal($this->universe->getScanRelayCrystalCost() * $number);
    }

    /**
     * @param int $number
     *
     * @return void
     */
    protected function increaseMetal(int $number): void
    {
        $this->metal = $this->metal + $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    protected function decreaseMetal(int $number): void
    {
        $this->metal = $this->metal - $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    protected function increaseCrystal(int $number): void
    {
        $this->crystal = $this->crystal + $number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    protected function decreaseCrystal(int $number): void
    {
        $this->crystal = $this->crystal - $number;
    }

    /**
     * @return int
     */
    protected function getMetalIncomePerTick(): int
    {
        return $this->extractorMetal * $this->universe->getExtractorMetalIncome();
    }

    /**
     * @return int
     */
    protected function getCrystalIncomePerTick(): int
    {
        return $this->extractorCrystal * $this->universe->getExtractorCrystalIncome();
    }

    /**
     * @return void
     */
    public function increaseResourceIncome(): void
    {
        $this->increaseMetal($this->getMetalIncomePerTick());
        $this->increaseCrystal($this->getCrystalIncomePerTick());
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
}