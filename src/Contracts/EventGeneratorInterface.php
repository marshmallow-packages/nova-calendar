<?php

namespace Marshmallow\NovaCalendar\Contracts;

use Illuminate\Support\Carbon;

interface EventGeneratorInterface
{
    public function generateEvents(Carbon $rangeStart, Carbon $rangeEnd): array;
}
