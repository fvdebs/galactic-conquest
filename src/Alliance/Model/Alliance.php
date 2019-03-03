<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @OneToMany(targetEntity="\GC\Alliance\Model\AllianceTechnology", mappedBy="technology", cascade={"all"}, orphanRemoval=true)
     */
    private $allianceTechnologies;

    /**
     * @param string $name
     * @param string $code
     * @param \GC\Universe\Model\Universe $universe
     */
    public function __construct(string $name, string $code, Universe $universe)
    {
        $this->galaxies = new ArrayCollection();
        $this->allianceTechnologies = new ArrayCollection();

        $this->name = $name;
        $this->code = $code;
        $this->universe = $universe;
        $this->description = '';
        $this->metal = 0;
        $this->crystal = 0;
        $this->taxMetal = 0;
        $this->taxCrystal = 0;
        $this->scanRelays = 0;
        $this->extractorPoints = 0;
        $this->rankingPosition = 0;
        $this->averagePoints = 0;
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
        return $this->galaxies;
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
     * @return void
     */
    public function calculateExtractorPoints(): void
    {
        $newExtractorPoints = 0;
        foreach ($this->getGalaxies() as $galaxy) {
            $newExtractorPoints += $galaxy->getExtractorPoints();
        }

        $this->extractorPoints = $this->extractorPoints + $newExtractorPoints;
    }

    /**
     * @return void
     */
    public function calculateAveragePoints(): void
    {
        $averagePoints = 0;
        foreach ($this->getGalaxies() as $galaxy) {
            $averagePoints += $galaxy->getAveragePoints();
        }

        $galaxyCount = \count($this->getGalaxies());
        $calculation = 0;
        if ($galaxyCount > 0) {
            $calculation = $averagePoints / $galaxyCount;
        }

        $this->averagePoints = (int) \round($calculation, 0, PHP_ROUND_HALF_UP);;
    }
}