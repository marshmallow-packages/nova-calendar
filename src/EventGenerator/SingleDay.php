<?php


namespace Marshmallow\NovaCalendar\EventGenerator;

use Illuminate\Support\Carbon;
use Marshmallow\NovaCalendar\Filters\AfterOrOnDate;
use Marshmallow\NovaCalendar\Filters\BeforeOrOnDate;


class SingleDay extends NovaEventGenerator
{
    public function generateEvents(Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $novaResourceClass = $this->novaResourceClass();
        $eloquentModelClass = $novaResourceClass::$model;
        $toEventSpec = $this->toEventSpec();

        if (!is_string($toEventSpec)) {
            throw new \Exception("Invalid toEventSpec: expected the name of a datetime attribute that starts the event as a single string");
        }

        $afterFilter = new AfterOrOnDate('', $toEventSpec);
        $beforeFilter = new BeforeOrOnDate('', $toEventSpec);

        // Since these are single-day events by definition, we only query for the models
        // that have the date attribute within the current calendar range
        $models = $eloquentModelClass::orderBy($toEventSpec);
        $models = $afterFilter->modulateQuery($models, $rangeStart);
        $models = $beforeFilter->modulateQuery($models, $rangeEnd);

        $out = [];
        foreach ($models->cursor() as $model) {
            $out[] = $this->resourceToEvent(new $novaResourceClass($model), $toEventSpec);
        }

        return $out;
    }
}
