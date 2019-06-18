<?php

declare(strict_types=1);

namespace GC\Universe\Model;

use DateTime;
use DateInterval;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use GC\Alliance\Model\Alliance;
use GC\Faction\Model\Faction;
use GC\Galaxy\Model\Galaxy;
use GC\Player\Model\Player;
use GC\Technology\Model\Technology;
use GC\Unit\Model\Unit;

use function array_reverse;
use function floor;
use function shuffle;
use function strtolower;

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
     * @Column(name="max_ticks_mission_offensive", type="integer", nullable=false)
     */
    private $maxTicksMissionOffensive;

    /**
     * @var int
     *
     * @Column(name="max_ticks_mission_defensive", type="integer", nullable=false)
     */
    private $maxTicksMissionDefensive;

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
     * @Column(name="max_alliance_galaxies", type="integer", nullable=false)
     */
    private $maxAllianceGalaxies;

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
     * @var bool
     *
     * @Column(name="is_closed", type="boolean", nullable=false)
     */
    private $isClosed;

    /**
     * @var DateTime
     *
     * @Column(name="closed_at", type="datetime", nullable=true)
     */
    private $closedAt;

    /**
     * @var bool
     *
     * @Column(name="is_registration_allowed", type="boolean", nullable=false)
     */
    private $isRegistrationAllowed;

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
        $this->maxTicksMissionOffensive = 5;
        $this->maxTicksMissionDefensive = 20;
        $this->maxPrivateGalaxyPlayers = 8;
        $this->maxPublicGalaxyPlayers = 12;
        $this->maxAllianceGalaxies = 3;
        $this->extractorMetalIncome = 50;
        $this->extractorCrystalIncome = 50;
        $this->extractorStartCost = 65;
        $this->extractorPoints = 15000;
        $this->resourcePointsDivider = 10;
        $this->isActive = false;
        $this->isClosed = false;
        $this->rankingInterval = 12;
        $this->isRegistrationAllowed = true;
    }

    /**
     * @return int
     */
    public function getUniverseId(): int
    {
        return $this->universeId;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->isClosed;
    }

    /**
     * @param bool $isClosed
     *
     * @return void
     */
    public function setIsClosed(bool $isClosed): void
    {
        $this->isClosed = $isClosed;
    }

    /**
     * @return \DateTime|null
     */
    public function getClosedAt(): DateTime
    {
        return $this->closedAt;
    }

    /**
     * @param \DateTime $closedAt
     *
     * @return void
     */
    public function setClosedAt(?DateTime $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    /**
     * @return bool
     */
    public function isRegistrationAllowed(): bool
    {
        return $this->isRegistrationAllowed;
    }

    /**
     * @param bool $isRegistrationAllowed
     *
     * @return void
     */
    public function setIsRegistrationAllowed(bool $isRegistrationAllowed): void
    {
        $this->isRegistrationAllowed = $isRegistrationAllowed;
    }

    /**
     * @return int
     */
    public function getMaxAllianceGalaxies(): int
    {
        return $this->maxAllianceGalaxies;
    }

    /**
     * @param int $maxAllianceGalaxies
     */
    public function setMaxAllianceGalaxies(int $maxAllianceGalaxies): void
    {
        $this->maxAllianceGalaxies = $maxAllianceGalaxies;
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
     * @return string
     */
    public function getRouteName(): string
    {
        return strtolower($this->name);
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
    public function getMaxTicksMissionOffensive(): int
    {
        return $this->maxTicksMissionOffensive;
    }

    /**
     * @param int $maxTicksMissionOffensive
     *
     * @return void
     */
    public function setMaxTicksMissionOffensive(int $maxTicksMissionOffensive): void
    {
        $this->maxTicksMissionOffensive = $maxTicksMissionOffensive;
    }

    /**
     * @return int
     */
    public function getMaxTicksMissionDefensive(): int
    {
        return $this->maxTicksMissionDefensive;
    }

    /**
     * @param int $maxTicksMissionDefensive
     *
     * @return void
     */
    public function setMaxTicksMissionDefensive(int $maxTicksMissionDefensive): void
    {
        $this->maxTicksMissionDefensive = $maxTicksMissionDefensive;
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
     * @param bool $isDefault
     *
     * @return \GC\Faction\Model\Faction
     */
    public function createFaction(string $name, bool $isDefault = false): Faction
    {
        $faction = new Faction($name, $this, $isDefault);
        $this->factions->add($faction);

        return $faction;
    }

    /**
     * @return \GC\Faction\Model\Faction[]
     */
    public function getFactions(): array
    {
        return $this->factions->getValues();
    }

    /**
     * @return \GC\Faction\Model\Faction|null
     */
    public function getDefaultFaction(): ?Faction
    {
        foreach ($this->getFactions() as $faction) {
            if ($faction->isDefault()) {
                return $faction;
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasDefaultFaction(): bool
    {
        foreach ($this->getFactions() as $faction) {
            if ($faction->isDefault()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     * @param \GC\Faction\Model\Faction $faction
     * @param string $group
     *
     * @return \GC\Unit\Model\Unit
     */
    public function createUnit(string $name, Faction $faction, string $group): Unit
    {
        $unit = new Unit($name, $faction, $group);
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
    public function getGalaxies(): array
    {
        return $this->galaxies->getValues();
    }

    /**
     * @return \GC\Player\Model\Player[]
     */
    public function getPlayers(): array
    {
        $players = [];
        foreach ($this->getGalaxies() as $galaxy) {
            foreach ($galaxy->getPlayers() as $player) {
                $players[] = $player;
            }
        }

        return $players;
    }

    /**
     * @param \GC\Alliance\Model\Alliance $alliance
     *
     * @return void
     */
    public function addAlliance(Alliance $alliance): void
    {
        $this->alliances->add($alliance);
    }

    /**
     * @return \GC\Alliance\Model\Alliance[]
     */
    public function getAlliances(): array
    {
        return $this->alliances->getValues();
    }

    /**
     * @return \GC\Galaxy\Model\Galaxy|null
     */
    public function getRandomPublicGalaxyWithFreeSpace(): ?Galaxy
    {
        $galaxies = $this->getGalaxies();

        shuffle($galaxies);

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
     * @return int
     */
    public function calculateTicksPerDay(): int
    {
        return (int) floor(1440 / $this->getTickInterval());
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

        return !($nextTick > new DateTime());
    }

    /**
     *
     * @return array
     */
    protected function createCombatTransfers(): array
    {
        return [];
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function tick(): void
    {
        $this->lastTickAt = new DateTime();
        ++$this->tickCurrent;

        $players = $this->getPlayers();
        $galaxies = $this->getGalaxies();
        $alliances = $this->getAlliances();

        foreach ($players as $player) {
            $player->increaseResourceIncomePerTick();
            $player->finishPlayerTechnologyConstructions();
            $player->finishUnitConstructions();
            $player->movePlayerFleetsForward();
        }

        foreach ($this->createCombatTransfers() as $combat) {
            // $combatReport = $combatService->calculate($combat)
            // combatReport Add
        }

        foreach ($players as $player) {
            $player->clearOrRecallPlayerFleets();
            $player->calculatePoints();

        }

        foreach ($galaxies as $galaxy) {
            $galaxy->finishTechnologyConstructions();
            $galaxy->increaseExtractorPointsPerTick();
            $galaxy->increaseResourceIncomePerTick();
            $galaxy->calculateAveragePlayerPoints();
        }

        foreach ($alliances as $alliance) {
            $alliance->finishTechnologyConstructions();
            $alliance->increaseExtractorPointsPerTick();
            $alliance->increaseResourceIncomePerTick();
            $alliance->calculateAverageGalaxyPoints();
        }

        $this->generatePlayerRanking();
    }

    /**
     * @return void
     */
    public function generatePlayerRanking(): void
    {
        $players = $this->getPlayers();
        usort($players, function (Player $playerFirst, Player $playerSecond) {
            return $playerFirst->getPoints() <=> $playerSecond->getPoints();
        });

        $players = array_reverse($players);
        foreach ($players as $index => $player) {
            $rankingPosition = $index + 1;
            $player->setRankingPosition($rankingPosition);
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

        return !($nextRankingAt > new DateTime());
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
        usort($rankedGalaxies, function (Galaxy $galaxyFirst, Galaxy $galaxySecond) {
            return $galaxyFirst->getExtractorPoints() <=> $galaxySecond->getExtractorPoints();
        });

        $rankedGalaxies = array_reverse($rankedGalaxies);
        foreach ($rankedGalaxies as $index => $rankedGalaxy) {
            $rankingPosition = $index + 1;
            $rankedGalaxy->setRankingPosition($rankingPosition);
        }
    }

    /**
     * @return void
     */
    private function generateAllianceRanking(): void
    {
        $rankedAlliances = $this->getAlliances();
        usort($rankedAlliances, function (Alliance $allianceFirst, Alliance $allianceSecond) {
            return $allianceFirst->getExtractorPoints() <=> $allianceSecond->getExtractorPoints();
        });

        $rankedAlliances = array_reverse($rankedAlliances);
        foreach ($rankedAlliances as $index => $rankedAlliance) {
            $rankingPosition = $index + 1;
            $rankedAlliance->setRankingPosition($rankingPosition);
        }
    }

    /**
     * @return DateTimeInterface
     */
    public function getNextTick(): DateTimeInterface
    {
        $lastTick = $this->getLastTickAt() ?? $this->getTicksStartingAt();

        $newDate = clone $lastTick;
        $newDate->modify("+ {$this->getTickInterval()} minutes");

        return $newDate;
    }
}
