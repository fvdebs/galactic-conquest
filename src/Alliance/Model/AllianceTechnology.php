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
     * @param \GC\Alliance\Model\Alliance $alliance
     * @param \GC\Technology\Model\Technology $technology
     */
    public function __construct(Alliance $alliance, Technology $technology)
    {
        $this->alliance = $alliance;
        $this->technology = $technology;
        $this->ticksLeft = $technology->getTicksToBuild();
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

    /**
     * @return int
     */
    public function calculateProgress(): int
    {
        if ($this->isCompleted()) {
            return 100;
        }

        $technologyBuildTicks = $this->getTechnology()->getTicksToBuild();

        $calculation = $technologyBuildTicks / ($technologyBuildTicks - $this->getTicksLeft());

        return (int) \round($calculation);
    }

    /**
     * @return void
     */
    public function decreaseTicksLeft(): void
    {
        --$this->ticksLeft;
    }

    /**
     * @return bool
     */
    public function isInConstruction(): bool
    {
        return $this->ticksLeft > 0;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->ticksLeft === 0;
    }
}