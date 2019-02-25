<?php

declare(strict_types=1);

namespace GC\Technology\Model;

/**
 * Technology
 *
 * @Table(name="technology", indexes={@Index(name="fk-technology-faction_id", columns={"faction_id"})})
 * @Entity
 */
class Technology
{
    /**
     * @var int
     *
     * @Column(name="technology_id", type="bigint", nullable=false)
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
     * @var string|null
     *
     * @Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @Column(name="feature_key", type="string", length=150, nullable=true)
     */
    private $featureKey;

    /**
     * @var bool|null
     *
     * @Column(name="is_alliance_technology", type="boolean", nullable=true)
     */
    private $isAllianceTechnology;

    /**
     * @var bool|null
     *
     * @Column(name="is_player_technology", type="boolean", nullable=true)
     */
    private $isPlayerTechnology;

    /**
     * @var bool|null
     *
     * @Column(name="is_galaxy_technology", type="boolean", nullable=true)
     */
    private $isGalaxyTechnology;

    /**
     * @var int|null
     *
     * @Column(name="metal_cost", type="integer", nullable=true)
     */
    private $metalCost;

    /**
     * @var int|null
     *
     * @Column(name="crystal_cost", type="integer", nullable=true)
     */
    private $crystalCost;

    /**
     * @var int|null
     *
     * @Column(name="ticks_to_build", type="integer", nullable=true)
     */
    private $ticksToBuild;

    /**
     * @var \Faction
     *
     * @ManyToOne(targetEntity="Faction")
     * @JoinColumns({
     *   @JoinColumn(name="faction_id", referencedColumnName="faction_id")
     * })
     */
    private $faction;


}
