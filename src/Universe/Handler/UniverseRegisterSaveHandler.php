<?php

declare(strict_types=1);

namespace GC\Universe\Handler;

use GC\App\Aware\GameAwareTrait;
use GC\App\Aware\RepositoryAwareTrait;
use GC\Player\Handler\PlayerOverviewHandler;
use GC\Player\Model\Player;
use GC\Universe\Exception\RegisterUniversePrivateGalaxyFullException;
use GC\Universe\Exception\RegisterUniversePrivateGalaxyPasswordFailedException;
use GC\Universe\Exception\RegisterUniversePublicGalaxiesFullException;
use GC\Universe\Model\Universe;
use GC\User\Model\User;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UniverseRegisterSaveHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;
    use GameAwareTrait;

    public const NAME = 'universe.register.save';

    private const GALAXY_TYPE_NEW = 'new';
    private const GALAXY_TYPE_PUBLIC = 'public';
    private const GALAXY_TYPE_PRIVATE = 'private';
    private const VALID_GALAXY_TYPE_VALUES = ['new', 'public', 'private'];
    private const FIELD_NAME_GALAXY_TYPE = 'galaxyType';
    private const FIELD_NAME_PASSWORD = 'password';
    private const FIELD_NAME_RULES = 'rules';


    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $this->getCurrentUser($request);
        $universe = $this->getCurrentUniverse($request);

        $galaxyType = (string) $this->getValue(static::FIELD_NAME_GALAXY_TYPE, $request);
        $password = (string) $this->getValue(static::FIELD_NAME_PASSWORD, $request);

        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context(static::FIELD_NAME_RULES)->isRequired();
        $validator->context(static::FIELD_NAME_GALAXY_TYPE)->isRequired()
            ->isIn(static::VALID_GALAXY_TYPE_VALUES);

        if ($user->hasPlayerIn($universe)) {
            $validator->addMessage(static::FIELD_NAME_RULES);
        }

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $player = null;
        try {
            if ($galaxyType === static::GALAXY_TYPE_PUBLIC) {
                $player = $this->joinPublicGalaxyAndCreateNewPlayer($user, $universe);
            }

            if ($galaxyType === static::GALAXY_TYPE_PRIVATE) {
                $player = $this->joinPrivateGalaxyAndCreateNewPlayer($user, $universe, $password);
            }

            if ($galaxyType === static::GALAXY_TYPE_NEW) {
                $player = $this->createNewGalaxyAndCreatePlayer($user, $universe);
            }
        } catch (RegisterUniversePrivateGalaxyPasswordFailedException $exception) {
            $validator->addMessage(static::FIELD_NAME_PASSWORD, 'universe.register.private.galaxy.not.found');
            return $this->failedValidation($validator);
        } catch (RegisterUniversePrivateGalaxyFullException $exception) {
            $validator->addMessage(static::FIELD_NAME_PASSWORD, 'universe.register.private.galaxy.full');
            return $this->failedValidation($validator);
        } catch (RegisterUniversePublicGalaxiesFullException $exception) {
            $validator->addMessage(static::FIELD_NAME_GALAXY_TYPE, 'universe.register.public.galaxy.full');
            return $this->failedValidation($validator);
        }

        if ($player === null) {
            $this->getFlashBag()->addError('universe.register.error.unknown');
            return $this->redirectJson(static::NAME);
        }

        $this->flush();

        $redirect =  $this->redirectJson(PlayerOverviewHandler::NAME, ['universe' => $universe->getRouteName()]);

        return $redirect;
    }

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Player\Model\Player
     */
    private function createNewGalaxyAndCreatePlayer(User $user, Universe $universe): Player
    {
        $galaxy = $universe->createPublicGalaxy();
        $player = $galaxy->createPlayer($user, $universe->getDefaultFaction());
        $player->grantCommanderRole();

        return $player;
    }

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Universe\Model\Universe $universe
     *
     * @throws \GC\Universe\Exception\RegisterUniversePublicGalaxiesFullException
     *
     * @return \GC\Player\Model\Player
     */
    private function joinPublicGalaxyAndCreateNewPlayer(User $user, Universe $universe): Player
    {
        $galaxy = $universe->getRandomPublicGalaxyWithFreeSpace();
        if ($galaxy === null) {
            throw RegisterUniversePublicGalaxiesFullException::forFull();
        }

        return $galaxy->createPlayer($user, $universe->getDefaultFaction());
    }

    /**
     * @param \GC\User\Model\User $user
     * @param \GC\Universe\Model\Universe $universe
     * @param string $password
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \GC\Universe\Exception\RegisterUniversePrivateGalaxyPasswordFailedException
     * @throws \GC\Universe\Exception\RegisterUniversePrivateGalaxyFullException
     *
     * @return \GC\Player\Model\Player
     */
    private function joinPrivateGalaxyAndCreateNewPlayer(User $user, Universe $universe, string $password): Player
    {
        $galaxy = $this->getGalaxyRepository()->findByPassword($password);
        if ($galaxy === null) {
            throw RegisterUniversePrivateGalaxyPasswordFailedException::forPassword($password);
        }

        if (!$galaxy->hasSpaceForNewPlayer()) {
            throw RegisterUniversePrivateGalaxyFullException::forGalaxy($galaxy);
        }

        return $galaxy->createPlayer($user, $universe->getDefaultFaction());
    }
}