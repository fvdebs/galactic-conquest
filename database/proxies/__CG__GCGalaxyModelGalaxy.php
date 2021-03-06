<?php

namespace Inferno\DoctrineProxies\__CG__\GC\Galaxy\Model;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Galaxy extends \GC\Galaxy\Model\Galaxy implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'galaxyId', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'number', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'password', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'metal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'crystal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'metalPerTick', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'crystalPerTick', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'taxMetal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'taxCrystal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'extractorPoints', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'averagePoints', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'rankingPosition', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'alliance', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'galaxyTechnologies', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'players', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'universe'];
        }

        return ['__isInitialized__', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'galaxyId', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'number', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'password', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'metal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'crystal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'metalPerTick', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'crystalPerTick', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'taxMetal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'taxCrystal', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'extractorPoints', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'averagePoints', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'rankingPosition', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'alliance', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'galaxyTechnologies', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'players', '' . "\0" . 'GC\\Galaxy\\Model\\Galaxy' . "\0" . 'universe'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Galaxy $proxy) {
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
    public function getGalaxyId(): int
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getGalaxyId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGalaxyId', []);

        return parent::getGalaxyId();
    }

    /**
     * {@inheritDoc}
     */
    public function getAveragePoints(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAveragePoints', []);

        return parent::getAveragePoints();
    }

    /**
     * {@inheritDoc}
     */
    public function getNumber(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumber', []);

        return parent::getNumber();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumber(int $number): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumber', [$number]);

        parent::setNumber($number);
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', []);

        return parent::getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword(?string $password): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', [$password]);

        parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetal(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetal', []);

        return parent::getMetal();
    }

    /**
     * {@inheritDoc}
     */
    public function setMetal(int $metal): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMetal', [$metal]);

        parent::setMetal($metal);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetalPerTick(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetalPerTick', []);

        return parent::getMetalPerTick();
    }

    /**
     * {@inheritDoc}
     */
    public function getCrystalPerTick(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCrystalPerTick', []);

        return parent::getCrystalPerTick();
    }

    /**
     * {@inheritDoc}
     */
    public function getCrystal(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCrystal', []);

        return parent::getCrystal();
    }

    /**
     * {@inheritDoc}
     */
    public function setCrystal(int $crystal): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCrystal', [$crystal]);

        parent::setCrystal($crystal);
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxMetal(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTaxMetal', []);

        return parent::getTaxMetal();
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxMetal(int $taxMetal): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTaxMetal', [$taxMetal]);

        parent::setTaxMetal($taxMetal);
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxCrystal(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTaxCrystal', []);

        return parent::getTaxCrystal();
    }

    /**
     * {@inheritDoc}
     */
    public function setTaxCrystal(int $taxCrystal): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTaxCrystal', [$taxCrystal]);

        parent::setTaxCrystal($taxCrystal);
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
    public function setExtractorPoints(int $extractorPoints): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setExtractorPoints', [$extractorPoints]);

        parent::setExtractorPoints($extractorPoints);
    }

    /**
     * {@inheritDoc}
     */
    public function getRankingPosition(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRankingPosition', []);

        return parent::getRankingPosition();
    }

    /**
     * {@inheritDoc}
     */
    public function setRankingPosition(int $rankingPosition): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRankingPosition', [$rankingPosition]);

        parent::setRankingPosition($rankingPosition);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlliance(): ?\GC\Alliance\Model\Alliance
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAlliance', []);

        return parent::getAlliance();
    }

    /**
     * {@inheritDoc}
     */
    public function hasAlliance(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasAlliance', []);

        return parent::hasAlliance();
    }

    /**
     * {@inheritDoc}
     */
    public function setAlliance(?\GC\Alliance\Model\Alliance $alliance): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAlliance', [$alliance]);

        parent::setAlliance($alliance);
    }

    /**
     * {@inheritDoc}
     */
    public function getUniverse(): \GC\Universe\Model\Universe
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUniverse', []);

        return parent::getUniverse();
    }

    /**
     * {@inheritDoc}
     */
    public function isPublicGalaxy(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isPublicGalaxy', []);

        return parent::isPublicGalaxy();
    }

    /**
     * {@inheritDoc}
     */
    public function isPrivateGalaxy(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isPrivateGalaxy', []);

        return parent::isPrivateGalaxy();
    }

    /**
     * {@inheritDoc}
     */
    public function generateGalaxyPassword(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'generateGalaxyPassword', []);

        return parent::generateGalaxyPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximumNumberOfPlayers(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaximumNumberOfPlayers', []);

        return parent::getMaximumNumberOfPlayers();
    }

    /**
     * {@inheritDoc}
     */
    public function hasSpaceForNewPlayer(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasSpaceForNewPlayer', []);

        return parent::hasSpaceForNewPlayer();
    }

    /**
     * {@inheritDoc}
     */
    public function createPlayer(\GC\User\Model\User $user, \GC\Faction\Model\Faction $faction): \GC\Player\Model\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createPlayer', [$user, $faction]);

        return parent::createPlayer($user, $faction);
    }

    /**
     * {@inheritDoc}
     */
    public function createAlliance(string $name, string $code): \GC\Alliance\Model\Alliance
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'createAlliance', [$name, $code]);

        return parent::createAlliance($name, $code);
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
    public function getNextFreeGalaxyPosition(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNextFreeGalaxyPosition', []);

        return parent::getNextFreeGalaxyPosition();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateTotalPlayerPoints(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateTotalPlayerPoints', []);

        return parent::calculateTotalPlayerPoints();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateAverageExtractors(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateAverageExtractors', []);

        return parent::calculateAverageExtractors();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateAverageMetalExtractors(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateAverageMetalExtractors', []);

        return parent::calculateAverageMetalExtractors();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateTotalMetalExtractors(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateTotalMetalExtractors', []);

        return parent::calculateTotalMetalExtractors();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateAverageCrystalExtractors(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateAverageCrystalExtractors', []);

        return parent::calculateAverageCrystalExtractors();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateTotalCrystalExtractors(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateTotalCrystalExtractors', []);

        return parent::calculateTotalCrystalExtractors();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateUnitsMovableAverageQuantity(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateUnitsMovableAverageQuantity', []);

        return parent::calculateUnitsMovableAverageQuantity();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateUnitsMovableTotalQuantity(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateUnitsMovableTotalQuantity', []);

        return parent::calculateUnitsMovableTotalQuantity();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateUnitsStationaryAverageQuantity(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateUnitsStationaryAverageQuantity', []);

        return parent::calculateUnitsStationaryAverageQuantity();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateUnitsStationaryTotalQuantity(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateUnitsStationaryTotalQuantity', []);

        return parent::calculateUnitsStationaryTotalQuantity();
    }

    /**
     * {@inheritDoc}
     */
    public function getCommander(): ?\GC\Player\Model\Player
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCommander', []);

        return parent::getCommander();
    }

    /**
     * {@inheritDoc}
     */
    public function hasResources(int $metal, int $crystal): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasResources', [$metal, $crystal]);

        return parent::hasResources($metal, $crystal);
    }

    /**
     * {@inheritDoc}
     */
    public function increaseMetal(int $number): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'increaseMetal', [$number]);

        parent::increaseMetal($number);
    }

    /**
     * {@inheritDoc}
     */
    public function decreaseMetal(int $number): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'decreaseMetal', [$number]);

        parent::decreaseMetal($number);
    }

    /**
     * {@inheritDoc}
     */
    public function increaseCrystal(int $number): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'increaseCrystal', [$number]);

        parent::increaseCrystal($number);
    }

    /**
     * {@inheritDoc}
     */
    public function decreaseCrystal(int $number): void
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'decreaseCrystal', [$number]);

        parent::decreaseCrystal($number);
    }

    /**
     * {@inheritDoc}
     */
    public function calculateMetalTaxFor(\GC\Player\Model\Player $player): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateMetalTaxFor', [$player]);

        return parent::calculateMetalTaxFor($player);
    }

    /**
     * {@inheritDoc}
     */
    public function calculateCrystalTaxFor(\GC\Player\Model\Player $player): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateCrystalTaxFor', [$player]);

        return parent::calculateCrystalTaxFor($player);
    }

    /**
     * {@inheritDoc}
     */
    public function calculateMetalIncomePerTick(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateMetalIncomePerTick', []);

        return parent::calculateMetalIncomePerTick();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateCrystalIncomePerTick(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateCrystalIncomePerTick', []);

        return parent::calculateCrystalIncomePerTick();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateMetalTaxPerTick(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateMetalTaxPerTick', []);

        return parent::calculateMetalTaxPerTick();
    }

    /**
     * {@inheritDoc}
     */
    public function calculateCrystalTaxPerTick(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'calculateCrystalTaxPerTick', []);

        return parent::calculateCrystalTaxPerTick();
    }

    /**
     * {@inheritDoc}
     */
    public function buildTechnology(\GC\Technology\Model\Technology $technology): \GC\Galaxy\Model\GalaxyTechnology
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'buildTechnology', [$technology]);

        return parent::buildTechnology($technology);
    }

    /**
     * {@inheritDoc}
     */
    public function hasTechnology(\GC\Technology\Model\Technology $technology): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasTechnology', [$technology]);

        return parent::hasTechnology($technology);
    }

    /**
     * {@inheritDoc}
     */
    public function hasTechnologyByFeatureKey(string $featureKey): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasTechnologyByFeatureKey', [$featureKey]);

        return parent::hasTechnologyByFeatureKey($featureKey);
    }

    /**
     * {@inheritDoc}
     */
    public function hasResourcesForTechnology(\GC\Technology\Model\Technology $technology): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasResourcesForTechnology', [$technology]);

        return parent::hasResourcesForTechnology($technology);
    }

    /**
     * {@inheritDoc}
     */
    public function hasTechnologyRequirementsFor(\GC\Technology\Model\Technology $technology): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasTechnologyRequirementsFor', [$technology]);

        return parent::hasTechnologyRequirementsFor($technology);
    }

    /**
     * {@inheritDoc}
     */
    public function isTechnologyInConstruction(\GC\Technology\Model\Technology $technology): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isTechnologyInConstruction', [$technology]);

        return parent::isTechnologyInConstruction($technology);
    }

    /**
     * {@inheritDoc}
     */
    public function getGalaxyTechnologies(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGalaxyTechnologies', []);

        return parent::getGalaxyTechnologies();
    }

    /**
     * {@inheritDoc}
     */
    public function getGalaxyTechnologiesCompleted(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGalaxyTechnologiesCompleted', []);

        return parent::getGalaxyTechnologiesCompleted();
    }

    /**
     * {@inheritDoc}
     */
    public function getGalaxyTechnologiesInConstruction(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGalaxyTechnologiesInConstruction', []);

        return parent::getGalaxyTechnologiesInConstruction();
    }

}
