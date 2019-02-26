<?php

declare(strict_types=1);

namespace GC\Technology\Model;

/**
 * @Table(name="technology_relation", indexes={@Index(name="fk-technology_relation-target_technology_id", columns={"target_technology_id"}), @Index(name="fk-technology_relation-source_technology_id", columns={"source_technology_id"})})
 * @Entity
 */
final class TechnologyRelation
{
    public const RELATION_DEPENDS = 'depends';
    public const RELATION_OR = 'or';

    /**
     * @var int
     *
     * @Column(name="technology_relation_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $technologyRelationId;

    /**
     * @var string
     *
     * @Column(name="relation_type", type="string", length=150, nullable=false)
     */
    private $relationType;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="\GC\Technology\Model\Technology")
     * @JoinColumns({
     *   @JoinColumn(name="source_technology_id", referencedColumnName="technology_id")
     * })
     */
    private $sourceTechnology;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="\GC\Technology\Model\Technology")
     * @JoinColumns({
     *   @JoinColumn(name="target_technology_id", referencedColumnName="technology_id")
     * })
     */
    private $targetTechnology;

    /**
     * @param \GC\Technology\Model\Technology $sourceTechnology
     * @param \GC\Technology\Model\Technology $targetTechnology
     * @param string $relationType
     *
     */
    public function __construct(Technology $sourceTechnology, Technology $targetTechnology, string $relationType)
    {
        $this->sourceTechnology = $sourceTechnology;
        $this->targetTechnology = $targetTechnology;
        $this->relationType = $relationType;
    }

    /**
     * @return int
     */
    public function getTechnologyRelationId(): int
    {
        return $this->technologyRelationId;
    }

    /**
     * @return string
     */
    public function getRelationType(): string
    {
        return $this->relationType;
    }

    /**
     * @param string $relationType
     *
     * @return void
     */
    public function setRelationType(string $relationType): void
    {
        $this->relationType = $relationType;
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