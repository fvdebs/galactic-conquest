<?php

namespace Inferno\DoctrineProxies\__CG__\GC\Universe\Model;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Universe extends \GC\Universe\Model\Universe implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'universeId', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'name', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'description', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksStartingAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'lastTickAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'tickInterval', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'lastRankingAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'rankingInterval', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'tickCurrent', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksAttack', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksDefense', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksDefenseAlliance', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksDefenseAllied', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxTicksMissionOffensive', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxTicksMissionDefensive', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxPrivateGalaxyPlayers', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxPublicGalaxyPlayers', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxAllianceGalaxies', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorMetalIncome', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorCrystalIncome', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorStartCost', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorPoints', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'resourcePointsDivider', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'isActive', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'isClosed', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'closedAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'isRegistrationAllowed', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'galaxies', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'alliances', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'units', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'technologies', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'factions'];
        }

        return ['__isInitialized__', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'universeId', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'name', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'description', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksStartingAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'lastTickAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'tickInterval', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'lastRankingAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'rankingInterval', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'tickCurrent', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksAttack', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksDefense', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksDefenseAlliance', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'ticksDefenseAllied', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxTicksMissionOffensive', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxTicksMissionDefensive', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxPrivateGalaxyPlayers', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxPublicGalaxyPlayers', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'maxAllianceGalaxies', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorMetalIncome', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorCrystalIncome', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorStartCost', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'extractorPoints', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'resourcePointsDivider', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'isActive', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'isClosed', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'closedAt', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'isRegistrationAllowed', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'galaxies', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'alliances', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'units', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'technologies', '' . "\0" . 'GC\\Universe\\Model\\Universe' . "\0" . 'factions'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Universe $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getUniverseId(): int
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getUniverseId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUniverseId', []);

        return parent::getUniverseId();
    }

    /**
     * {@inheritDoc}
     */
    public function isClosed(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isClosed', []);

        return parent::isClosed();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsClosed(bool $isClosed): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsClosed', [$isClosed]);

        parent::setIsClosed($isClosed);
    }

    /**
     * {@inheritDoc}
     */
    public function getClosedAt(): \DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClosedAt', []);

        return parent::getClosedAt();
    }

    /**
     * {@inheritDoc}
     */
    public function setClosedAt(?\DateTime $closedAt): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClosedAt', [$closedAt]);

        parent::setClosedAt($closedAt);
    }

    /**
     * {@inheritDoc}
     */
    public function isRegistrationAllowed(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isRegistrationAllowed', []);

        return parent::isRegistrationAllowed();
    }

    /**
     * {@inheritDoc}
     */
    public function setIsRegistrationAllowed(bool $isRegistrationAllowed): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIsRegistrationAllowed', [$isRegistrationAllowed]);

        parent::setIsRegistrationAllowed($isRegistrationAllowed);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxAllianceGalaxies(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaxAllianceGalaxies', []);

        return parent::getMaxAllianceGalaxies();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaxAllianceGalaxies(int $maxAllianceGalaxies): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaxAllianceGalaxies', [$maxAllianceGalaxies]);

        parent::setMaxAllianceGalaxies($maxAllianceGalaxies);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastTickAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastTickAt', []);

        return parent::getLastTickAt();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastRankingAt(): ?\DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastRankingAt', []);

        return parent::getLastRankingAt();
    }

    /**
     * {@inheritDoc}
     */
    public function getRankingInterval(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRankingInterval', []);

        return parent::getRankingInterval();
    }

    /**
     * {@inheritDoc}
     */
    public function setRankingInterval(int $rankingInterval): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRankingInterval', [$rankingInterval]);

        parent::setRankingInterval($rankingInterval);
    }

    /**
     * {@inheritDoc}
     */
    public function getResourcePointsDivider(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getResourcePointsDivider', []);

        return parent::getResourcePointsDivider();
    }

    /**
     * {@inheritDoc}
     */
    public function getExtractorPoints(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExtractorPoints', []);

        return parent::getExtractorPoints();
    }

    /**
     * {@inheritDoc}
     */
    public function getExtractorStartCost(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExtractorStartCost', []);

        return parent::getExtractorStartCost();
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRouteName', []);

        return parent::getRouteName();
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', [$name]);

        parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', []);

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(string $description): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDescription', [$description]);

        parent::setDescription($description);
    }

    /**
     * {@inheritDoc}
     */
    public function getTicksStartingAt(): \DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTicksStartingAt', []);

        return parent::getTicksStartingAt();
    }

    /**
     * {@inheritDoc}
     */
    public function setTicksStartingAt(\DateTime $ticksStartingAt): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTicksStartingAt', [$ticksStartingAt]);

        parent::setTicksStartingAt($ticksStartingAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getTickInterval(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTickInterval', []);

        return parent::getTickInterval();
    }

    /**
     * {@inheritDoc}
     */
    public function setTickInterval(int $tickInterval): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTickInterval', [$tickInterval]);

        parent::setTickInterval($tickInterval);
    }

    /**
     * {@inheritDoc}
     */
    public function getTickCurrent(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTickCurrent', []);

        return parent::getTickCurrent();
    }

    /**
     * {@inheritDoc}
     */
    public function setTickCurrent(int $tickCurrent): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTickCurrent', [$tickCurrent]);

        parent::setTickCurrent($tickCurrent);
    }

    /**
     * {@inheritDoc}
     */
    public function getTicksAttack(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTicksAttack', []);

        return parent::getTicksAttack();
    }

    /**
     * {@inheritDoc}
     */
    public function setTicksAttack(int $ticksAttack): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTicksAttack', [$ticksAttack]);

        parent::setTicksAttack($ticksAttack);
    }

    /**
     * {@inheritDoc}
     */
    public function getTicksDefense(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTicksDefense', []);

        return parent::getTicksDefense();
    }

    /**
     * {@inheritDoc}
     */
    public function setTicksDefense(int $ticksDefense): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTicksDefense', [$ticksDefense]);

        parent::setTicksDefense($ticksDefense);
    }

    /**
     * {@inheritDoc}
     */
    public function getTicksDefenseAlliance(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTicksDefenseAlliance', []);

        return parent::getTicksDefenseAlliance();
    }

    /**
     * {@inheritDoc}
     */
    public function setTicksDefenseAlliance(int $ticksDefenseAlliance): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTicksDefenseAlliance', [$ticksDefenseAlliance]);

        parent::setTicksDefenseAlliance($ticksDefenseAlliance);
    }

    /**
     * {@inheritDoc}
     */
    public function getTicksDefenseAllied(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTicksDefenseAllied', []);

        return parent::getTicksDefenseAllied();
    }

    /**
     * {@inheritDoc}
     */
    public function setTicksDefenseAllied(int $ticksDefenseAllied): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTicksDefenseAllied', [$ticksDefenseAllied]);

        parent::setTicksDefenseAllied($ticksDefenseAllied);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxTicksMissionOffensive(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaxTicksMissionOffensive', []);

        return parent::getMaxTicksMissionOffensive();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaxTicksMissionOffensive(int $maxTicksMissionOffensive): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaxTicksMissionOffensive', [$maxTicksMissionOffensive]);

        parent::setMaxTicksMissionOffensive($maxTicksMissionOffensive);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxTicksMissionDefensive(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaxTicksMissionDefensive', []);

        return parent::getMaxTicksMissionDefensive();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaxTicksMissionDefensive(int $maxTicksMissionDefensive): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaxTicksMissionDefensive', [$maxTicksMissionDefensive]);

        parent::setMaxTicksMissionDefensive($maxTicksMissionDefensive);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxPrivateGalaxyPlayers(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaxPrivateGalaxyPlayers', []);

        return parent::getMaxPrivateGalaxyPlayers();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaxPrivateGalaxyPlayers(int $maxPrivateGalaxyPlayers): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaxPrivateGalaxyPlayers', [$maxPrivateGalaxyPlayers]);

        parent::setMaxPrivateGalaxyPlayers($maxPrivateGalaxyPlayers);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxPublicGalaxyPlayers(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaxPublicGalaxyPlayers', []);

        return parent::getMaxPublicGalaxyPlayers();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaxPublicGalaxyPlayers(int $maxPublicGalaxyPlayers): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaxPublicGalaxyPlayers', [$maxPublicGalaxyPlayers]);

        parent::setMaxPublicGalaxyPlayers($maxPublicGalaxyPlayers);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtractorMetalIncome(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExtractorMetalIncome', []);

        return parent::getExtractorMetalIncome();
    }

    /**
     * {@inheritDoc}
     */
    public function setExtractorMetalIncome(int $extractorMetalIncome): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setExtractorMetalIncome', [$extractorMetalIncome]);

        parent::setExtractorMetalIncome($extractorMetalIncome);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtractorCrystalIncome(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExtractorCrystalIncome', []);

        return parent::getExtractorCrystalIncome();
    }

    /**
     * {@inheritDoc}
     */
    public function setExtractorCrystalIncome(int $extractorCrystalIncome): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setExtractorCrystalIncome', [$extractorCrystalIncome]);

        parent::setExtractorCrystalIncome($extractorCrystalIncome);
    }

    /**
     * {@inheritDoc}
     */
    public function isActive(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isActive', []);

        return parent::isActive();
    }

    /**
     * {@inheritDoc}
     */
    public function activate(): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'activate', []);

        parent::activate();
    }

    /**
     * {@inheritDoc}
     */
    public function deactivate(): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'deactivate', []);

        parent::deactivate();
    }

    /**
     * {@inheritDoc}
     */
    public function createFaction(string $name, bool $isDefault = false): \GC\Faction\Model\Faction
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createFaction', [$name, $isDefault]);

        return parent::createFaction($name, $isDefault);
    }

    /**
     * {@inheritDoc}
     */
    public function getFactions(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFactions', []);

        return parent::getFactions();
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultFaction(): ?\GC\Faction\Model\Faction
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDefaultFaction', []);

        return parent::getDefaultFaction();
    }

    /**
     * {@inheritDoc}
     */
    public function hasDefaultFaction(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasDefaultFaction', []);

        return parent::hasDefaultFaction();
    }

    /**
     * {@inheritDoc}
     */
    public function createUnit(string $name, \GC\Faction\Model\Faction $faction, string $group): \GC\Unit\Model\Unit
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createUnit', [$name, $faction, $group]);

        return parent::createUnit($name, $faction, $group);
    }

    /**
     * {@inheritDoc}
     */
    public function createTechnology(string $name, \GC\Faction\Model\Faction $faction): \GC\Technology\Model\Technology
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createTechnology', [$name, $faction]);

        return parent::createTechnology($name, $faction);
    }

    /**
     * {@inheritDoc}
     */
    public function createPublicGalaxy(): \GC\Galaxy\Model\Galaxy
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createPublicGalaxy', []);

        return parent::createPublicGalaxy();
    }

    /**
     * {@inheritDoc}
     */
    public function createPrivateGalaxy(): \GC\Galaxy\Model\Galaxy
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createPrivateGalaxy', []);

        return parent::createPrivateGalaxy();
    }

    /**
     * {@inheritDoc}
     */
    public function getGalaxies(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGalaxies', []);

        return parent::getGalaxies();
    }

    /**
     * {@inheritDoc}
     */
    public function getPlayers(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPlayers', []);

        return parent::getPlayers();
    }

    /**
     * {@inheritDoc}
     */
    public function getPlayerCount(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPlayerCount', []);

        return parent::getPlayerCount();
    }

    /**
     * {@inheritDoc}
     */
    public function addAlliance(\GC\Alliance\Model\Alliance $alliance): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addAlliance', [$alliance]);

        parent::addAlliance($alliance);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlliances(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAlliances', []);

        return parent::getAlliances();
    }

    /**
     * {@inheritDoc}
     */
    public function getRandomPublicGalaxyWithFreeSpace(): ?\GC\Galaxy\Model\Galaxy
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRandomPublicGalaxyWithFreeSpace', []);

        return parent::getRandomPublicGalaxyWithFreeSpace();
    }

    /**
     * {@inheritDoc}
     */
    public function getNextFreeGalaxyNumber(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNextFreeGalaxyNumber', []);

        return parent::getNextFreeGalaxyNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateTicksPerDay(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateTicksPerDay', []);

        return parent::calculateTicksPerDay();
    }

    /**
     * {@inheritDoc}
     */
    public function isTickCalculationNeeded(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isTickCalculationNeeded', []);

        return parent::isTickCalculationNeeded();
    }

    /**
     * {@inheritDoc}
     */
    public function tick(): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'tick', []);

        parent::tick();
    }

    /**
     * {@inheritDoc}
     */
    public function generatePlayerRanking(): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'generatePlayerRanking', []);

        parent::generatePlayerRanking();
    }

    /**
     * {@inheritDoc}
     */
    public function isAllianceAndGalaxyRankingGenerationNeeded(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isAllianceAndGalaxyRankingGenerationNeeded', []);

        return parent::isAllianceAndGalaxyRankingGenerationNeeded();
    }

    /**
     * {@inheritDoc}
     */
    public function generateAllianceAndGalaxyRanking(): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'generateAllianceAndGalaxyRanking', []);

        parent::generateAllianceAndGalaxyRanking();
    }

    /**
     * {@inheritDoc}
     */
    public function getNextTick(): \DateTimeInterface
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNextTick', []);

        return parent::getNextTick();
    }

}
