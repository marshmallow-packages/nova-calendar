<?php

use Illuminate\Support\Facades\Route;
use Marshmallow\NovaCalendar\Http\Controllers\CalendarController;

Route::get('/{view}/', [CalendarController::class, 'getCalendarData']);
