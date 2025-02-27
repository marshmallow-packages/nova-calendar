<?php

namespace Marshmallow\NovaCalendar\Contracts;

use Marshmallow\NovaCalendar\Event;

interface EventFilterInterface
{
    public function hasKey(string $filterKey): bool;
    public function showEvent(Event $event): bool;
}
