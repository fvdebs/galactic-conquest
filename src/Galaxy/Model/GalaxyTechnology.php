<?php

declare(strict_types=1);

namespace GC\Galaxy\Model;

use GC\Technology\Model\Technology;

/**
 * @Table(name="galaxy_technology")
 * @Entity
 */
class GalaxyTechnology
{
    /**
     * @var int
     *
     * @Column(name="galaxy_technology_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $galaxyTechnologyId;

    /**
     * @var \GC\Galaxy\Model\Galaxy
     *
     * @ManyToOne(targetEntity="GC\Galaxy\Model\Galaxy")
     * @JoinColumn(name="galaxy_id", referencedColumnName="galaxy_id", nullable=false)
     */
    private $galaxy;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="\GC\Technology\Model\Technology")
     * @JoinColumn(name="technology_id", referencedColumnName="technology_id", nullable=false)
     */
    private $technology;

    /**
     * @var int
     *
     * @Column(name="ticks_left", type="integer", nullable=false)
     */
    private $ticksLeft;

    /**
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     * @param \GC\Technology\Model\Technology $technology
     * @param int $ticksLeft
     */
    public function __construct(Galaxy $galaxy, Technology $technology, int $ticksLeft)
    {
        $this->galaxy = $galaxy;
        $this->technology = $technology;
        $this->ticksLeft = $ticksLeft;
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

    /**
     * @return int
     */
    public function getTicksLeft(): int
    {
        return $this->ticksLeft;
    }
}