<?php

namespace Marshmallow\NovaCalendar\EventFilter;

use Marshmallow\NovaCalendar\Event;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class EloquentCallbackFilter extends CallbackFilter
{
    public function showEvent(Event $event): bool
    {
        return ($event->model() instanceof EloquentModel) && $this->passesCallbackFilter($event);
    }
}
