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
     * @param \GC\Universe\Model\Universe $universe
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     * @param int $galaxyPosition
     */
    public function __construct(User $user, Faction $faction, Universe $universe, Galaxy $galaxy, int $galaxyPosition)
    {
        $this->playerFleets = new ArrayCollection();
        $this->playerTechnologies = new ArrayCollection();
        $this->playerUnitConstructions = new ArrayCollection();
        $this->playerCombatReports = new ArrayCollection();

        $this->user = $user;
        $this->faction = $faction;
        $this->universe = $universe;
        $this->galaxy = $galaxy;
        $this->galaxyPosition = $galaxyPosition;
        $this->metal = 5000;
        $this->crystal = 5000;
        $this->scanRelays = 0;
        $this->scanBlocker = 0;
        $this->allianceScanRelays = 0;
        $this->points = 0;
        $this->rankingPosition = 0;
        $this->extractorMetal = 0;
        $this->extractorCrystal = 0;
    }

    /**
     * @return int
     */
    public function getPlayerId(): int
    {
        return $this->playerId;
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

    public function relocate(Galaxy $galaxy): void
    {

    }

    public function buildCrystalExtractorsBy(int $number): void
    {

    }

    public function buildMetalExtractorsBy(int $number): void
    {

    }

    public function stealCrystalExtractorsFrom(Player $victim, int $maxNumber): void
    {

    }

    public function stealMetalExtractorsFrom(Player $victim, int $maxNumber): void
    {

    }

    public function recalculatePoints(): int
    {
        return $this->points;
    }

    public function giveAllianceScanRelays(): void
    {

    }

    public function takeAllianceScanRelays(): void
    {

    }

    public function buildScanBlocker(int $number): void
    {

    }

    public function buildScanRelays(int $number): void
    {

    }

    protected function increaseMetal(int $number): void
    {

    }

    protected function decreaseMetal(int $number): void
    {

    }
    protected function increaseCrystal(int $number): void
    {

    }

    protected function decreaseCrystal(int $number): void
    {

    }

    public function tickIncreaseResources(): void
    {

    }

    public function tradeMetalWith(Player $player, int $decrease, int $increase): void
    {

    }

    public function tradeCrystalWith(Player $player, int $decrease, int $increase): void
    {

    }
}