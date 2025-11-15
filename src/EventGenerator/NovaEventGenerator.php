<?php

namespace Marshmallow\NovaCalendar\EventGenerator;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Laravel\Nova\Resource as NovaResource;
use Marshmallow\NovaCalendar\Contracts\EventGeneratorInterface;
use Marshmallow\NovaCalendar\Event;

abstract class NovaEventGenerator implements EventGeneratorInterface
{
    public static function from(string $novaResourceClass, $toEventSpec): ?EventGeneratorInterface
    {
        // Check validity of arguments
        if (!is_subclass_of($novaResourceClass, NovaResource::class)) {
            throw new \Exception("Only Nova resources can be automatically fetched for event generation ($novaResourceClass is not a Nova resource)");
        }

        $eloquentModelClass = $novaResourceClass::$model;
        if (!is_subclass_of($eloquentModelClass, EloquentModel::class)) {
            throw new \Exception("$eloquentModelClass is not an Eloquent model");
        }

        // Create appropriate EventGenerator
        if (is_string($toEventSpec) || (is_array($toEventSpec) && count($toEventSpec) == 1 && is_string($toEventSpec[0]))) {
            // If a single string is supplied as the toEventSpec, it is assumed to
            // be the name of a datetime attribute on the underlying Eloquent model
            // that will be used as the starting date/time for a single-day event
            // Support single attributes supplied in an array, too, since it's bound to happen
            return new SingleDay($novaResourceClass, is_array($toEventSpec) ? $toEventSpec[0] : $toEventSpec);
        } else if (is_array($toEventSpec) && count($toEventSpec) == 2 && is_string($toEventSpec[0]) && is_string($toEventSpec[1])) {
            // If an array containing two strings is supplied as the toEventSpec, they are assumed to
            // be the name of two datetime attributes on the underlying Eloquent model
            // that will be used as the start and end datetime for an event
            // that can be either single or multi-day (depending on the values of each model instance)
            return new MultiDay($novaResourceClass, $toEventSpec);
        } else if (is_object($toEventSpec) && $toEventSpec instanceof EventGeneratorInterface) {
            // If an event generator instance was supplied, simply use that but pre-set the nova resource class
            // so the developer doesn't have to specify it twice in the novaResources() method
            return $toEventSpec->withNovaResourceClass($novaResourceClass);
        }

        return null;
    }

    private $novaResourceClass = null;
    private $toEventSpec = null;

    public function __construct(?string $novaResourceClass = null, $toEventSpec = null)
    {
        $this->novaResourceClass = $novaResourceClass;
        $this->toEventSpec = $toEventSpec;
    }

    abstract public function generateEvents(Carbon $rangeStart, Carbon $rangeEnd): array;

    public function withNovaResourceClass(string $novaResourceClass)
    {
        $this->novaResourceClass = $novaResourceClass;
        return $this;
    }

    protected function novaResourceClass(): ?string
    {
        return $this->novaResourceClass;
    }

    protected function toEventSpec()
    {
        return $this->toEventSpec;
    }

    protected function resourceToEvent(NovaResource $resource, string $dateAttributeStart, ?string $dateAttributeEnd = null): Event
    {
        return Event::fromResource($resource, $dateAttributeStart, $dateAttributeEnd);
    }
}
