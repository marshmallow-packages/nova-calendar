<?php

namespace Marshmallow\NovaCalendar\EventFilter;

use Laravel\Nova\Resource as NovaResource;
use Marshmallow\NovaCalendar\Event;

class ExcludeNovaResourceFilter extends NovaResourceFilter
{
    public function showEvent(Event $event): bool
    {
        foreach ($this->novaResourceClasses as $novaResourceClass) {
            if ($event->hasNovaResource($novaResourceClass)) {
                return !$this->passesCallbackFilter($event);;
            }
        }

        return true;
    }
}
