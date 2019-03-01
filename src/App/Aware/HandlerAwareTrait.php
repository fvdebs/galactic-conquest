<?php

namespace GC\App\Aware;

use Doctrine\ORM\EntityManager;
use GC\App\Dependency\SingletonContainer;
use Inferno\Routing\Router\RouterChain;
use Inferno\Session\Bag\AttributeBagInterface;
use Inferno\Session\Bag\FlashBagInterface;
use Inferno\Session\Manager\SessionManagerInterface;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * ATTENTION: Use this trait carefully. This is bad practice but speeds up development significantly.
 * inject everything instead through your handlers.
 */
trait HandlerAwareTrait
{
    use GameAwareTrait;

    /**
     * @return \Pimple\Container
     */
    protected function getContainer(): Container
    {
        return SingletonContainer::getContainer()->offsetGet(EntityManager::class);
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->getContainer()->offsetGet(LoggerInterface::class);
    }

    /**
     * @return \Inferno\Session\Bag\FlashBagInterface
     */
    protected function getFlashBag(): FlashBagInterface
    {
        return $this->getContainer()->offsetGet(SessionManagerInterface::class)->getFlashBag();
    }

    /**
     * @return \Inferno\Session\Bag\AttributeBagInterface
     */
    protected function getAttributeBag(): AttributeBagInterface
    {
        return $this->getContainer()->offsetGet(SessionManagerInterface::class)->getAttributeBag();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getDoctrine(): EntityManager
    {
        return $this->getContainer()->offsetGet(EntityManager::class);
    }

    /**
     * @param object|null $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return void
     */
    protected function flush(?object $entity = null): void
    {
        $this->getDoctrine()->flush($entity);
    }

    /**
     * @param object $entity
     *
     * @throws \Doctrine\ORM\ORMException
     *
     * @return void
     */
    protected function persist(object $entity): void
    {
        $this->getDoctrine()->persist($entity);
    }

    /**
     * @param string $path
     * @param string[] $placeholders
     * @param int $code
     * @param string[] $headers
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function render(string $path, array $placeholders = [], int $code = 200, array $headers = []): ResponseInterface
    {
        $content = $this->getContainer()->offsetGet('renderer')->render($path, $placeholders);

        return $this->getContainer()->offsetGet('response-factory')->createFromContent($content, $code, $headers);
    }

    /**
     * @param string $name
     * @param string[] $parameters
     * @param int $code
     * @param string[] $headers
     *
     * @throws \Inferno\Routing\Exception\ResourceNotFoundException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function redirect(string $name, array $parameters = [], int $code = 302, array $headers = []): ResponseInterface
    {
        $uri = $this->getContainer()->offsetGet('uri-factory')->createUri(
            $this->getContainer()->offsetGet(RouterChain::class)->generate($name, $parameters)
        );

        return $this->getContainer()->offsetGet('response-factory')->createFromContent($uri, $code, $headers);
    }
}