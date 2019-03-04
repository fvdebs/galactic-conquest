<?php

declare(strict_types=1);

namespace GC\Universe\Model;

use DateTime;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use GC\Alliance\Model\Alliance;
use GC\Faction\Model\Faction;
use GC\Galaxy\Model\Galaxy;
use GC\Player\Model\Player;
use GC\Technology\Model\Technology;
use GC\Unit\Model\Unit;
use GC\User\Model\User;

/**
 * @Table(name="universe")
 * @Entity(repositoryClass="GC\Universe\Model\UniverseRepository")
 */
class Universe
{
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
     * @var DateTime|null
     *
     * @Column(name="last_tick_at", type="datetime", nullable=true)
     */
    private $lastTickAt;

    /**
     * @var int
     *
     * @Column(name="tick_interval", type="integer", nullable=false)
     */
    private $tickInterval;

    /**
     * @var DateTime|null
     *
     * @Column(name="last_ranking_at", type="datetime", nullable=true)
     */
    private $lastRankingAt;

    /**
     * @var int
     *
     * @Column(name="ranking_interval", type="integer", nullable=false)
     */
    private $rankingInterval;

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
     * @Column(name="ticks_defense_alliance", type="integer", nullable=false)
     */
    private $ticksDefenseAlliance;

    /**
     * @var int
     *
     * @Column(name="ticks_defense_allied", type="integer", nullable=false)
     */
    private $ticksDefenseAllied;

    /**
     * @var int
     *
     * @Column(name="max_private_glaxy_players", type="integer", nullable=false)
     */
    private $maxPrivateGalaxyPlayers;

    /**
     * @var int
     *
     * @Column(name="max_public_galaxy_players", type="integer", nullable=false)
     */
    private $maxPublicGalaxyPlayers;

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
     * @Column(name="extractor_metal_income", type="integer", nullable=false)
     */
    private $extractorMetalIncome;

    /**
     * @var int
     *
     * @Column(name="extractor_crystal_income", type="integer", nullable=false)
     */
    private $extractorCrystalIncome;

    /**
     * @var int
     *
     * @Column(name="extractor_start_cost", type="integer", nullable=false)
     */
    private $extractorStartCost;

    /**
     * @var int
     *
     * @Column(name="extractor_points", type="integer", nullable=false)
     */
    private $extractorPoints;

    /**
     * @var int
     *
     * @Column(name="resource_points_divider", type="integer", nullable=false)
     */
    private $resourcePointsDivider;

    /**
     * @var bool
     *
     * @Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var \GC\Player\Model\Player[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\Player", mappedBy="universe", cascade={"persist"}, orphanRemoval=true)
     * @OrderBy({"rankingPosition" = "ASC"})
     */
    private $players;

    /**
     * @var \GC\Galaxy\Model\Galaxy[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Galaxy\Model\Galaxy", mappedBy="universe", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     * @OrderBy({"number" = "ASC"})
     */
    private $galaxies;

    /**
     * @var \GC\Alliance\Model\Alliance[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Alliance\Model\Alliance", mappedBy="universe", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     * @OrderBy({"rankingPosition" = "ASC"})
     */
    private $alliances;

    /**
     * @var \GC\Unit\Model\Unit[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Unit\Model\Unit", mappedBy="universe", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     */
    private $units;

    /**
     * @var \GC\Technology\Model\Technology[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Technology\Model\Technology", mappedBy="universe", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     */
    private $technologies;

    /**
     * @var \GC\Faction\Model\Faction[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Faction\Model\Faction", mappedBy="universe", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     */
    private $factions;

    /**
     * @param string $name
     *
     * @throws \Exception
     */
    public function __construct(string $name)
    {
        $this->players = new ArrayCollection();
        $this->galaxies = new ArrayCollection();
        $this->alliances = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->factions = new ArrayCollection();

        $this->name = $name;
        $this->description = '';
        $this->ticksStartingAt = new DateTime();
        $this->tickInterval = 15;
        $this->tickCurrent = 0;
        $this->ticksAttack = 30;
        $this->ticksDefense = 20;
        $this->ticksDefenseAllied = 16;
        $this->ticksDefenseAlliance = 14;
        $this->maxPrivateGalaxyPlayers = 8;
        $this->maxPublicGalaxyPlayers = 12;
        $this->scanBlockerMetalCost = 5000;
        $this->scanBlockerCrystalCost = 2000;
        $this->scanRelayMetalCost = 2000;
        $this->scanRelayCrystalCost = 5000;
        $this->extractorMetalIncome = 50;
        $this->extractorCrystalIncome = 50;
        $this->extractorStartCost = 65;
        $this->extractorPoints = 15000;
        $this->resourcePointsDivider = 10;
        $this->isActive = false;
        $this->lastRankingAt = null;
        $this->rankingInterval = 12;
    }

    /**
     * @return int
     */
    public function getUniverseId(): int
    {
        return $this->universeId;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastTickAt(): ?DateTime
    {
        return $this->lastTickAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastRankingAt(): ?DateTime
    {
        return $this->lastRankingAt;
    }

    /**
     * @return int
     */
    public function getRankingInterval(): int
    {
        return $this->rankingInterval;
    }

    /**
     * @param int $rankingInterval
     */
    public function setRankingInterval(int $rankingInterval): void
    {
        $this->rankingInterval = $rankingInterval;
    }

    /**
     * @return int
     */
    public function getResourcePointsDivider(): int
    {
        return $this->resourcePointsDivider;
    }

    /**
     * @return int
     */
    public function getExtractorPoints(): int
    {
        return $this->extractorPoints;
    }

    /**
     * @return int
     */
    public function getExtractorStartCost(): int
    {
        return $this->extractorStartCost;
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
    public function getTicksDefenseAlliance(): int
    {
        return $this->ticksDefenseAlliance;
    }

    /**
     * @param int $ticksDefenseAlliance
     *
     * @return void
     */
    public function setTicksDefenseAlliance(int $ticksDefenseAlliance): void
    {
        $this->ticksDefenseAlliance = $ticksDefenseAlliance;
    }

    /**
     * @return int
     */
    public function getTicksDefenseAllied(): int
    {
        return $this->ticksDefenseAllied;
    }

    /**
     * @param int $ticksDefenseAllied
     *
     * @return void
     */
    public function setTicksDefenseAllied(int $ticksDefenseAllied): void
    {
        $this->ticksDefenseAllied = $ticksDefenseAllied;
    }

    /**
     * @return int
     */
    public function getMaxPrivateGalaxyPlayers(): int
    {
        return $this->maxPrivateGalaxyPlayers;
    }

    /**
     * @param int $maxPrivateGalaxyPlayers
     *
     * @return void
     */
    public function setMaxPrivateGalaxyPlayers(int $maxPrivateGalaxyPlayers): void
    {
        $this->maxPrivateGalaxyPlayers = $maxPrivateGalaxyPlayers;
    }

    /**
     * @return int
     */
    public function getMaxPublicGalaxyPlayers(): int
    {
        return $this->maxPublicGalaxyPlayers;
    }

    /**
     * @param int $maxPublicGalaxyPlayers
     *
     * @return void
     */
    public function setMaxPublicGalaxyPlayers(int $maxPublicGalaxyPlayers): void
    {
        $this->maxPublicGalaxyPlayers = $maxPublicGalaxyPlayers;
    }

    /**
     * @return int
     */
    public function getExtractorMetalIncome(): int
    {
        return $this->extractorMetalIncome;
    }

    /**
     * @param int $extractorMetalIncome
     *
     * @return void
     */
    public function setExtractorMetalIncome(int $extractorMetalIncome): void
    {
        $this->extractorMetalIncome = $extractorMetalIncome;
    }

    /**
     * @return int
     */
    public function getExtractorCrystalIncome(): int
    {
        return $this->extractorCrystalIncome;
    }

    /**
     * @param int $extractorCrystalIncome
     *
     * @return void
     */
    public function setExtractorCrystalIncome(int $extractorCrystalIncome): void
    {
        $this->extractorCrystalIncome = $extractorCrystalIncome;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return void
     */
    public function activate(): void
    {
        $this->isActive = true;
    }

    /**
     * @return void
     */
    public function deactivate(): void
    {
        $this->isActive = false;
    }

    /**
     * @param string $name
     *
     * @return \GC\Faction\Model\Faction
     */
    public function createFaction(string $name): Faction
    {
        $faction = new Faction($name, $this);
        $this->factions->add($faction);

        return $faction;
    }

    /**
     * @return \GC\Faction\Model\Faction[]
     */
    protected function getFactions(): array
    {
        return $this->factions->getValues();
    }

    /**
     * @param string $name
     * @param \GC\Faction\Model\Faction $faction
     *
     * @return \GC\Unit\Model\Unit
     */
    public function createUnit(string $name, Faction $faction): Unit
    {
        $unit = new Unit($name, $faction);
        $this->units->add($unit);

        return $unit;
    }

    /**
     * @return \GC\Unit\Model\Unit[]
     */
    protected function getUnits(): array
    {
        return $this->units->getValues();
    }

    /**
     * @param string $name
     * @param \GC\Faction\Model\Faction $faction
     *
     * @return \GC\Technology\Model\Technology
     */
    public function createTechnology(string $name, Faction $faction): Technology
    {
        $technology = new Technology($name, $faction);
        $this->technologies->add($technology);

        return $technology;
    }

    /**
     * @return \GC\Technology\Model\Technology[]
     */
    protected function getTechnologies(): array
    {
        return $this->technologies->getValues();
    }

    /**
     * @return \GC\Galaxy\Model\Galaxy
     */
    public function createPublicGalaxy(): Galaxy
    {
        $galaxy = new Galaxy($this);
        $this->galaxies->add($galaxy);

        return $galaxy;
    }

    /**
     * @return \GC\Galaxy\Model\Galaxy
     */
    public function createPrivateGalaxy(): Galaxy
    {
        $galaxy = new Galaxy($this, true);
        $this->galaxies->add($galaxy);

        return $galaxy;
    }

    /**
     * @return \GC\Galaxy\Model\Galaxy[]
     */
    protected function getGalaxies(): array
    {
        return $this->galaxies->getValues();
    }

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Faction\Model\Faction $faction
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return \GC\Player\Model\Player
     */
    public function createPlayer(User $user, Faction $faction, Galaxy $galaxy): Player
    {
        $player = new Player($user, $faction, $galaxy);
        $this->players->add($player);

        return $player;
    }

    /**
     * @return \GC\Player\Model\Player[]
     */
    protected function getPlayers(): array
    {
        return $this->players->getValues();
    }

    /**
     * @param string $name
     * @param string $code
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return \GC\Alliance\Model\Alliance
     */
    public function createAlliance(string $name, string $code, Galaxy $galaxy): Alliance
    {
        $alliance = new Alliance($name, $code, $this);
        $this->alliances->add($alliance);

        $commander = $galaxy->getCommander();
        if ($commander !== null) {
            $commander->grantAdmiralRole();
        }

        return $alliance;
    }

    /**
     * @return \GC\Alliance\Model\Alliance[]
     */
    protected function getAlliances(): array
    {
        return $this->alliances->getValues();
    }

    /**
     * @return \GC\Galaxy\Model\Galaxy|null
     */
    public function getRandomPublicGalaxyWithFreeSpace(): ?Galaxy
    {
        $galaxies = $this->getGalaxies();

        \shuffle($galaxies);

        foreach ($galaxies as $galaxy) {
            if ($galaxy->isPublicGalaxy() && $galaxy->hasSpaceForNewPlayer()) {
                return $galaxy;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    public function getNextFreeGalaxyNumber(): int
    {
        $freeGalaxyNumber = 1;
        foreach ($this->galaxies as $index => $galaxy) {
            if ($galaxy->getNumber() > $freeGalaxyNumber) {
                return $freeGalaxyNumber;
            }

            $freeGalaxyNumber++;
        }

        return $freeGalaxyNumber;
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function isTickCalculationNeeded(): bool
    {
        $lastTick = $this->lastTickAt ?? $this->ticksStartingAt;

        $interval = new DateInterval('PT' . $this->tickInterval . 'M');
        $nextTick = clone $lastTick;
        $nextTick->add($interval);

        if ($nextTick > new DateTime()) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function tick(): void
    {
        $this->lastTickAt = new DateTime();
        $this->tickCurrent = $this->tickCurrent + 1;

        foreach ($this->getPlayers() as $player) {
            $player->finishTechnologyConstructions();
            $player->finishUnitConstructions();
            $player->increaseResourceIncome();
            $player->calculatePoints();
        }

        foreach ($this->getGalaxies() as $galaxy) {
            $galaxy->finishTechnologyConstructions();
            $galaxy->calculateExtractorPoints();
            $galaxy->calculateAveragePoints();
        }

        foreach ($this->getAlliantces() as $alliance) {
            $alliance->calculateExtractorPoints();
            $alliance->calculateAveragePoints();
        }

        $this->generatePlayerRanking();
    }

    /**
     * @return void
     */
    public function generatePlayerRanking(): void
    {
        $rankedPlayers = $this->getPlayers();
        usort($rankedPlayers, function(Player $playerFirst, Player $playerSecond) {
            if ($playerFirst->getPoints() == $playerSecond->getPoints()) {
                return 0;
            }

            if ($playerFirst->getPoints() < $playerSecond->getPoints()) {
                return 1;
            }

            return -1;
        });

        foreach ($rankedPlayers as $index => $rankedPlayer) {
            $rankedPlayer->setRankingPosition(($index + 1));
        }
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function isAllianceAndGalaxyRankingGenerationNeeded(): bool
    {
        $lastRankingAt = $this->lastRankingAt ?? $this->ticksStartingAt;

        $interval = new DateInterval('PT' . $this->rankingInterval . 'H');
        $nextRankingAt = clone $lastRankingAt;
        $nextRankingAt->add($interval);

        if ($nextRankingAt > new DateTime()) {
            return false;
        }

        return true;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function generateAllianceAndGalaxyRanking(): void
    {
        $this->lastRankingAt = new DateTime();

        $this->generateGalaxyRanking();
        $this->generateAllianceRanking();
    }

    /**
     * @return void
     */
    private function generateGalaxyRanking(): void
    {
        $rankedGalaxies = $this->getGalaxies();
        usort($rankedGalaxies, function(Galaxy $galaxyFirst, Galaxy $galaxySecond) {
            if ($galaxyFirst->getExtractorPoints() == $galaxySecond->getExtractorPoints()) {
                return 0;
            }

            if ($galaxyFirst->getExtractorPoints() < $galaxySecond->getExtractorPoints()) {
                return 1;
            }

            return -1;
        });

        foreach ($rankedGalaxies as $index => $rankedGalaxy) {
            $rankedGalaxy->setRankingPosition(($index + 1));
        }
    }

    /**
     * @return void
     */
    private function generateAllianceRanking(): void
    {
        $rankedAlliances = $this->getAlliances();
        usort($rankedAlliances, function(Alliance $allianceFirst, Alliance $allianceSecond) {
            if ($allianceFirst->getExtractorPoints() == $allianceSecond->getExtractorPoints()) {
                return 0;
            }

            if ($allianceFirst->getExtractorPoints() < $allianceSecond->getExtractorPoints()) {
                return 1;
            }

            return -1;
        });

        foreach ($rankedAlliances as $index => $rankedAlliance) {
            $rankedAlliance->setRankingPosition(($index + 1));
        }
    }
}