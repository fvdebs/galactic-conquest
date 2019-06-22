<?php

declare(strict_types=1);

namespace GC\Tick\Plugin;

use GC\Tick\Model\TickRepository;
use GC\Universe\Model\Universe;

class AllianceIncreaseExtractorPointsPlugin implements TickPluginInterface
{
    /**
     * @var \GC\Tick\Model\TickRepository
     */
    private $tickRepository;

    /**
     * @param \GC\Tick\Model\TickRepository $tickRepository
     */
    public function __construct(TickRepository $tickRepository)
    {
        $this->tickRepository = $tickRepository;
    }

    /**
     * @param \GC\Universe\Model\Universe $universe
     * @param \GC\Tick\Plugin\TickPluginResultInterface $tickPluginResult
     *
     * @return \GC\Tick\Plugin\TickPluginResultInterface
     */
    public function executeFor(Universe $universe, TickPluginResultInterface $tickPluginResult): TickPluginResultInterface
    {
        $this->tickRepository->allianceIncreaseExtractorPointsFor(
            $universe->getUniverseId()
        );

        $tickPluginResult->setAffectedRows(
            $this->tickRepository->allianceUpdateRankingPositionFor(
                $universe->getUniverseId()
            )
        );

        return $tickPluginResult;
    }
}
