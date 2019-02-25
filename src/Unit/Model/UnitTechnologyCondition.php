<?php

declare(strict_types=1);

namespace GC\Unit\Model;

/**
 * @Table(name="unit_technology_condition", indexes={@Index(name="fk-unit_technology_condition-unit_id", columns={"unit_id"}), @Index(name="fk-unit_technology_condition-technology_id", columns={"technology_id"})})
 * @Entity
 */
class UnitTechnologyCondition
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
     * @var \Technology
     *
     * @ManyToOne(targetEntity="Technology")
     * @JoinColumns({
     *   @JoinColumn(name="technology_id", referencedColumnName="technology_id")
     * })
     */
    private $technology;

    /**
     * @var \Unit
     *
     * @ManyToOne(targetEntity="Unit")
     * @JoinColumns({
     *   @JoinColumn(name="unit_id", referencedColumnName="unit_id")
     * })
     */
    private $unit;


}
