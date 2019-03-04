<?php

declare(strict_types=1);

namespace GC\Alliance\Model;

use GC\Galaxy\Model\Galaxy;

/**
 * @Table(name="alliance_application")
 * @Entity
 */
class AllianceApplication
{
    /**
     * @var int
     *
     * @Column(name="alliance_application_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $allianceApplicationId;

    /**
     * @var \GC\Alliance\Model\Alliance
     *
     * @ManyToOne(targetEntity="GC\Alliance\Model\Alliance")
     * @JoinColumn(name="alliance_id", referencedColumnName="alliance_id", nullable=false)
     */
    private $alliance;

    /**
     * @var \GC\Galaxy\Model\Galaxy
     *
     * @ManyToOne(targetEntity="GC\Galaxy\Model\Galaxy", inversedBy="allianceApplications")
     * @JoinColumn(name="galaxy_id", referencedColumnName="galaxy_id", nullable=false)
     */
    private $galaxy;

    /**
     * @param \GC\Alliance\Model\Alliance $alliance
     * @param \GC\Galaxy\Model\Galaxy $galaxy
     */
    public function __construct(Alliance $alliance, Galaxy $galaxy)
    {
        $this->alliance = $alliance;
        $this->galaxy = $galaxy;
    }

    /**
     * @return int
     */
    public function getAllianceApplicationId(): int
    {
        return $this->allianceApplicationId;
    }

    /**
     * @return \GC\Alliance\Model\Alliance
     */
    public function getAlliance(): Alliance
    {
        return $this->alliance;
    }

    /**
     * @return \GC\Galaxy\Model\Galaxy
     */
    public function getGalaxy():Galaxy
    {
        return $this->galaxy;
    }
}