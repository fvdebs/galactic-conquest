<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerUnitBuildHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'player.unit.build';
    private const FIELD_NAME_QUANTITY = 'value';
    private const FIELD_NAME_UNIT_ID = 'identifier';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $quantity = (int) $this->getValue(static::FIELD_NAME_QUANTITY, $request);
        $unitId = (int) $this->getValue(static::FIELD_NAME_UNIT_ID, $request);

        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context(static::FIELD_NAME_QUANTITY)->isRequired()->isInt();
        $validator->context(static::FIELD_NAME_UNIT_ID)->isRequired()->isInt();

        $unit = $this->getUnitRepository()->findById($unitId);
        if ($unit === null) {
            $validator->addMessage(static::FIELD_NAME_UNIT_ID);
        }

        $player = $this->getCurrentPlayer($request);
        if ($player->isUnitInConstruction($unit)) {
            $validator->addMessage(static::FIELD_NAME_UNIT_ID);
        }

        if (!$player->hasUnitRequirementsFor($unit)) {
            $validator->addMessage(static::FIELD_NAME_UNIT_ID);
        }

        if (!$player->hasResourcesForUnitAndQuantity($unit, $quantity)) {
            $validator->addMessage(static::FIELD_NAME_UNIT_ID);
        }

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $player->buildUnit($unit, $quantity);
        $this->flush();

        return $this->redirectJson(
            PlayerUnitListHandler::NAME,
            ['universe' => $this->getCurrentUniverse($request)->getRouteName()]
        );
    }
}