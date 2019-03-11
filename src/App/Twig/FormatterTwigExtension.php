<?php

declare(strict_types=1);

namespace GC\App\Twig;

use DateTimeInterface;
use Symfony\Component\Translation\Translator;
use Twig\Extension\AbstractExtension;

final class FormatterTwigExtension extends AbstractExtension
{
    /**
     * @var \Symfony\Component\Translation\Translator
     */
    private $translator;

    /**
     * @param \Symfony\Component\Translation\Translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter('date', [$this, 'formatDate']),
            new \Twig_Filter('time', [$this, 'formatTime']),
            new \Twig_Filter('datetime', [$this, 'formatDateTime']),
            new \Twig_Filter('humandatetime', [$this, 'formatHumanDateTime']),
            new \Twig_Filter('humanbool', [$this, 'formatHumanBool']),
            new \Twig_Filter('number', [$this, 'formatNumber']),
        ];
    }

    /**
     * shut the fuck up. just format by locale
     *
     * @return bool
     */
    private function isGerman(): bool
    {
        return $this->translator->getLocale() === 'de-DE';
    }

    /**
     * @param \DateTimeInterface|null $dateTime
     * @param string $format
     *
     * @return string
     */
    private function format(?DateTimeInterface $dateTime, string $format): string
    {
        if ($dateTime === null) {
            return $this->translator->trans('app.unknown');
        }

        return $dateTime->format($format);
    }

    /**
     * @param DateTimeInterface|null $dateTime
     *
     * @return string
     */
    public function formatDate(?DateTimeInterface $dateTime): string
    {
        if ($this->isGerman()) {
            return $this->format($dateTime, 'd.m.Y');
        }

        return $this->format($dateTime, 'l jS \of F');
    }

    /**
     * @param DateTimeInterface|null $dateTime
     *
     * @return string
     */
    public function formatTime(?DateTimeInterface $dateTime): string
    {
        return $this->format($dateTime, 'H:i:s');
    }

    /**
     * @param DateTimeInterface|null $dateTime
     *
     * @return string
     */
    public function formatDateTime(?DateTimeInterface $dateTime): string
    {
        if ($this->isGerman()) {
            return $this->format($dateTime, 'd.m.Y H:i:s');
        }

        return $this->format($dateTime, 'l jS \of F H:i:s');
    }

    /**
     * @param mixed|null $boolean
     *
     * @return string
     */
    public function formatHumanBool($boolean): string
    {
        $boolean = (bool) $boolean;
        if ($boolean) {
            return $this->translator->trans('app.yes');
        }

        return $this->translator->trans('app.no');
    }

    /**
     * @param mixed|null $number
     *
     * @return string
     */
    public function formatNumber($number): string
    {
        if ($number === null) {
            return '0';
        }

        return (string) \number_format($number, 0, ',', '.');
    }
}