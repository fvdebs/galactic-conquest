<?php

declare(strict_types=1);

namespace GC\Combat\Model;

final class Battle implements BattleInterface
{
    /**
     * @var \GC\Combat\Model\FleetInterface[]
     */
    private $attackingFleets;

    /**
     * @var \GC\Combat\Model\FleetInterface[]
     */
    private $defendingFleets;

    /**
     * @var int
     */
    private $targetExtractorsMetal;

    /**
     * @var int
     */
    private $targetExtractorsCrystal;

    /**
     * @var string[]
     */
    private $targetData;

    /**
     * @param \GC\Combat\Model\FleetInterface[] $attackingFleets
     * @param \GC\Combat\Model\FleetInterface[] $defendingFleets
     * @param int $targetExtractorsMetal - default: 0
     * @param int $targetExtractorsCrystal -  default: 0
     * @param string[] $targetData - default: []
     */
    public function __construct(
        array $attackingFleets,
        array $defendingFleets,
        int $targetExtractorsMetal = 0,
        int $targetExtractorsCrystal = 0,
        array $targetData = []
    ) {
        $this->attackingFleets = $attackingFleets;
        $this->defendingFleets = $defendingFleets;
        $this->targetExtractorsMetal = $targetExtractorsMetal;
        $this->targetExtractorsCrystal = $targetExtractorsCrystal;
        $this->targetData = $targetData;
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getAttackingFleets()
    {
        return $this->attackingFleets;
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getDefendingFleets()
    {
        return $this->defendingFleets;
    }

    /**
     * @return int
     */
    public function getTargetExtractorsMetal(): int
    {
        return $this->targetExtractorsMetal;
    }

    /**
     * @return int
     */
    public function getTargetExtractorsCrystal(): int
    {
        return $this->targetExtractorsCrystal;
    }

    /**
     * @return string[]
     */
    public function getTargetData(): array
    {
        return $this->targetData;
    }

    /**
     * @param string $dataKey
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function groupFleetsByDataKey(string $dataKey, array $fleets): array
    {
        $data = [];
        foreach ($fleets as $fleet) {
            $dataValue = 0;
            if ($dataValue === $fleet->hasDataValue($dataKey)) {
                $dataValue = $fleet->getDataValue($dataKey);
            }

            $data[$dataValue][] = $fleet;
        }

        return $data;
    }
}
