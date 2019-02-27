<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use GC\Technology\Model\Technology;

/**
 * @Table(name="alliance_technology")
 * @Entity
 */
class AllianceTechnology
{
    /**
     * @var int
     *
     * @Column(name="alliance_technology_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $allianceTechnologyId;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="GC\Technology\Model\Technology", inversedBy="allianceTechnologies")
     * @JoinColumn(name="technology_id", referencedColumnName="technology_id", nullable=false)
     */
    private $technology;

    /**
     * @var \GC\Alliance\Model\Alliance
     *
     * @ManyToOne(targetEntity="GC\Alliance\Model\Alliance")
     * @JoinColumn(name="alliance_id", referencedColumnName="alliance_id", nullable=false)
     */
    private $alliance;

    /**
     * @var int
     *
     * @Column(name="ticks_left", type="integer", nullable=false)
     */
    private $ticksLeft;

    /**
     * @param \GC\Technology\Model\Technology $technology
     * @param \GC\Alliance\Model\Alliance $alliance
     * @param int $ticksLeft
     */
    public function __construct(Technology $technology, Alliance $alliance, int $ticksLeft)
    {
        $this->technology = $technology;
        $this->alliance = $alliance;
        $this->ticksLeft = $ticksLeft;
    }

    /**
     * @return int
     */
    public function getAllianceTechnologyId(): int
    {
        return $this->allianceTechnologyId;
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    public function getTechnology(): Technology
    {
        return $this->technology;
    }

    /**
     * @return \GC\Alliance\Model\Alliance
     */
    public function getAlliance(): Alliance
    {
        return $this->alliance;
    }

    /**
     * @return int
     */
    public function getTicksLeft(): int
    {
        return $this->ticksLeft;
    }
}