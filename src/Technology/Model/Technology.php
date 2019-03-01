<?php

declare(strict_types=1);

namespace GC\Technology\Model;

use Doctrine\Common\Collections\ArrayCollection;
use GC\Faction\Model\Faction;

/**
 * @Table(name="technology")
 * @Entity(repositoryClass="GC\Technology\Model\TechnologyRepository")
 */
final class Technology
{
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
     * @param string $name
     * @param \GC\Faction\Model\Faction $faction
     */
    public function __construct(string $name, Faction $faction)
    {
        $this->technologyConditions = new ArrayCollection();
        $this->name = $name;
        $this->faction = $faction;
        $this->description = '';
        $this->featureKey = null;
        $this->isAllianceTechnology = false;
        $this->isPlayerTechnology = true;
        $this->isGalaxyTechnology = false;
        $this->metalCost = 5000;
        $this->crystalCost = 5000;
        $this->ticksToBuild = 10;
    }

    /**
     * @return int
     */
    public function getTechnologyId(): int
    {
        return $this->technologyId;
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
}