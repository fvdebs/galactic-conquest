<?php

declare(strict_types=1);

namespace GC\Unit\Model;

use GC\Technology\Model\Technology;

/**
 * @Table(name="unit_technology_condition", indexes={@Index(name="fk-unit_technology_condition-unit_id", columns={"unit_id"}), @Index(name="fk-unit_technology_condition-technology_id", columns={"technology_id"})})
 * @Entity
 */
final class UnitTechnologyCondition
{
    /**
     * @var int
     *
     * @Column(name="unit_technology_condition_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $unitTechnologyConditionId;

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
     * @var \GC\Unit\Model\Unit
     *
     * @ManyToOne(targetEntity="\GC\Unit\Model\Unit")
     * @JoinColumns({
     *   @JoinColumn(name="unit_id", referencedColumnName="unit_id")
     * })
     */
    private $unit;

    /**
     * @param \GC\Unit\Model\Unit $unit
     * @param \GC\Technology\Model\Technology $technology
     */
    public function __construct(Unit $unit, Technology $technology)
    {
        $this->unit = $unit;
        $this->technology = $technology;
    }

    /**
     * @return int
     */
    public function getUnitTechnologyConditionId(): int
    {
        return $this->unitTechnologyConditionId;
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    public function getTechnology(): Technology
    {
        return $this->technology;
    }

    /**
     * @return \GC\Unit\Model\Unit
     */
    public function getUnit():Unit
    {
        return $this->unit;
    }
}