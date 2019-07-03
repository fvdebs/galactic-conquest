<?php

declare(strict_types=1);

namespace GC\Combat\Report;

use DateTime;
use GC\Combat\Format\JsonFormatter;
use Inferno\Renderer\RendererInterface;

use function json_decode;
use function array_key_exists;

final class CombatReportGenerator implements CombatReportGeneratorInterface
{
    private const DATA_UNIVERSE = 'universe';

    /**
     * @var \GC\Combat\Format\JsonFormatterInterface
     */
    private $renderer;

    /**
     * @param \Inferno\Renderer\RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param string $json
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string $pointOfViewDisplayNameDataKey
     *
     * @return \GC\Combat\Report\CombatReportGeneratorResponseInterface
     */
    public function generateCombatReportFromJson(
        string $json,
        $pointOfView,
        string $pointOfViewDataKey,
        string $pointOfViewDisplayNameDataKey
    ): CombatReportGeneratorResponseInterface {
        $data = json_decode($json, true);

        $defenders = $this->getDefendersFrom($pointOfViewDataKey, $data);
        $aggressors = $this->getAggressorsFrom($pointOfViewDataKey, $data);

        $isTypeOffensive = array_key_exists($pointOfView, $aggressors);
        $isLastTick = $data[JsonFormatter::KEY_SETTINGS][JsonFormatter::KEY_IS_LAST_TICK];

        $pointOfViewDisplayName = $this->getPointOfViewDisplayName(
            $pointOfView,
            $pointOfViewDataKey,
            $pointOfViewDisplayNameDataKey,
            $data,
            $isTypeOffensive
        );

        $pointOfViewSalvagedMetal = 0;
        $pointOfViewSalvagedCrystal = 0;

        $pointOfViewExtractorStolenMetal = 0;
        $pointOfViewExtractorStolenCrystal = 0;

        if ($isTypeOffensive) {
            $pointOfViewExtractorStolenMetal = $this->sumExtractorStolenMetalFor($pointOfView, $pointOfViewDataKey, $data);
            $pointOfViewExtractorStolenCrystal = $this->sumExtractorStolenCrystalFor($pointOfView, $pointOfViewDataKey, $data);
        } else {
            $pointOfViewSalvagedMetal = $this->sumSalvagedMetalFor($pointOfView, $pointOfViewDataKey, $data);
            $pointOfViewSalvagedCrystal = $this->sumSalvagedCrystalFor($pointOfView, $pointOfViewDataKey, $data);
        }

        $extractorsUnprotected = $this->getNumberOfExtractorsUnprotected($data);

        $pointOfViewFleetSumBefore = $this->getSumPointOfViewFleetBefore($pointOfView, $pointOfViewDataKey, $data, $isTypeOffensive);
        $pointOfViewFleetSumAfter = $this->getSumPointOfViewFleetAfter($pointOfView, $pointOfViewDataKey, $data, $isTypeOffensive);
        $pointOfViewFleetSumDestroyed = $this->getSumPointOfViewFleetDestroyed($pointOfView, $pointOfViewDataKey, $data, $isTypeOffensive);
        $pointOfViewFleetSumCarrierLosses = $this->getSumPointOfViewCarrierLosses($pointOfView, $pointOfViewDataKey, $data, $isTypeOffensive);
        $pointOfViewCarrierCapacity = $this->getSumPointOfViewCarrierCapacity($pointOfView, $pointOfViewDataKey, $data, $isTypeOffensive);
        $pointOfViewCarrierCapacityConsumed = $this->getSumPointOfViewCarrierCapacityConsumed($pointOfView, $pointOfViewDataKey, $data, $isTypeOffensive);

        $reportData = $data[JsonFormatter::KEY_REPORT];
        $createdAt = new DateTime($reportData[JsonFormatter::KEY_CREATED_AT]);
        $targetName = $data[JsonFormatter::KEY_TARGET][JsonFormatter::KEY_DATA][JsonFormatter::KEY_NAME];

        $renderedHtml = $this->renderer->render('@Combat/combat-report.twig', [
            'combat' => $data,
            'isTypeOffensive' => $isTypeOffensive,
            'isLastTick' => $isLastTick,
            'defenders' => $defenders,
            'aggressors' => $aggressors,
            'createdAt' => $createdAt,
            'universe' => $data[JsonFormatter::KEY_REPORT][static::DATA_UNIVERSE],
            'extractorsUnprotected' => $extractorsUnprotected,
            'pointOfViewSalvagedMetal' => $pointOfViewSalvagedMetal,
            'pointOfViewSalvagedCrystal' => $pointOfViewSalvagedCrystal,
            'pointOfViewExtractorStolenMetal' => $pointOfViewExtractorStolenMetal,
            'pointOfViewExtractorStolenCrystal' => $pointOfViewExtractorStolenCrystal,
            'pointOfViewFleetSumBefore' => $pointOfViewFleetSumBefore,
            'pointOfViewFleetSumAfter' => $pointOfViewFleetSumAfter,
            'pointOfViewFleetSumDestroyed' => $pointOfViewFleetSumDestroyed,
            'pointOfViewFleetSumCarrierLosses' => $pointOfViewFleetSumCarrierLosses,
            'pointOfViewCarrierCapacity' => $pointOfViewCarrierCapacity,
            'pointOfViewCarrierCapacityConsumed' => $pointOfViewCarrierCapacityConsumed,
            'pointOfViewDataKey' => $pointOfViewDataKey,
            'pointOfViewDisplayName' => $pointOfViewDisplayName,
            'pointOfViewDisplayNameDataKey' => $pointOfViewDisplayNameDataKey,
        ]);

        return new CombatReportGeneratorResponse(
            $renderedHtml,
            $isTypeOffensive,
            $pointOfViewDisplayName,
            $targetName,
            $createdAt,
            $reportData
        );
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     * @param bool $isTypeOffensive
     *
     * @return string[]
     */
    private function getSumPointOfViewFleetBefore($pointOfView, string $pointOfViewDataKey, array $data, bool $isTypeOffensive): array
    {
        $dataToSearchFor = $data[JsonFormatter::KEY_DEFENSE];
        if ($isTypeOffensive) {
            $dataToSearchFor = $data[JsonFormatter::KEY_OFFENSE];
        }

        $fleetSum = [];

        foreach ($dataToSearchFor as $fleet) {
            if ($fleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            foreach ($fleet[JsonFormatter::KEY_BEFORE] as $unitName => $quantity) {
                if (!array_key_exists($unitName, $fleetSum)) {
                    $fleetSum[$unitName] = 0.0;
                }

                $fleetSum[$unitName] += $quantity;
            }
        }

        return $fleetSum;
    }

    /**
     * @param string[] $data
     *
     * @return int
     */
    private function getNumberOfExtractorsUnprotected(array $data): int
    {
        $extractorsUnprotected =
            $data[JsonFormatter::KEY_SUMMARY][JsonFormatter::KEY_OFFENSE][JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_EXTRACTOR][JsonFormatter::KEY_STEAL_CAPACITY] -
            $data[JsonFormatter::KEY_SUMMARY][JsonFormatter::KEY_DEFENSE][JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_EXTRACTOR][JsonFormatter::KEY_PROTECTED];

        $targetExtractorsTotal =
            $data[JsonFormatter::KEY_TARGET][JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_EXTRACTOR][JsonFormatter::KEY_BEFORE][JsonFormatter::KEY_METAL] +
            $data[JsonFormatter::KEY_TARGET][JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_EXTRACTOR][JsonFormatter::KEY_BEFORE][JsonFormatter::KEY_CRYSTAL];

        if ($extractorsUnprotected < 0) {
            return 0;
        }

        if ($extractorsUnprotected > $targetExtractorsTotal) {
            return $targetExtractorsTotal;
        }

        return $extractorsUnprotected;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     * @param bool $isTypeOffensive
     *
     * @return string[]
     */
    private function getSumPointOfViewFleetAfter($pointOfView, string $pointOfViewDataKey, array $data, bool $isTypeOffensive): array
    {
        $dataToSearchFor = $data[JsonFormatter::KEY_DEFENSE];
        if ($isTypeOffensive) {
            $dataToSearchFor = $data[JsonFormatter::KEY_OFFENSE];
        }

        $fleetSum = [];

        foreach ($dataToSearchFor as $fleet) {
            if ($fleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            foreach ($fleet[JsonFormatter::KEY_AFTER] as $unitName => $quantity) {
                if (!array_key_exists($unitName, $fleetSum)) {
                    $fleetSum[$unitName] = 0.0;
                }

                $fleetSum[$unitName] += $quantity;
            }
        }

        return $fleetSum;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     * @param bool $isTypeOffensive
     *
     * @return string[]
     */
    private function getSumPointOfViewFleetDestroyed($pointOfView, string $pointOfViewDataKey, array $data, bool $isTypeOffensive): array
    {
        $dataToSearchFor = $data[JsonFormatter::KEY_DEFENSE];
        if ($isTypeOffensive) {
            $dataToSearchFor = $data[JsonFormatter::KEY_OFFENSE];
        }

        $fleetSum = [];

        foreach ($dataToSearchFor as $fleet) {
            if ($fleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            foreach ($fleet[JsonFormatter::KEY_DESTROYED] as $unitName => $quantity) {
                if (!array_key_exists($unitName, $fleetSum)) {
                    $fleetSum[$unitName] = 0.0;
                }

                $fleetSum[$unitName] += $quantity;
            }
        }

        return $fleetSum;
    }

    /**
     * @param string[] $data
     * @param string $pointOfViewDataKey
     *
     * @return string[]
     */
    private function getDefendersFrom(string $pointOfViewDataKey, array $data): array
    {
        $defender = [];

        $defenderDataArray = $data[JsonFormatter::KEY_SUMMARY][JsonFormatter::KEY_DEFENSE][JsonFormatter::KEY_DATA];

        foreach ($defenderDataArray as $defenderData) {
            $defender[$defenderData[$pointOfViewDataKey]] = $defenderData;
        }

        return $defender;
    }

    /**
     * @param string[] $data
     * @param string $pointOfViewDataKey
     *
     * @return string[]
     */
    private function getAggressorsFrom(string $pointOfViewDataKey, array $data): array
    {
        $aggressor = [];

        $aggressorDataArray = $data[JsonFormatter::KEY_SUMMARY][JsonFormatter::KEY_OFFENSE][JsonFormatter::KEY_DATA];

        foreach ($aggressorDataArray as $aggressorData) {
            $aggressor[$aggressorData[$pointOfViewDataKey]] = $aggressorData;
        }

        return $aggressor;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     *
     * @return int
     */
    private function sumSalvagedMetalFor($pointOfView, string $pointOfViewDataKey, array $data): int
    {
        $salvagedMetal = 0;

        foreach ($data[JsonFormatter::KEY_DEFENSE] as $defenseFleet) {
            if ($defenseFleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            $salvagedMetal += $defenseFleet[JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_SALVAGED][JsonFormatter::KEY_METAL];
        }

        return $salvagedMetal;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     *
     * @return int
     */
    private function sumSalvagedCrystalFor($pointOfView, string $pointOfViewDataKey, array $data): int
    {
        $salvagedCrystal = 0;

        foreach ($data[JsonFormatter::KEY_DEFENSE] as $defenseFleet) {
            if ($defenseFleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            $salvagedCrystal += $defenseFleet[JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_SALVAGED][JsonFormatter::KEY_CRYSTAL];
        }

        return $salvagedCrystal;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     *
     * @return int
     */
    private function sumExtractorStolenMetalFor($pointOfView, string $pointOfViewDataKey, array $data): int
    {
        $extractorStolenMetal = 0;

        foreach ($data[JsonFormatter::KEY_OFFENSE] as $offenseFleet) {
            if ($offenseFleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            $extractorStolenMetal += $offenseFleet[JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_EXTRACTOR][JsonFormatter::KEY_EXTRACTOR_STOLEN][JsonFormatter::KEY_METAL];
        }

        return $extractorStolenMetal;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     *
     * @return int
     */
    private function sumExtractorStolenCrystalFor($pointOfView, string $pointOfViewDataKey, array $data): int
    {
        $extractorStolenCrystal = 0;

        foreach ($data[JsonFormatter::KEY_OFFENSE] as $offenseFleet) {
            if ($offenseFleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            $extractorStolenCrystal += $offenseFleet[JsonFormatter::KEY_RESOURCE][JsonFormatter::KEY_EXTRACTOR][JsonFormatter::KEY_EXTRACTOR_STOLEN][JsonFormatter::KEY_CRYSTAL];
        }

        return $extractorStolenCrystal;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string $pointOfViewDisplayNameDataKey
     * @param string[] $data
     * @param bool $isTypeOffensive
     *
     * @return string
     */
    private function getPointOfViewDisplayName(
        $pointOfView,
        string $pointOfViewDataKey,
        string $pointOfViewDisplayNameDataKey,
        array $data,
        bool $isTypeOffensive
    ): string {
        $dataToSearchFor = $data[JsonFormatter::KEY_DEFENSE];
        if ($isTypeOffensive) {
            $dataToSearchFor = $data[JsonFormatter::KEY_OFFENSE];
        }

        foreach ($dataToSearchFor as $fleet) {
            if ($fleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            return (string) $fleet[JsonFormatter::KEY_DATA][$pointOfViewDisplayNameDataKey];
        }

        return 'Unknown';
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     * @param bool $isTypeOffensive
     *
     * @return string[]
     */
    private function getSumPointOfViewCarrierLosses($pointOfView, string $pointOfViewDataKey, array $data, bool $isTypeOffensive): array
    {
        $dataToSearchFor = $data[JsonFormatter::KEY_DEFENSE];
        if ($isTypeOffensive) {
            $dataToSearchFor = $data[JsonFormatter::KEY_OFFENSE];
        }

        $carrierLosses = [];

        foreach ($dataToSearchFor as $fleet) {
            if ($fleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            foreach ($fleet[JsonFormatter::KEY_CARRIER][JsonFormatter::KEY_CARRIER_LOSSES] as $unitName => $quantity) {
                if (!array_key_exists($unitName, $carrierLosses)) {
                    $carrierLosses[$unitName] = 0.0;
                }

                $carrierLosses[$unitName] += $quantity;
            }
        }

        return $carrierLosses;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     * @param bool $isTypeOffensive
     *
     * @return int
     */
    private function getSumPointOfViewCarrierCapacity($pointOfView, string $pointOfViewDataKey, array $data, bool $isTypeOffensive): int
    {
        $dataToSearchFor = $data[JsonFormatter::KEY_DEFENSE];
        if ($isTypeOffensive) {
            $dataToSearchFor = $data[JsonFormatter::KEY_OFFENSE];
        }

        $carrierCapacity = 0.0;

        foreach ($dataToSearchFor as $fleet) {
            if ($fleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            $carrierCapacity += $fleet[JsonFormatter::KEY_CARRIER][JsonFormatter::KEY_CARRIER_CAPACITY];
        }

        return $carrierCapacity;
    }

    /**
     * @param mixed $pointOfView
     * @param string $pointOfViewDataKey
     * @param string[] $data
     * @param bool $isTypeOffensive
     *
     * @return int
     */
    private function getSumPointOfViewCarrierCapacityConsumed($pointOfView, string $pointOfViewDataKey, array $data, bool $isTypeOffensive): int
    {
        $dataToSearchFor = $data[JsonFormatter::KEY_DEFENSE];
        if ($isTypeOffensive) {
            $dataToSearchFor = $data[JsonFormatter::KEY_OFFENSE];
        }

        $carrierCapacityConsumed = 0;

        foreach ($dataToSearchFor as $fleet) {
            if ($fleet[JsonFormatter::KEY_DATA][$pointOfViewDataKey] !== $pointOfView) {
                continue;
            }

            $carrierCapacityConsumed += $fleet[JsonFormatter::KEY_CARRIER][JsonFormatter::KEY_CARRIER_CAPACITY_CONSUMED];
        }

        return $carrierCapacityConsumed;
    }
}
