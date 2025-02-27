<?php

namespace Marshmallow\NovaCalendar\EventFilter;

use Laravel\Nova\Resource as NovaResource;
use Marshmallow\NovaCalendar\Event;

class NovaResourceFilter extends AbstractEventFilter
{
    protected $novaResourceClasses = [];

    public function __construct(string $label, $novaResourceClasses, $callbackFilter = null)
    {
        if (is_string($novaResourceClasses)) {
            $novaResourceClasses = [$novaResourceClasses];
        } else if (!is_array($novaResourceClasses)) {
            throw new \InvalidArgumentException("NovaResourceFilter constructor takes a single Nnova resource class as string or an array of them as second argument");
        }

        foreach ($novaResourceClasses as $novaResourceClass) {
            if (!is_subclass_of($novaResourceClass, NovaResource::class)) {
                throw new \Exception("A NovaResourceFilter can only filter Nova resources ($novaResourceClass is not a Nova resource)");
            }
        }

        parent::__construct($label);
        $this->novaResourceClasses = $novaResourceClasses;
        $this->setCallbackFilter($callbackFilter);
    }

    public function showEvent(Event $event): bool
    {
        foreach ($this->novaResourceClasses as $novaResourceClass) {
            if ($event->hasNovaResource($novaResourceClass)) {
                return $this->passesCallbackFilter($event);
            }
        }

        return false;
    }
}
