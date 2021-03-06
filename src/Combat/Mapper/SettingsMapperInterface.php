<?php

declare(strict_types=1);

namespace GC\Combat\Mapper;

use GC\Combat\Model\SettingsInterface;
use GC\Universe\Model\Universe;

interface SettingsMapperInterface
{
    /**
     * @param \GC\Universe\Model\Universe $universe
     *
     * @return \GC\Combat\Model\SettingsInterface
     */
    public function mapFrom(Universe $universe): SettingsInterface;
}
