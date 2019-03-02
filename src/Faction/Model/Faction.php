<?php

declare(strict_types=1);

namespace GC\Faction\Model;

use GC\Universe\Model\Universe;

/**
 * @Table(name="faction")
 * @Entity(repositoryClass="GC\Faction\Model\FactionRepository")
 */
class Faction
{
    /**
     * @var int
     *
     * @Column(name="faction_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $factionId;

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
     * @var \GC\Universe\Model\Universe
     *
     * @ManyToOne(targetEntity="GC\Universe\Model\Universe", inversedBy="factions")
     * @JoinColumn(name="universe_id", referencedColumnName="universe_id", nullable=false)
     */
    private $universe;

    /**
     * @param string $name
     * @param \GC\Universe\Model\Universe $universe
     */
    public function __construct(string $name, Universe $universe)
    {
        $this->name = $name;
        $this->universe = $universe;
        $this->description = '';
    }

    /**
     * @return int
     */
    public function getFactionId(): int
    {
        return $this->factionId;
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
     * @return \GC\Universe\Model\Universe
     */
    public function getUniverse(): Universe
    {
        return $this->universe;
    }
}