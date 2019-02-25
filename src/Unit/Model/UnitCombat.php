<?php

declare(strict_types=1);

namespace GC\Unit\Model;

/**
 * @Table(name="unit_combat", indexes={@Index(name="fk-unit_combat-target_unit_id", columns={"target_unit_id"}), @Index(name="fk-unit_combat-source_unit_id", columns={"source_unit_id"})})
 * @Entity
 */
class UnitCombat
{
    /**
     * @var int
     *
     * @Column(name="unit_combat_id", type="bigint", nullable=false)
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
     * @var \Unit
     *
     * @ManyToOne(targetEntity="Unit")
     * @JoinColumns({
     *   @JoinColumn(name="source_unit_id", referencedColumnName="unit_id")
     * })
     */
    private $sourceUnit;

    /**
     * @var \Unit
     *
     * @ManyToOne(targetEntity="Unit")
     * @JoinColumns({
     *   @JoinColumn(name="target_unit_id", referencedColumnName="unit_id")
     * })
     */
    private $targetUnit;


}
