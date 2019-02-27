<?php

namespace GC\App\Aware;

use GC\Alliance\Model\Alliance;
use GC\Alliance\Model\AllianceRepository;
use GC\App\Dependency\SingletonContainer;
use GC\Combat\Model\CombatReportRepository;
use GC\Faction\Model\FactionRepository;
use GC\Galaxy\Model\GalaxyRepository;
use GC\Player\Model\PlayerRepository;
use GC\Scan\Model\ScanRepository;
use GC\Technology\Model\TechnologyRepository;
use GC\Unit\Model\UnitRepository;
use GC\Universe\Model\UniverseRepository;
use GC\User\Model\UserRepository;

trait RepositoryAwareTrait
{
    use DoctrineAwareTrait;

    /**
     * @return \GC\Alliance\Model\AllianceRepository
     */
    protected function getAllianceRepository(): AllianceRepository
    {
        return $this->getRepository(Alliance::class);
    }

    /**
     * @return \GC\Combat\Model\CombatReportRepository
     */
    protected function getCombatReportRepository(): CombatReportRepository
    {
        return SingletonContainer::getContainer()->offsetGet(CombatReportRepository::class);
    }

    /**
     * @return \GC\Faction\Model\FactionRepository
     */
    protected function getFactionRepository(): FactionRepository
    {
        return SingletonContainer::getContainer()->offsetGet(FactionRepository::class);
    }

    /**
     * @return \GC\Galaxy\Model\GalaxyRepository
     */
    protected function getGalaxyRepository(): GalaxyRepository
    {
        return SingletonContainer::getContainer()->offsetGet(GalaxyRepository::class);
    }

    /**
     * @return \GC\Player\Model\PlayerRepository
     */
    protected function getPlayerRepository(): PlayerRepository
    {
        return SingletonContainer::getContainer()->offsetGet(PlayerRepository::class);
    }

    /**
     * @return \GC\Scan\Model\ScanRepository
     */
    protected function getScanRepository(): ScanRepository
    {
        return SingletonContainer::getContainer()->offsetGet(ScanRepository::class);
    }

    /**
     * @return \GC\Technology\Model\TechnologyRepository
     */
    protected function getTechnologyRepository(): TechnologyRepository
    {
        return SingletonContainer::getContainer()->offsetGet(TechnologyRepository::class);
    }

    /**
     * @return \GC\Unit\Model\UnitRepository
     */
    protected function getUnitRepository(): UnitRepository
    {
        return SingletonContainer::getContainer()->offsetGet(UnitRepository::class);
    }

    /**
     * @return \GC\Universe\Model\UniverseRepository
     */
    protected function getUniverseRepository(): UniverseRepository
    {
        return SingletonContainer::getContainer()->offsetGet(UniverseRepository::class);
    }

    /**
     * @return \GC\User\Model\UserRepository
     */
    protected function getUserRepository(): UserRepository
    {
        return SingletonContainer::getContainer()->offsetGet(UserRepository::class);
    }
}