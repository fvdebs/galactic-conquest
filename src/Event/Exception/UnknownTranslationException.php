<?php

declare(strict_types=1);

namespace GC\Event\Exception;

use Exception;
use GC\Event\Model\Event;

class UnknownTranslationException extends Exception implements EventExceptionInterface
{
    /**
     * @param \GC\Event\Model\Event
     *
     * @return \GC\Event\Exception\UnknownTranslationException
     */
    public static function forEvent(Event $event): UnknownTranslationException
    {
        $message = sprintf('The translation key for the event with the type "%s" is missing.', $event->getType());

        return new static($message);
    }
}
