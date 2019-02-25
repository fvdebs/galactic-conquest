<?php

declare(strict_types=1);

namespace GC\Technology\Model;

/**
 * @Table(name="technology_relation", indexes={@Index(name="fk-technology_relation-target_technology_id", columns={"target_technology_id"}), @Index(name="fk-technology_relation-source_technology_id", columns={"source_technology_id"})})
 * @Entity
 */
class TechnologyRelation
{
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
     * @var \Technology
     *
     * @ManyToOne(targetEntity="Technology")
     * @JoinColumns({
     *   @JoinColumn(name="source_technology_id", referencedColumnName="technology_id")
     * })
     */
    private $sourceTechnology;

    /**
     * @var \Technology
     *
     * @ManyToOne(targetEntity="Technology")
     * @JoinColumns({
     *   @JoinColumn(name="target_technology_id", referencedColumnName="technology_id")
     * })
     */
    private $targetTechnology;


}
