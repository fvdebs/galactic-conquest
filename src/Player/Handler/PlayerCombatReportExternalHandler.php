<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\Combat\Service\CombatServiceInterface;
use GC\Home\Handler\HomeHandler;
use GC\Player\Model\PlayerCombatReportRepository;
use Inferno\Http\Response\ResponseFactoryInterface;
use Inferno\Renderer\RendererInterface;
use Inferno\Routing\UrlGenerator\UrlGeneratorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerCombatReportExternalHandler implements RequestHandlerInterface
{
    public const NAME = 'player.combat.report.external';

    private const REQUEST_PARAMETER_EXTERNAL_COMBAT_REPORT_ID = 'externalCombatReportId';
    private const POINT_OF_VIEW_DATA_KEY = 'playerId';
    private const POINT_OF_VIEW_DISPLAY_NAME_DATA_KEY = 'name';

    /**
     * @var \Inferno\Renderer\RendererInterface
     */
    private $renderer;

    /**
     * @var \Inferno\Routing\UrlGenerator\UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var \Inferno\Http\Response\ResponseFactoryInterface
     */
    private $responseFactory;

    /**
     * @var \GC\Player\Model\PlayerCombatReportRepository
     */
    private $playerCombatReportRepository;

    /**
     * @var \GC\Combat\Service\CombatServiceInterface
     */
    private $combatService;

    /**
     * @param \Inferno\Renderer\RendererInterface $renderer
     * @param \Inferno\Routing\UrlGenerator\UrlGeneratorInterface $urlGenerator
     * @param \Inferno\Http\Response\ResponseFactoryInterface $responseFactory
     * @param \GC\Player\Model\PlayerCombatReportRepository $playerCombatReportRepository
     * @param \GC\Combat\Service\CombatServiceInterface $combatService
     */
    public function __construct(
        RendererInterface $renderer,
        UrlGeneratorInterface $urlGenerator,
        ResponseFactoryInterface $responseFactory,
        PlayerCombatReportRepository $playerCombatReportRepository,
        CombatServiceInterface $combatService
    ) {
        $this->renderer = $renderer;
        $this->responseFactory = $responseFactory;
        $this->playerCombatReportRepository = $playerCombatReportRepository;
        $this->combatService = $combatService;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $combatReportExternalId = (string) $request->getAttribute(static::REQUEST_PARAMETER_EXTERNAL_COMBAT_REPORT_ID);

        $combatReport = $this->playerCombatReportRepository
            ->findByExternalId($combatReportExternalId);

        if ($combatReport === null) {
            return $this->responseFactory->createFromContent(
                $this->urlGenerator->generate(HomeHandler::NAME)
            );
        }

        $response = $this->combatService->generateCombatReportFromJson(
            $combatReport->getDataJson(),
            $combatReport->getPlayer()->getPlayerId(),
            static::POINT_OF_VIEW_DATA_KEY,
            static::POINT_OF_VIEW_DISPLAY_NAME_DATA_KEY
        );

        return $this->responseFactory->createFromContent(
            $this->renderer->render('@Player/player-combat-report-external.twig', [
                'response' => $response
            ])
        );
    }
}
