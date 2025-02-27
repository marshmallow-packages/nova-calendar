<?php

namespace Marshmallow\NovaCalendar\EventGenerator;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Laravel\Nova\Resource as NovaResource;

use Marshmallow\NovaCalendar\Event;

abstract class Custom extends NovaEventGenerator
{
    abstract protected function modelQuery(EloquentBuilder $queryBuilder, Carbon $startOfCalendar, Carbon $endOfCalendar): EloquentBuilder;
    abstract protected function resourceToEvents(NovaResource $resource, Carbon $startOfCalendar, Carbon $endOfCalendar): array;

    public function generateEvents(Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $novaResourceClass = $this->novaResourceClass();
        $eloquentModelClass = $novaResourceClass::$model;
        $query = $this->modelQuery($eloquentModelClass::query(), $rangeStart, $rangeEnd);

        $out = [];
        foreach ($query->cursor() as $model) {
            $resource = new $novaResourceClass($model);
            foreach ($this->resourceToEvents($resource, $rangeStart, $rangeEnd) as $event) {
                if (!$event instanceof Event) {
                    throw new \Exception(get_class($this) . "::resourceToEvents() returned at least one value that is not a valid calendar Event");
                }

                $out[] = $event->withResource($resource);
            }
        }
        return $out;
    }
}
