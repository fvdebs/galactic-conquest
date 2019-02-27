<?php

namespace GC\App\Aware;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use GC\App\Dependency\SingletonContainer;

trait DoctrineAwareTrait
{
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getDoctrine(): EntityManager
    {
        return SingletonContainer::getContainer()->offsetGet(EntityManager::class);
    }

    /**
     * @param string $entityClassPath
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository(string $entityClassPath): ObjectRepository
    {
        return $this->getDoctrine()->getRepository($entityClassPath);
    }

    /**
     * @param object $entity
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
     * @return void
     */
    protected function persist(object $entity): void
    {
        $this->getDoctrine()->persist($entity);
    }
}