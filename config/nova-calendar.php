<?php

use App\Providers\CalendarDataProvider;

return [

  'my-calendar' => [

    /*
      * The class of the calendar data provider for this Nova Calendar
        Don't forget to add the proper use statement above.
        This key is required.
      */
    'dataProvider' => CalendarDataProvider::class,

    /*
         * URI for this Nova Calendar (will be appended to the Nova path, /nova by default)
           This key is required.
         */
    'uri' => 'my-calendar',

    /*
         * Browser window/tab title for this Nova Calendar.
           This key is optional.
           If you remove it or set it to an empty string, the dynamic title displayed above
           the calendar view will be used as window/tab title in the browser.
         */
    'windowTitle' => 'Nova Calendar',

  ],

  // 'calendar2' => [
  //     'uri' => 'marshmallow/second-calendar',
  //     'dataProvider' => SecondCalendarDataProvider::class
  //     'windowTitle' => 'Second Calendar',
  // ],

];
