<?php

namespace Marshmallow\NovaCalendar\Testing;

use Marshmallow\NovaCalendar\DataProvider\AbstractCalendarDataProvider;
use Marshmallow\NovaCalendar\Event;
use Marshmallow\NovaCalendar\Tests\Unit\CalendarDataProviderTest;
use Illuminate\Support\Carbon;

class CalendarDataProvider extends AbstractCalendarDataProvider
{
    public function novaResources(): array
    {
        return [];
    }

    public function appendEvent(Event $event)
    {
        $reflection = new \ReflectionProperty(AbstractCalendarDataProvider::class, 'allEvents');
        $reflection->setAccessible(true);
        $allEvents = $reflection->getValue($this);
        if (!is_array($allEvents)) {
            $allEvents = [];
        }
        $allEvents[] = $event;
        $reflection->setValue($this, $allEvents);
    }

    public function initialize(): void
    {
        parent::initialize();

        // See CalendarDataProviderTest::testMultiDayEventThatEndsOnDayZeroGitHubIssue56
        $this->appendEvent(new Event(
            CalendarDataProviderTest::GITHUB_ISSUE_56_EVENT_TITLE,
            Carbon::create(2023, 5, 10, 6, 0, 0), // start
            Carbon::create(2023, 5, 15, 0, 0, 0),  // end
        ));
    }
}
