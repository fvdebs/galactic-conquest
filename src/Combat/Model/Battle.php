<?php

declare(strict_types=1);

namespace GC\Combat\Model;

use RuntimeException;

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
    private $data;

    /**
     * @var string[]
     */
    private $targetData;

    /**
     * @param \GC\Combat\Model\FleetInterface[] $attackingFleets - default: []
     * @param \GC\Combat\Model\FleetInterface[] $defendingFleets - default: []
     * @param string[] $data - default: []
     * @param string[] $targetData - default: []
     * @param int $targetExtractorsMetal - default: 0
     * @param int $targetExtractorsCrystal -  default: 0
     */
    public function __construct(
        array $attackingFleets = [],
        array $defendingFleets = [],
        array $targetData = [],
        array $data = [],
        int $targetExtractorsMetal = 0,
        int $targetExtractorsCrystal = 0
    ) {
        $this->attackingFleets = $attackingFleets;
        $this->defendingFleets = $defendingFleets;
        $this->data = $data;
        $this->targetExtractorsMetal = $targetExtractorsMetal;
        $this->targetExtractorsCrystal = $targetExtractorsCrystal;
        $this->targetData = $targetData;
    }

    /**
     * @return string[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasDataValue(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getDataValue(string $key)
    {
        if ($this->hasDataValue($key)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getAttackingFleets(): array
    {
        return $this->attackingFleets;
    }

    /**
     * @return \GC\Combat\Model\FleetInterface[]
     */
    public function getDefendingFleets(): array
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
     * @param int $fleetReference
     * @param \GC\Combat\Model\FleetInterface[] $fleets
     *
     * @return \GC\Combat\Model\FleetInterface
     */
    public function getFleetByReference(int $fleetReference, array $fleets): FleetInterface
    {
        foreach ($fleets as $fleet) {
            if ($fleet->getFleetReference() === $fleetReference) {
                return $fleet;
            }
        }

        throw new RuntimeException('fleet with given reference not found: ' . $fleetReference);
    }

    /**
     * Returns true if its equal and not empty.
     *
     * @param \GC\Combat\Model\FleetInterface $fleet
     * @param string $dataKey
     *
     * @return bool
     */
    public function compareFleetDataValueWithTargetDataValue(FleetInterface $fleet, string $dataKey): bool
    {
        $targetValue = $this->hasDataValue($dataKey);
        $fleetValue = $fleet->hasDataValue($dataKey);

        if (!$targetValue || !$fleetValue) {
            return false;
        }

        $targetValue = $this->getDataValue($dataKey);
        $fleetValue = $fleet->getDataValue($dataKey);

        if ($targetValue === null || $targetValue !== $fleetValue) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        foreach($this as $key => $val) {
            if (is_object($val) || (is_array($val))) {
                $this->{$key} = unserialize(serialize($val));
            }
        }
    }
}
