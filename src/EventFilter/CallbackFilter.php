<?php

namespace Marshmallow\NovaCalendar\EventFilter;

use Marshmallow\NovaCalendar\Event;

class CallbackFilter extends AbstractEventFilter
{
    public function __construct(string $label, $callback)
    {
        parent::__construct($label);
        $this->setCallbackFilter($callback);
    }

    public function showEvent(Event $event): bool
    {
        return $this->passesCallbackFilter($event);
    }
}
