<?php

declare(strict_types=1);

namespace GC\Combat\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\Combat\Model\Battle;
use GC\Combat\Model\Fleet;
use GC\Combat\Service\CombatServiceInterface;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CombatSimulatorHandler implements RequestHandlerInterface
{
    use GameAwareTrait;

    public const NAME = 'combat.simulator';

    private const POINT_OF_VIEW_DATA_KEY = 'fleetReference';
    private const POINT_OF_VIEW_DISPLAY_NAME_DATA_KEY = 'fleetReference';

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
        $settings = $this->combatService->mapSettingsFromUniverse(
            $this->getCurrentUniverse($request)
        );

        $settings->setIsLastTick(true);

        $fleet1 = new Fleet(
            1,
            [9 => 200],
            [
                'fleetReference' => 'Flotte 1',
            ]
        );

        $fleet2 = new Fleet(
            2,
            [7 => 300],
            [
                'fleetReference' => 'Flotte 2',
            ]
        );

        $fleet3 = new Fleet(
            3,
            [6 => 400],
            [
                'fleetReference' => 'Flotte 3',
            ]
        );

        $battle = new Battle(
            [$fleet1, $fleet2],
            [$fleet3],
            100,
            100,
            [
                'name' => 'Target'
            ],
            [
                'universe' => 'Sirius',
                'universeId' => 1,
            ]
        );

        $combatResponse = $this->combatService->calculate($battle, $settings);

        $json = $this->combatService->formatToJson($combatResponse);

        $response = $this->combatService->generateCombatReportFromJson(
            $json,
            1,
            static::POINT_OF_VIEW_DATA_KEY,
            static::POINT_OF_VIEW_DISPLAY_NAME_DATA_KEY
        );

        return $this->responseFactory->createFromContent(
            $this->renderer->render('@Combat/combat-report-simulator.twig', ['response' => $response])
        );
    }
}
