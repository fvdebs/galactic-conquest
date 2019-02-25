<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use GC\Technology\Model\Technology;

/**
 * @Table(name="alliance_technology", indexes={@Index(name="fk-alliance_technology-alliance_id", columns={"alliance_id"}), @Index(name="fk-alliance-technology_id", columns={"technology_id"})})
 * @Entity
 */
final class AllianceTechnology
{
    /**
     * @var int
     *
     * @Column(name="alliance_technology_id", type="bigint", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $allianceTechnologyId;

    /**
     * @var \GC\Technology\Model\Technology
     *
     * @ManyToOne(targetEntity="GC\Technology\Model\Technology")
     * @JoinColumns({
     *   @JoinColumn(name="technology_id", referencedColumnName="technology_id")
     * })
     */
    private $technology;

    /**
     * @var \GC\Alliance\Model\Alliance
     *
     * @ManyToOne(targetEntity="Alliance")
     * @JoinColumns({
     *   @JoinColumn(name="alliance_id", referencedColumnName="alliance_id")
     * })
     */
    private $alliance;

    /**
     * @param \GC\Technology\Model\Technology $technology
     * @param \GC\Alliance\Model\Alliance $alliance
     */
    public function __construct(Technology $technology, Alliance $alliance)
    {
        $this->technology = $technology;
        $this->alliance = $alliance;
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
}