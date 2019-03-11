<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use GC\Galaxy\Model\Galaxy;
use GC\Universe\Model\Universe;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @TODO
 */
final class UniverseRegisterSaveHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;
    use GameAwareTrait;

    public const NAME = 'universe.register.save';
    public const GALAXY_TYPE_NEW = 'new';
    public const GALAXY_TYPE_PUBLIC = 'public';
    public const GALAXY_TYPE_PRIVATE = 'private';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->getCurrentUser($request);
        $universe = $this->getCurrentUniverse($request);

        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context('galaxyType')->isRequired();
        $validator->context('rules')->isRequired();

        $galaxyType = (string) $this->getValue('galaxyType', $request);
        if (!$this->isValidGalaxyType($galaxyType)) {
            $validator->addMessage('galaxyType');
        }

        if ($user->hasPlayerIn($universe)) {
            $validator->addMessage('rules');
        }

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $galaxy = $this->createOrGetGalaxyBy($galaxyType, $universe);
        $player = $galaxy->createPlayer($user, $universe->getFactions()[0]);

        $this->flush();

        return $this->redirectJson(UniverseSelectHandler::NAME);
    }

    /**
     * @param string $galaxyType
     *
     * @return bool
     */
    private function isValidGalaxyType(string $galaxyType): bool
    {
        return \in_array($galaxyType, [
            static::GALAXY_TYPE_NEW,
            static::GALAXY_TYPE_PRIVATE,
            static::GALAXY_TYPE_PUBLIC
        ]);
    }

    /**
     * @param string $galaxyType
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Galaxy\Model\Galaxy|null
     */
    private function createOrGetGalaxyBy(string $galaxyType, Universe $universe): Galaxy
    {
        if ($galaxyType === static::GALAXY_TYPE_PRIVATE) {
            return $universe->createPublicGalaxy();
        }

        if ($galaxyType === static::GALAXY_TYPE_PUBLIC) {
            return $universe->getRandomPublicGalaxyWithFreeSpace();
        }

        return $universe->createPublicGalaxy();
    }
}