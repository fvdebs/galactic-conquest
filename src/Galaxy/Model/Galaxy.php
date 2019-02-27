<?php

declare(strict_types=1);

namespace GC\Galaxy\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Alliance\Model\Alliance;
use GC\Player\Model\Player;

/**
 * @Table(name="galaxy")
 * @Entity
 */
class Galaxy
{
    /**
     * @var int
     *
     * @Column(name="galaxy_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $galaxyId;

    /**
     * @var int
     *
     * @Column(name="number", type="integer", nullable=false)
     */
    private $number;

    /**
     * @var string|null
     *
     * @Column(name="password", type="string", length=70, nullable=true)
     */
    private $password;

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
     * @var \GC\Alliance\Model\Alliance|null
     *
     * @ManyToOne(targetEntity="\GC\Alliance\Model\Alliance")
     * @JoinColumn(name="alliance_id", referencedColumnName="alliance_id", nullable=true)
     */
    private $alliance;

    /**
     * @var \GC\Player\Model\Player|null
     *
     * @ManyToOne(targetEntity="\GC\Player\Model\Player")
     * @JoinColumn(name="commander_player_id", referencedColumnName="player_id", nullable=true)
     */
    private $commanderPlayer;

    /**
     * @var \GC\Galaxy\Model\GalaxyTechnology[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Galaxy\Model\GalaxyTechnology", mappedBy="galaxy", cascade={"all"}, orphanRemoval=true)
     */
    private $galaxyTechnologies;

    /**
     * @var \GC\Player\Model\Player[]|\Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany(targetEntity="\GC\Player\Model\Player", mappedBy="galaxy", cascade={"all"}, orphanRemoval=true)
     */
    private $players;

    /**
     * @param int $number
     */
    public function __construct(int $number)
    {
        $this->players = new ArrayCollection();
        $this->galaxyTechnologies = new ArrayCollection();
        $this->number = $number;
        $this->password = null;
        $this->metal = 0;
        $this->crystal = 0;
        $this->taxCrystal = 0;
        $this->taxMetal = 0;
        $this->extractorPoints = 0;
        $this->rankingPosition = 0;
        $this->alliance = null;
        $this->commanderPlayer = null;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|\GC\Player\Model\Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @return int
     */
    public function getGalaxyId(): int
    {
        return $this->galaxyId;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     *
     * @return void
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     *
     * @return void
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
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
     * @return \GC\Alliance\Model\Alliance|null
     */
    public function getAlliance(): ?Alliance
    {
        return $this->alliance;
    }

    /**
     * @param \GC\Alliance\Model\Alliance|null $alliance
     *
     * @return void
     */
    public function setAlliance(?Alliance $alliance): void
    {
        $this->alliance = $alliance;
    }

    /**
     * @return \GC\Player\Model\Player|null
     */
    public function getCommanderPlayer(): ?Player
    {
        return $this->commanderPlayer;
    }

    /**
     * @param \GC\Player\Model\Player|null $commanderPlayer
     *
     * @return void
     */
    public function setCommanderPlayer(?Player $commanderPlayer): void
    {
        $this->commanderPlayer = $commanderPlayer;
    }
}