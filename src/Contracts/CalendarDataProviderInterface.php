<?php

namespace Marshmallow\NovaCalendar\Contracts;

use Marshmallow\NovaCalendar\View\AbstractView as View;

interface CalendarDataProviderInterface
{
    public function __construct();

    // Will be used for the browser window/tab title
    public function windowTitle(): string;

    // Will be displayed above the calendar
    public function titleForView(string $viewSpecifier): string;

    // A 1D array with the names of the seven days of the week, in order of display L -> R
    public function daysOfTheWeek(): array;

    // A multi-dimensional array of event styles, see documentation
    public function eventStyles(): array;

    // Allows the data provider to customize the data view just before sending it to the front-end
    public function customizeView(View $view): View;
}
