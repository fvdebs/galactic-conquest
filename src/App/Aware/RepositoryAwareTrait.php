<?php

namespace GC\App\Aware;

use Doctrine\Common\Persistence\ObjectRepository;
use GC\Alliance\Model\Alliance;
use GC\Alliance\Model\AllianceRepository;
use GC\Faction\Model\Faction;
use GC\Faction\Model\FactionRepository;
use GC\Galaxy\Model\Galaxy;
use GC\Galaxy\Model\GalaxyRepository;
use GC\Player\Model\Player;
use GC\Player\Model\PlayerRepository;
use GC\Scan\Model\Scan;
use GC\Scan\Model\ScanRepository;
use GC\Technology\Model\Technology;
use GC\Technology\Model\TechnologyRepository;
use GC\Unit\Model\Unit;
use GC\Unit\Model\UnitRepository;
use GC\Universe\Model\Universe;
use GC\Universe\Model\UniverseRepository;
use GC\User\Model\User;
use GC\User\Model\UserRepository;

/**
 * ATTENTION: Use this class carefully. This is bad practice but speeds up development a bit.
 * Use entity methods or inject repositories to your handlers instead.
 */
trait RepositoryAwareTrait
{
    /**
     * @param string $entityClassPath
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getDoctrineRepository(string $entityClassPath): ObjectRepository
    {
        return $this->getDoctrine()->getRepository($entityClassPath);
    }

    /**
     * @return \GC\Alliance\Model\AllianceRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getAllianceRepository(): AllianceRepository
    {
        return $this->getDoctrineRepository(Alliance::class);
    }

    /**
     * @return \GC\Faction\Model\FactionRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getFactionRepository(): FactionRepository
    {
        return $this->getDoctrineRepository(Faction::class);
    }

    /**
     * @return \GC\Galaxy\Model\GalaxyRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getGalaxyRepository(): GalaxyRepository
    {
        return $this->getDoctrineRepository(Galaxy::class);
    }

    /**
     * @return \GC\Player\Model\PlayerRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getPlayerRepository(): PlayerRepository
    {
        return $this->getDoctrineRepository(Player::class);
    }

    /**
     * @return \GC\Scan\Model\ScanRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getScanRepository(): ScanRepository
    {
        return $this->getDoctrineRepository(Scan::class);
    }

    /**
     * @return \GC\Technology\Model\TechnologyRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getTechnologyRepository(): TechnologyRepository
    {
        return $this->getDoctrineRepository(Technology::class);
    }

    /**
     * @return \GC\Unit\Model\UnitRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getUnitRepository(): UnitRepository
    {
        return $this->getDoctrineRepository(Unit::class);
    }

    /**
     * @return \GC\Universe\Model\UniverseRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getUniverseRepository(): UniverseRepository
    {
        return $this->getDoctrineRepository(Universe::class);
    }

    /**
     * @return \GC\User\Model\UserRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getUserRepository(): UserRepository
    {
        return $this->getDoctrineRepository(User::class);
    }
}