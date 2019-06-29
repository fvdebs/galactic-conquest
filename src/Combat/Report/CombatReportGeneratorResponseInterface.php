<?php

declare(strict_types=1);

namespace GC\Combat\Report;

use DateTime;

interface CombatReportGeneratorResponseInterface
{
    /**
     * @return string
     */
    public function getRenderedHtml(): string;

    /**
     * @return bool
     */
    public function isPointOfViewReportTypeOffensive(): bool;

    /**
     * @return string
     */
    public function getPointOfViewDisplayName(): string;

    /**
     * @return string
     */
    public function getTargetName(): string;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return string[]
     */
    public function getReportData(): array;
}
