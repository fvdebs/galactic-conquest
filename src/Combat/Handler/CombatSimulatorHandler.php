<?php

declare(strict_types=1);

namespace GC\Combat\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\Combat\Model\Fleet;
use GC\Combat\Model\FleetInterface;
use GC\Combat\Model\SettingsInterface;
use GC\Combat\Service\CombatServiceInterface;
use GC\Combat\Service\CombatSimulationRequest;
use GC\Universe\Model\Universe;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_key_exists;
use function count;
use function array_pop;

final class CombatSimulatorHandler implements RequestHandlerInterface
{
    use GameAwareTrait;

    public const NAME = 'combat.simulator';

    /**
     * @var \Inferno\Renderer\RendererInterface
     */
    private $renderer;

    /**
     * @var \Inferno\Http\Response\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \GC\Combat\Service\CombatServiceInterface
     */
    private $combatService;

    /**
     * @param \Inferno\Renderer\RendererInterface $renderer
     * @param \Inferno\Http\Response\ResponseFactoryInterface $responseFactory
     * @param \GC\Combat\Service\CombatServiceInterface $combatService
     */
    public function __construct(
        RendererInterface $renderer,
        ResponseFactoryInterface $responseFactory,
        CombatServiceInterface $combatService
    ) {
        $this->renderer = $renderer;
        $this->responseFactory = $responseFactory;
        $this->combatService = $combatService;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentUniverse = $this->getCurrentUniverse($request);
        $settings = $this->combatService->mapSettingsFromUniverse($currentUniverse);

        $fleets = $this->parseFleetsFrom($request);
        $calculationTicks = $this->parseCalculationTicksFrom($request);
        $targetExtractorsMetal = $this->parseTargetExtractorsMetalFrom($request);
        $targetExtractorsCrystal = $this->parseTargetExtractorsCrystalFrom($request);

        if (array_key_exists('addFleet', $request->getQueryParams())) {
            $fleets[] = $this->createNewRequestFleet();;
        }

        if (array_key_exists('removeFleet', $request->getQueryParams())) {
            $fleets = $this->removeFleet($fleets);
        }

        $responses = [];
        if (array_key_exists('simulate', $request->getQueryParams())) {
            $simulationRequest = $this->createCombatSimulationRequest(
                $settings,
                $fleets,
                $calculationTicks,
                $targetExtractorsMetal,
                $targetExtractorsCrystal,
                $currentUniverse
            );

            $responses = $this->combatService->simulate($simulationRequest);
        }

        foreach ($fleets as $index => $fleetData) {
            // add fleet reference if field is empty
            $fleets[$index]['fleetReference'] = $this->getFleetReferenceFrom($fleetData, sprintf('Fleet %s', ($index + 1)));
        }

        return $this->responseFactory->createFromContent(
            $this->renderer->render('@Combat/combat-report-simulator.twig', [
                'responses' => $responses,
                'settings' => $settings,
                'fleets' => $fleets,
                'targetExtractorsMetal' => $targetExtractorsMetal,
                'targetExtractorsCrystal' => $targetExtractorsCrystal,
                'calculationTicks' => $calculationTicks,
            ])
        );
    }

    /**
     * @param \GC\Combat\Model\SettingsInterface $settings
     * @param array $fleets
     * @param int $calculationTicks
     * @param int $targetExtractorsMetal
     * @param int $targetExtractorsCrystal
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Combat\Service\CombatSimulationRequest
     */
    private function createCombatSimulationRequest(
        SettingsInterface $settings,
        array $fleets,
        int $calculationTicks,
        int $targetExtractorsMetal,
        int $targetExtractorsCrystal,
        Universe $universe
    ): CombatSimulationRequest {
        $simulationRequest = new CombatSimulationRequest(
            $calculationTicks,
            $settings,
            $targetExtractorsMetal,
            $targetExtractorsCrystal,
            [
                'universe' => $universe->getName(),
                'universeId' => $universe->getUniverseId(),
            ]
        );

        foreach ($fleets as $index => $fleetData) {
            if (!array_key_exists('units', $fleetData)) {
                continue;
            }

            $fleet = $this->createFleet(($index + 1), $fleetData);

            if ($this->isOffensiveFleet($fleetData)) {
                $simulationRequest->addAttackingFleet(
                    $fleet,
                    $this->getIncomingTicksFrom($fleetData),
                    $this->getDurationTicksFrom($fleetData)
                );

                continue;
            }

            $simulationRequest->addDefendingFleet(
                $fleet,
                $this->getIncomingTicksFrom($fleetData),
                $this->getDurationTicksFrom($fleetData)
            );
        }

        return $simulationRequest;
    }

    /**
     * @param string[] $fleetData
     *
     * @return bool
     */
    private function isOffensiveFleet(array $fleetData): bool
    {
        return $fleetData['type'] === 'offensive';
    }

    /**
     * @param string[] $fleetData
     *
     * @return int
     */
    private function getIncomingTicksFrom(array $fleetData): int
    {
        return (int) $fleetData['incomingTick'];
    }

    /**
     * @param string[] $fleetData
     *
     * @return int
     */
    private function getDurationTicksFrom(array $fleetData): int
    {
        return (int) $fleetData['durationTicks'];
    }

    /**
     * @param string[] $fleetData
     * @param string $default
     *
     * @return string
     */
    private function getFleetReferenceFrom(array $fleetData, string $default = ''): string
    {
        if (!array_key_exists('fleetReference', $fleetData)) {
            return $default;
        }

        if (empty($fleetData['fleetReference'])) {
            return $default;
        }

        return $fleetData['fleetReference'];
    }

    /**
     * @param int $fleetId
     * @param string[] $fleetData
     *
     * @return \GC\Combat\Model\FleetInterface
     */
    private function createFleet(int $fleetId, array $fleetData): FleetInterface
    {
        $fleet = new Fleet(
            $fleetId,
            $fleetData['units'],
            ['fleetReference' => $fleetData['fleetReference']]
        );

        if ($fleetId === 1) {
            $fleet->setIsTarget(true);
        }

        return $fleet;
    }

    /**
     * @return string[]
     */
    private function createNewRequestFleet(): array
    {
        return ['durationTicks' => 5];
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return string[]
     */
    private function parseFleetsFrom(ServerRequestInterface $request): array
    {
        $fleets[] = $this->createNewRequestFleet();

        if ($this->hasValue('fleets', $request)) {
            return $this->getValue('fleets', $request);
        }

        return $fleets;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return int
     */
    private function parseCalculationTicksFrom(ServerRequestInterface $request): int
    {
        $calculationTicks = 0;

        if ($this->hasValue('calculationTicks', $request)) {
            $calculationTicks = (int) $this->getValue('calculationTicks', $request);
        }

        if ($calculationTicks <= 99 && $calculationTicks > 0) {
            return $calculationTicks;
        }

        return 5;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return int
     */
    private function parseTargetExtractorsMetalFrom(ServerRequestInterface $request): int
    {
        if ($this->hasValue('targetExtractorsMetal', $request)) {
            return (int) $this->getValue('targetExtractorsMetal', $request);
        }

        return 0;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return int
     */
    private function parseTargetExtractorsCrystalFrom(ServerRequestInterface $request): int
    {
        if ($this->hasValue('targetExtractorsCrystal', $request)) {
            return (int) $this->getValue('targetExtractorsCrystal', $request);
        }

        return 0;
    }

    /**
     * @param string[] $fleets
     *
     * @return string[]
     */
    private function removeFleet(array $fleets): array
    {
        array_pop($fleets);

        if (count($fleets) <= 0) {
            $fleets[] = $this->createNewRequestFleet();
        }

        return $fleets;
    }

    /**
     * @param string $key
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return bool
     */
    private function hasValue(string $key, ServerRequestInterface $request): bool
    {
        return array_key_exists($key, $request->getParsedBody());
    }

    /**
     * @param string $key
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return mixed
     */
    private function getValue(string $key, ServerRequestInterface $request)
    {
        return $request->getParsedBody()[$key];
    }
}
