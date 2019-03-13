<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Galaxy\Model\Galaxy;
use GC\Player\Model\Player;
use GC\Technology\Model\Technology;
use GC\Universe\Model\Universe;

/**
 * @Table(name="alliance")
 * @Entity(repositoryClass="GC\Alliance\Model\AllianceRepository")
 */
class Alliance
{
    /**
     * @var int
     *
     * @Column(name="alliance_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $allianceId;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="code", type="string", length=150, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

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
     * @Column(name="tax_metal", type="integer", nullable=false)
     */
    private $taxMetal;

    /**
     * @var int
     *
     * @Column(name="tax_crystal", type="integer", nullable=false)
     */
    private $taxCrystal;

    /**
     * @var int
     *
     * @Column(name="scan_relays", type="integer", nullable=false)
     */
    private $scanRelays;

    /**
     * @var int
     *
     * @Column(name="extractor_points", type="integer", nullable=false)
     */
    private $extractorPoints;

    /**
     * @var int
     *
     * @Column(name="ranking_position", type="integer", nullable=false)
     */
    private $rankingPosition;

    /**
     * @var int
     *
     * @Column(name="average_points", type="integer", nullable=false)
     */
    private $averagePoints;

    /**
     * @var \GC\Universe\Model\Universe
     *
     * @ManyToOne(targetEntity="GC\Universe\Model\Universe", inversedBy="alliances")
     * @JoinColumn(name="universe_id", referencedColumnName="universe_id", nullable=false)
     */
    private $universe;

    /**
     * @var \GC\Galaxy\Model\Galaxy[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Galaxy\Model\Galaxy", mappedBy="alliance", fetch="EXTRA_LAZY", cascade={"all"}, orphanRemoval=true)
     */
    private $galaxies;

    /**
     * @var \GC\Alliance\Model\AllianceTechnology[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Alliance\Model\AllianceTechnology", mappedBy="alliance", cascade={"all"}, orphanRemoval=true)
     */
    private $allianceTechnologies;

    /**
     * @var \GC\Alliance\Model\AllianceApplication[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Alliance\Model\AllianceApplication", mappedBy="alliance", cascade={"all"}, orphanRemoval=true)
     */
    private $allianceApplications;

    /**
     * @param string $name
     * @param string $code
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     */
    public function __construct(string $name, string $code, Galaxy $galaxy)
    {
        $this->galaxies = new ArrayCollection();
        $this->allianceTechnologies = new ArrayCollection();
        $this->allianceApplications = new ArrayCollection();

        $this->name = $name;
        $this->code = $code;
        $this->universe = $galaxy->getUniverse();
        $this->description = '';
        $this->metal = 0;
        $this->crystal = 0;
        $this->taxMetal = 0;
        $this->taxCrystal = 0;
        $this->scanRelays = 0;
        $this->extractorPoints = 0;
        $this->rankingPosition = 0;
        $this->averagePoints = 0;

        $this->universe->addAlliance($this);
        $this->galaxies->add($galaxy);

        if ($galaxy->getCommander() !== null) {
            $galaxy->getCommander()->grantAdmiralRole();
        }
    }

    /**
     * @return int
     */
    public function getAllianceId(): int
    {
        return $this->allianceId;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\GC\Galaxy\Model\Galaxy[]
     */
    public function getGalaxies()
    {
        return $this->galaxies->getValues();
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return void
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
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
     * @return int
     */
    public function getMetal(): int
    {
        return $this->metal;
    }

    /**
     * @param int $metal
     *
     * @return void
     */
    public function setMetal(int $metal): void
    {
        $this->metal = $metal;
    }

    /**
     * @return int
     */
    public function getCrystal(): int
    {
        return $this->crystal;
    }

    /**
     * @param int $crystal
     *
     * @return void
     */
    public function setCrystal(int $crystal): void
    {
        $this->crystal = $crystal;
    }

    /**
     * @return int
     */
    public function getTaxMetal(): int
    {
        return $this->taxMetal;
    }

    /**
     * @param int $taxMetal
     *
     * @return void
     */
    public function setTaxMetal(int $taxMetal): void
    {
        $this->taxMetal = $taxMetal;
    }

    /**
     * @return int
     */
    public function getTaxCrystal(): int
    {
        return $this->taxCrystal;
    }

    /**
     * @param int $taxCrystal
     *
     * @return void
     */
    public function setTaxCrystal(int $taxCrystal): void
    {
        $this->taxCrystal = $taxCrystal;
    }

    /**
     * @return int
     */
    public function getScanRelays(): int
    {
        return $this->scanRelays;
    }

    /**
     * @param int $scanRelays
     *
     * @return void
     */
    public function setScanRelays(int $scanRelays): void
    {
        $this->scanRelays = $scanRelays;
    }

    /**
     * @return int
     */
    public function getExtractorPoints(): int
    {
        return $this->extractorPoints;
    }

    /**
     * @param int $extractorPoints
     *
     * @return void
     */
    public function setExtractorPoints(int $extractorPoints): void
    {
        $this->extractorPoints = $extractorPoints;
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
     * @return int
     */
    public function getAveragePoints(): int
    {
        return $this->averagePoints;
    }

    /**
     * @param int $averagePoints
     *
     * @return void
     */
    public function setAveragePoints(int $averagePoints): void
    {
        $this->averagePoints = $averagePoints;
    }

    /**
     * @return \GC\Universe\Model\Universe
     */
    public function getUniverse(): Universe
    {
        return $this->universe;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return void
     */
    public function setUniverse(Universe $universe): void
    {
        $this->universe = $universe;
    }

    /**
     * @return \GC\Player\Model\Player[]
     */
    public function getPlayers(): array
    {
        $players = [];
        foreach($this->getGalaxies() as $galaxy) {
            foreach ($galaxy->getPlayers() as $player) {
                $players[] = $player;
            }
        }

        return $players;
    }

    /**
     * @return int
     */
    public function getPlayerCount(): int
    {
        $count = 0;
        foreach($this->getGalaxies() as $galaxy) {
            $count += $galaxy->getPlayerCount();
        }

        return $count;
    }

    /**
     * @return int
     */
    public function calculateExtractorPointsPerTick(): int
    {
        $extractorPoints = 0;
        foreach ($this->getGalaxies() as $galaxy) {
            $extractorPoints += $galaxy->getExtractorPoints();
        }

        return $extractorPoints;
    }

    /**
     * @return void
     */
    public function increaseExtractorPointsPerTick(): void
    {
        $this->extractorPoints += $this->calculateExtractorPointsPerTick();
    }

    /**
     * @return void
     */
    public function calculateAverageGalaxyPoints(): void
    {
        $averagePoints = 0;
        foreach ($this->getGalaxies() as $galaxy) {
            $averagePoints += $galaxy->getAveragePoints();
        }

        $calculation = $averagePoints / \count($this->getGalaxies());

        $this->averagePoints = (int) \round($calculation);
    }

    /**
     * @return \GC\Player\Model\Player|null
     */
    public function getAdmiral(): ?Player
    {
        foreach ($this->getPlayers() as $player) {
            if ($player->isAdmiral()) {
                return $player;
            }
        }

        return null;
    }

    /**
     * @param int $metal
     * @param int $crystal
     *
     * @return bool
     */
    public function hasResources(int $metal, int $crystal): bool
    {
        return $this->metal >= $metal && $this->crystal >= $crystal;
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
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return int
     */
    public function calculateMetalTaxFor(Galaxy $galaxy): int
    {
        $calculation = ($galaxy->calculateMetalIncomePerTick() / 100) * $this->taxMetal;

        return (int) \round($calculation);
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return int
     */
    public function calculateCrystalTaxFor(Galaxy $galaxy): int
    {
        $calculation = ($galaxy->calculateCrystalIncomePerTick() / 100) * $this->taxCrystal;

        return (int) \round($calculation);
    }

    /**
     * @return int
     */
    public function calculateMetalIncomePerTick(): int
    {
        $income = 0;
        foreach ($this->getGalaxies() as $galaxy) {
            $income += $this->calculateMetalTaxFor($galaxy);
        }

        return $income;
    }

    /**
     * @return int
     */
    public function calculateCrystalIncomePerTick(): int
    {
        $income = 0;
        foreach ($this->getGalaxies() as $galaxy) {
            $income += $this->calculateCrystalTaxFor($galaxy);
        }

        return $income;
    }

    /**
     * @return void
     */
    public function increaseResourceIncomePerTick(): void
    {
        $this->increaseMetal($this->calculateMetalIncomePerTick());
        $this->increaseCrystal($this->calculateCrystalIncomePerTick());
    }

    /**
     * @return \GC\Alliance\Model\AllianceTechnology[]
     */
    public function getAllianceTechnologies(): array
    {
        return $this->allianceTechnologies->getValues();
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return \GC\Alliance\Model\AllianceTechnology
     */
    public function buildTechnology(Technology $technology): AllianceTechnology
    {
        $allianceTechnology = new AllianceTechnology($this, $technology);
        $this->allianceTechnologies->add($allianceTechnology);

        $this->decreaseMetal($technology->getMetalCost());
        $this->decreaseCrystal($technology->getCrystalCost());

        return $allianceTechnology;
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return bool
     */
    public function hasTechnology(Technology $technology): bool
    {
        foreach ($this->getAllianceTechnologies() as $allianceTechnology) {
            if ($allianceTechnology->isCompleted()
                && $allianceTechnology->getTechnology()->getTechnologyId() === $technology->getTechnologyId()) {

                return true;
            }
        }

        return false;
    }

    /**
     * @param string $featureKey
     *
     * @return bool
     */
    public function hasTechnologyByFeatureKey(string $featureKey): bool
    {
        foreach ($this->getAllianceTechnologies() as $allianceTechnology) {
            if ($allianceTechnology->isCompleted()
                && $allianceTechnology->getTechnology()->getFeatureKey() === $featureKey) {

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
            if (!$this->hasTechnology($technologyCondition->getTargetTechnology())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \GC\Technology\Model\Technology $technology
     *
     * @return bool
     */
    public function isTechnologyInConstruction(Technology $technology): bool
    {
        foreach ($this->getAllianceTechnologies() as $allianceTechnology) {
            if ($allianceTechnology->getTechnology()->getTechnologyId() === $technology->getTechnologyId()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    public function finishTechnologyConstructions(): void
    {
        foreach ($this->getAllianceTechnologies() as $allianceTechnology) {
            if ($allianceTechnology->getTicksLeft() > 0) {
                $allianceTechnology->decreaseTicksLeft();
            }
        }
    }

    /**
     * @return \GC\Galaxy\Model\GalaxyTechnology[]
     */
    public function getAllianceTechnologiesCompleted(): array
    {
        $allianceTechnologies = [];
        foreach ($this->getAllianceTechnologies() as $allianceTechnology) {
            if ($allianceTechnology->isCompleted()) {
                $allianceTechnologies[] = $allianceTechnology;
            }
        }

        return $allianceTechnologies;
    }

    /**
     * @return \GC\Galaxy\Model\GalaxyTechnology[]
     */
    public function getAllianceTechnologiesInConstruction(): array
    {
        $allianceTechnologies = [];
        foreach ($this->getAllianceTechnologies() as $allianceTechnologies) {
            if ($allianceTechnologies->isInConstruction()) {
                $allianceTechnologies[] = $allianceTechnologies;
            }
        }

        return $allianceTechnologies;
    }

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     *
     * @return \GC\Alliance\Model\AllianceApplication
     */
    public function createAllianceApplicationFor(Galaxy $galaxy): AllianceApplication
    {
        $allianceApplication = new AllianceApplication($this, $galaxy);
        $this->allianceApplications->add($allianceApplication);

        return $allianceApplication;
    }

    /**
     * @param \GC\Alliance\Model\AllianceApplication $allianceApplication
     *
     * @return void
     */
    public function acceptAllianceApplication(AllianceApplication $allianceApplication): void
    {
        $allianceApplication->getGalaxy()->setAlliance($this);
        $this->allianceApplications->removeElement($allianceApplication);
    }

    /**
     * @param \GC\Alliance\Model\AllianceApplication $allianceApplication
     *
     * @return void
     */
    public function denyAllianceApplication(AllianceApplication $allianceApplication): void
    {
        $this->allianceApplications->removeElement($allianceApplication);
    }

    /**
     * @param \GC\Player\Model\Player $player
     *
     * @return bool
     */
    public function isMember(Player $player): bool
    {
        foreach ($this->getPlayers() as $member) {
            if ($player->getPlayerId() === $member->getPlayerId()) {
                return true;
            }
        }

        return false;
    }
}