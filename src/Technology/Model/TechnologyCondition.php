<?php

declare(strict_types=1);

namespace GC\Technology\Model;

/**
 * @Table(name="technology_condition")
 * @Entity
 */
final class TechnologyCondition
{
    /**
     * @var int
     *
     * @Column(name="technology_relation_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $technologyRelationId;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="GC\Technology\Model\Technology", inversedBy="technologyConditions")
     * @JoinColumn(name="source_technology_id", referencedColumnName="technology_id", nullable=false)
     */
    private $sourceTechnology;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="\GC\Technology\Model\Technology")
     * @JoinColumn(name="target_technology_id", referencedColumnName="technology_id", nullable=false)
     */
    private $targetTechnology;

    /**
     * @param \GC\Technology\Model\Technology $sourceTechnology
     * @param \GC\Technology\Model\Technology $targetTechnology
     */
    public function __construct(Technology $sourceTechnology, Technology $targetTechnology)
    {
        $this->sourceTechnology = $sourceTechnology;
        $this->targetTechnology = $targetTechnology;
    }

    /**
     * @return int
     */
    public function getTechnologyRelationId(): int
    {
        return $this->technologyRelationId;
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    public function getSourceTechnology(): Technology
    {
        return $this->sourceTechnology;
    }

    /**
     * @param \GC\Technology\Model\Technology $sourceTechnology
     *
     * @return void
     */
    public function setSourceTechnology(Technology $sourceTechnology): void
    {
        $this->sourceTechnology = $sourceTechnology;
    }

    /**
     * @return \GC\Technology\Model\Technology
     */
    public function getTargetTechnology(): Technology
    {
        return $this->targetTechnology;
    }

    /**
     * @param \GC\Technology\Model\Technology $targetTechnology
     *
     * @return void
     */
    public function setTargetTechnology(Technology $targetTechnology): void
    {
        $this->targetTechnology = $targetTechnology;
    }
}