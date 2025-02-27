<?php

namespace Marshmallow\NovaCalendar\EventGenerator;

use Illuminate\Support\Carbon;
use Marshmallow\NovaCalendar\Filters\AfterOrOnDate;
use Marshmallow\NovaCalendar\Filters\BeforeOrOnDate;

class MultiDay extends NovaEventGenerator
{
    public function generateEvents(Carbon $rangeStart, Carbon $rangeEnd): array
    {
        $novaResourceClass = $this->novaResourceClass();
        $eloquentModelClass = $novaResourceClass::$model;
        $toEventSpec = $this->toEventSpec();

        if (!is_array($toEventSpec) || 2 != count($toEventSpec) || !is_string($toEventSpec[0]) || !is_string($toEventSpec[1])) {
            throw new \Exception("Invalid toEventSpec: expected the names of two datetime attributes that start and end the event as an array of exactly two strings");
        }

        $dateAttributeStart = $toEventSpec[0];
        $dateAttributeEnd = $toEventSpec[1];

        $afterFilter = new AfterOrOnDate('', $dateAttributeEnd);
        $beforeFilter = new BeforeOrOnDate('', $dateAttributeStart);

        // Since multi-day events are to be included, we have to query for
        // all models..
        $models = $eloquentModelClass::orderBy($dateAttributeStart);
        // a) that start before the end of the calendar,
        $models = $beforeFilter->modulateQuery($models, $rangeEnd);
        // and that..
        $models = $models->where(function ($query) use ($dateAttributeStart, $dateAttributeEnd, $rangeStart, $rangeEnd) {
            //    b) EITHER don't have an end date AND start on or after the calendar start
            $query->where(function ($query) use ($dateAttributeStart, $dateAttributeEnd, $rangeStart, $rangeEnd) {
                $query->whereNull($dateAttributeEnd)
                    ->whereDate($dateAttributeStart, '>=', $rangeStart);
                //    c) OR have an end date that lies on or after the start of the calendar
            })->orWhere(function ($query) use ($dateAttributeStart, $dateAttributeEnd, $rangeStart, $rangeEnd) {
                $query->whereDate($dateAttributeEnd, '>=', $rangeStart);
            });
        });

        $out = [];
        foreach ($models->cursor() as $model) {
            $out[] = $this->resourceToEvent(new $novaResourceClass($model), $dateAttributeStart, $dateAttributeEnd);
        }

        return $out;
    }
}
