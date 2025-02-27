<?php

namespace Marshmallow\NovaCalendar\Filters;

class BeforeDate extends OnDate
{
    public function __construct(string $name, string $column)
    {
        parent::__construct($name, $column);
        $this->before();
    }
}
