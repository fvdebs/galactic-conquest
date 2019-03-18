<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerTechnologyBuildHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;
    use GameAwareTrait;

    public const NAME = 'player.technology.build';
    private const FIELD_NAME_TECHNOLOGY_ID = 'value';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $technologyId = (int) $this->getValue(static::FIELD_NAME_TECHNOLOGY_ID, $request);

        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context(static::FIELD_NAME_TECHNOLOGY_ID)->isRequired()->isInt();

        $technology = $this->getTechnologyRepository()->findById($technologyId);
        if ($technology === null) {
            $validator->addMessage(static::FIELD_NAME_TECHNOLOGY_ID);
        }

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $player = $this->getCurrentPlayer($request);
        if ($player->hasTechnology($technology)) {
            $validator->addMessage(static::FIELD_NAME_TECHNOLOGY_ID);
        }

        if (!$player->hasTechnologyRequirementsFor($technology)) {
            $validator->addMessage(static::FIELD_NAME_TECHNOLOGY_ID);
        }

        if (!$player->hasResourcesForTechnology($technology)) {
            $validator->addMessage(static::FIELD_NAME_TECHNOLOGY_ID);
        }

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $player->buildTechnology($technology);
        $this->flush();

        return $this->redirectJson(
            PlayerTechnologyListHandler::NAME,
            ['universe' => $this->getCurrentUniverse($request)->getRouteName()]
        );
    }
}