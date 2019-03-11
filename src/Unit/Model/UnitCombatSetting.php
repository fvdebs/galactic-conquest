<?php

declare(strict_types=1);

namespace GC\Unit\Model;

/**
 * @Table(name="unit_combat_setting")
 * @Entity
 */
class UnitCombatSetting
{
    /**
     * @var int
     *
     * @Column(name="unit_combat_id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $unitCombatId;

    /**
     * @var int
     *
     * @Column(name="distribution", type="integer", nullable=false)
     */
    private $distribution;

    /**
     * @var string
     *
     * @Column(name="attack_power", type="string", length=20, nullable=false)
     */
    private $attackPower;

    /**
     * @var \GC\Unit\Model\Unit
     *
     * @ManyToOne(targetEntity="GC\Unit\Model\Unit", inversedBy="unitCombatSettings")
     * @JoinColumn(name="source_unit_id", referencedColumnName="unit_id", nullable=false)
     */
    private $sourceUnit;

    /**
     * @var \GC\Unit\Model\Unit
     *
     * @ManyToOne(targetEntity="\GC\Unit\Model\Unit")
     * @JoinColumn(name="target_unit_id", referencedColumnName="unit_id", nullable=false)
     */
    private $targetUnit;

    /**
     * @param \GC\Unit\Model\Unit $sourceUnit
     * @param \GC\Unit\Model\Unit $targetUnit
     * @param int $distribution
     * @param string $attackPower
     */
    public function __construct(Unit $sourceUnit, Unit $targetUnit, int $distribution, string $attackPower)
    {
        $this->sourceUnit = $sourceUnit;
        $this->targetUnit = $targetUnit;
        $this->distribution = $distribution;
        $this->attackPower = $attackPower;
    }

    /**
     * @return int
     */
    public function getUnitCombatId(): int
    {
        return $this->unitCombatId;
    }

    /**
     * @return int
     */
    public function getDistribution(): int
    {
        return $this->distribution;
    }

    /**
     * @param int $distribution
     *
     * @return void
     */
    public function setDistribution(int $distribution): void
    {
        $this->distribution = $distribution;
    }

    /**
     * @return string
     */
    public function getAttackPower(): string
    {
        return $this->attackPower;
    }

    /**
     * @param string $attackPower
     *
     * @return void
     */
    public function setAttackPower(string $attackPower): void
    {
        $this->attackPower = $attackPower;
    }

    /**
     * @return \GC\Unit\Model\Unit
     */
    public function getSourceUnit(): Unit
    {
        return $this->sourceUnit;
    }

    /**
     * @param \GC\Unit\Model\Unit $sourceUnit
     *
     * @return void
     */
    public function setSourceUnit(Unit $sourceUnit): void
    {
        $this->sourceUnit = $sourceUnit;
    }

    /**
     * @return \GC\Unit\Model\Unit
     */
    public function getTargetUnit(): Unit
    {
        return $this->targetUnit;
    }

    /**
     * @param \GC\Unit\Model\Unit $targetUnit
     *
     * @return void
     */
    public function setTargetUnit(Unit $targetUnit): void
    {
        $this->targetUnit = $targetUnit;
    }
}