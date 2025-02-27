<?php

namespace Marshmallow\NovaCalendar\Contracts;

use Laravel\Nova\Http\Requests\NovaRequest;

interface ViewInterface
{
    public function specifier(): string;
    public function initFromRequest(NovaRequest $request);
    public function viewData(CalendarDataProviderInterface $dataProvider): array;

    public function calendarData(NovaRequest $request, CalendarDataProviderInterface $dataProvider): array;
    public function defaultStyles(): array;
    public function firstDayOfWeek(): int;
    public function updateViewRanges(CalendarDataProviderInterface $dataProvider): void;
}
