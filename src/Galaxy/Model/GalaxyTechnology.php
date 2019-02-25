<?php

declare(strict_types=1);

namespace GC\Galaxy\Model;

use GC\Technology\Model\Technology;

/**
 * @Table(name="galaxy_technology", indexes={@Index(name="fk-galaxy_technology-galaxy_id", columns={"galaxy_id"}), @Index(name="fk-galaxy_technology-technology_id", columns={"technology_id"})})
 * @Entity
 */
final class GalaxyTechnology
{
    /**
     * @var int
     *
     * @Column(name="galaxy_technology_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $galaxyTechnologyId;

    /**
     * @var \GC\Galaxy\Model\Galaxy
     *
     * @ManyToOne(targetEntity="\GC\Galaxy\Model\Galaxy")
     * @JoinColumns({
     *   @JoinColumn(name="galaxy_id", referencedColumnName="galaxy_id")
     * })
     */
    private $galaxy;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="\GC\Technology\Model\Technology")
     * @JoinColumns({
     *   @JoinColumn(name="technology_id", referencedColumnName="technology_id")
     * })
     */
    private $technology;

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     * @param \GC\Technology\Model\Technology $technology
     */
    public function __construct(Galaxy $galaxy, Technology $technology)
    {
        $this->galaxy = $galaxy;
        $this->technology = $technology;
    }

    /**
     * @return int
     */
    public function getGalaxyTechnologyId(): int
    {
        return $this->galaxyTechnologyId;
    }

    /**
     * @return \GC\Galaxy\Model\Galaxy
     */
    public function getGalaxy(): Galaxy
    {
        return $this->galaxy;
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    public function getTechnology(): Technology
    {
        return $this->technology;
    }
}