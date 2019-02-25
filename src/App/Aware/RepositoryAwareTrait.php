<?php

namespace GC\App\Aware;

use GC\Alliance\Model\AllianceRepository;
use GC\App\Dependency\SingletonContainer;
use GC\Combat\Model\CombatReportRepository;
use GC\Faction\Model\FactionRepository;
use GC\Galaxy\Model\GalaxyRepository;
use GC\Player\Model\PlayerRepository;
use GC\Progress\Model\ProgressRepository;
use GC\Scan\Model\ScanRepository;
use GC\Technology\Model\TechnologyRepository;
use GC\Unit\Model\UnitRepository;
use GC\Universe\Model\UniverseRepository;
use GC\User\Model\UserRepository;
use Pimple\Container;

trait RepositoryAwareTrait
{
    /**
     * @return \Pimple\Container
     */
    private function getContainer(): Container
    {
        return SingletonContainer::getContainer();
    }

    /**
     * @return \GC\Alliance\Model\AllianceRepository
     */
    protected function getAllianceRepository(): AllianceRepository
    {
        return $this->getContainer()->offsetGet(AllianceRepository::class);
    }

    /**
     * @return \GC\Combat\Model\CombatReportRepository
     */
    protected function getCombatReportRepository(): CombatReportRepository
    {
        return $this->getContainer()->offsetGet(CombatReportRepository::class);
    }

    /**
     * @return \GC\Faction\Model\FactionRepository
     */
    protected function getFactionRepository(): FactionRepository
    {
        return $this->getContainer()->offsetGet(FactionRepository::class);
    }

    /**
     * @return \GC\Galaxy\Model\GalaxyRepository
     */
    protected function getGalaxyRepository(): GalaxyRepository
    {
        return $this->getContainer()->offsetGet(GalaxyRepository::class);
    }

    /**
     * @return \GC\Player\Model\PlayerRepository
     */
    protected function getPlayerRepository(): PlayerRepository
    {
        return $this->getContainer()->offsetGet(PlayerRepository::class);
    }

    /**
     * @return \GC\Progress\Model\ProgressRepository
     */
    protected function getProgressRepository(): ProgressRepository
    {
        return $this->getContainer()->offsetGet(ProgressRepository::class);
    }

    /**
     * @return \GC\Scan\Model\ScanRepository
     */
    protected function getScanRepository(): ScanRepository
    {
        return $this->getContainer()->offsetGet(ScanRepository::class);
    }

    /**
     * @return \GC\Technology\Model\TechnologyRepository
     */
    protected function getTechnologyRepository(): TechnologyRepository
    {
        return $this->getContainer()->offsetGet(TechnologyRepository::class);
    }

    /**
     * @return \GC\Unit\Model\UnitRepository
     */
    protected function getUnitRepository(): UnitRepository
    {
        return $this->getContainer()->offsetGet(UnitRepository::class);
    }

    /**
     * @return \GC\Universe\Model\UniverseRepository
     */
    protected function getUniverseRepository(): UniverseRepository
    {
        return $this->getContainer()->offsetGet(UniverseRepository::class);
    }

    /**
     * @return \GC\User\Model\UserRepository
     */
    protected function getUserRepository(): UserRepository
    {
        return $this->getContainer()->offsetGet(UserRepository::class);
    }
}