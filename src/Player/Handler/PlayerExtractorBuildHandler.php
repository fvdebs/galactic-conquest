<?php

declare(strict_types=1);

namespace GC\Player\Handler;

use GC\App\Aware\GameAwareTrait;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PlayerExtractorBuildHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use GameAwareTrait;

    public const NAME = 'player.extractor.build';
    private const FIELD_NAME_EXTRACTOR_TYPE = 'type';
    private const FIELD_NAME_EXTRACTOR_TYPE_VALUES = ['metal', 'crystal'];
    private const FIELD_NAME_EXTRACTOR_TYPE_METAL = 'metal';
    private const FIELD_NAME_EXTRACTOR_TYPE_CRYSTAL = 'crystal';
    private const FIELD_NAME_EXTRACTOR_NUMBER = 'number';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $currentPlayer = $this->getCurrentPlayer($request);

        $type = $this->getValue(static::FIELD_NAME_EXTRACTOR_TYPE, $request);
        $number = (int) $this->getValue(static::FIELD_NAME_EXTRACTOR_NUMBER, $request);

        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context(static::FIELD_NAME_EXTRACTOR_TYPE)
            ->isRequired()
            ->isIn(static::FIELD_NAME_EXTRACTOR_TYPE_VALUES);

        $validator->context(static::FIELD_NAME_EXTRACTOR_NUMBER)
            ->isRequired()
            ->isInt()
            ->isLessThan(($currentPlayer->calculateMaxExtractorConstruction() + 1));

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        if ($type === static::FIELD_NAME_EXTRACTOR_TYPE_METAL) {
            $currentPlayer->buildMetalExtractors($number);
        } else if ($type === static::FIELD_NAME_EXTRACTOR_TYPE_CRYSTAL) {
            $currentPlayer->buildCrystalExtractors($number);
        }

        $this->getFlashBag()->addSuccess('resources.extractor.build.success');

        $this->flush();

        return $this->redirectJson(PlayerResourcesHandler::NAME);
    }
}