<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerResourcesHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'player.resources';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);

        $extractorMetalIncomePerTick = $currentPlayer->calculateMetalIncomePerTick();
        $extractorMetalIncomePerDay = $currentPlayer->calculateMetalIncomePerDay();
        $technologiesWithMetalIncome = $currentPlayer->getTechnologiesCompletedWithMetalIncome();
        $technologiesTotalMetalIncomePerTick = $currentPlayer->getTotalMetalIncomeFromTechnologiesPerTick();
        $technologiesTotalMetalIncomePerDay = $currentPlayer->getTotalMetalIncomeFromTechnologiesPerDay();
        $subTotalMetalTechnologiesAndExtractorPerTick = $technologiesTotalMetalIncomePerTick + $extractorMetalIncomePerTick;
        $subTotalMetalTechnologiesAndExtractorPerDay = $technologiesTotalMetalIncomePerDay + $extractorMetalIncomePerDay;
        $galaxyMetalTaxPerTick = $currentPlayer->calculateMetalTaxPerTick();
        $galaxyMetalTaxPerDay = $currentPlayer->calculateMetalTaxPerDay();
        $totalMetalPerTick = $subTotalMetalTechnologiesAndExtractorPerTick - $galaxyMetalTaxPerTick;
        $totalMetalPerDay = $subTotalMetalTechnologiesAndExtractorPerDay - $galaxyMetalTaxPerDay;

        $extractorCrystalIncomePerTick = $currentPlayer->calculateCrystalIncomePerTick();
        $extractorCrystalIncomePerDay = $currentPlayer->calculateCrystalIncomePerDay();
        $technologiesWithCrystalIncome = $currentPlayer->getTechnologiesCompletedWithCrystalIncome();
        $technologiesTotalCrystalIncomePerTick = $currentPlayer->getTotalCrystalIncomeFromTechnologiesPerTick();
        $technologiesTotalCrystalIncomePerDay = $currentPlayer->getTotalCrystalIncomeFromTechnologiesPerDay();
        $subTotalCrystalTechnologiesAndExtractorPerTick = $technologiesTotalCrystalIncomePerTick + $extractorCrystalIncomePerTick;
        $subTotalCrystalTechnologiesAndExtractorPerDay = $technologiesTotalCrystalIncomePerDay + $extractorCrystalIncomePerDay;
        $galaxyCrystalTaxPerTick = $currentPlayer->calculateCrystalTaxPerTick();
        $galaxyCrystalTaxPerDay = $currentPlayer->calculateCrystalTaxPerDay();
        $totalCrystalPerTick = $subTotalCrystalTechnologiesAndExtractorPerTick - $galaxyCrystalTaxPerTick;
        $totalCrystalPerDay = $subTotalCrystalTechnologiesAndExtractorPerDay - $galaxyCrystalTaxPerDay;

        $metalCostForNextExtractor = $currentPlayer->calculateMetalCostForNextExtractor();
        $maxExtractorsToBuild = $currentPlayer->calculateMaxExtractorConstruction();

        return $this->render('@Player/player-resources.twig', [
            'extractorMetalIncomePerTick' => $extractorMetalIncomePerTick,
            'extractorMetalIncomePerDay' => $extractorMetalIncomePerDay,
            'technologiesWithMetalIncome' => $technologiesWithMetalIncome,
            'technologiesTotalMetalIncomePerTick' => $technologiesTotalMetalIncomePerTick,
            'technologiesTotalMetalIncomePerDay' => $technologiesTotalMetalIncomePerDay,
            'subTotalMetalTechnologiesAndExtractorPerTick' => $subTotalMetalTechnologiesAndExtractorPerTick,
            'subTotalMetalTechnologiesAndExtractorPerDay' => $subTotalMetalTechnologiesAndExtractorPerDay,
            'galaxyMetalTaxPerTick' => $galaxyMetalTaxPerTick,
            'galaxyMetalTaxPerDay' => $galaxyMetalTaxPerDay,
            'totalMetalPerTick' => $totalMetalPerTick,
            'totalMetalPerDay' => $totalMetalPerDay,
            'extractorCrystalIncomePerTick' => $extractorCrystalIncomePerTick,
            'extractorCrystalIncomePerDay' => $extractorCrystalIncomePerDay,
            'technologiesWithCrystalIncome' => $technologiesWithCrystalIncome,
            'technologiesTotalCrystalIncomePerTick' => $technologiesTotalCrystalIncomePerTick,
            'technologiesTotalCrystalIncomePerDay' => $technologiesTotalCrystalIncomePerDay,
            'subTotalCrystalTechnologiesAndExtractorPerTick' => $subTotalCrystalTechnologiesAndExtractorPerTick,
            'subTotalCrystalTechnologiesAndExtractorPerDay' => $subTotalCrystalTechnologiesAndExtractorPerDay,
            'galaxyCrystalTaxPerTick' => $galaxyCrystalTaxPerTick,
            'galaxyCrystalTaxPerDay' => $galaxyCrystalTaxPerDay,
            'totalCrystalPerTick' => $totalCrystalPerTick,
            'totalCrystalPerDay' => $totalCrystalPerDay,
            'metalCostForNextExtractor' => $metalCostForNextExtractor,
            'maxExtractorsToBuild' => $maxExtractorsToBuild,
        ]);
    }
}
