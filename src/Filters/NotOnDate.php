<?php

namespace Marshmallow\NovaCalendar\Filters;

class NotOnDate extends OnDate
{
    public function __construct(string $name, string $column)
    {
        parent::__construct($name, $column);
        $this->notOn();
    }
}
