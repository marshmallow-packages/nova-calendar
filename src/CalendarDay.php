<?php

namespace Marshmallow\NovaCalendar;

use DateTimeInterface;
use Marshmallow\NovaCalendar\NovaCalendar;
use Marshmallow\NovaCalendar\Contracts\CalendarDayInterface;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarDay implements CalendarDayInterface
{
    public static function forDateInYearAndMonth(Carbon $date, int $year, int $month, ?int $firstDayOfWeek = null): self
    {
        $firstDayOfWeek = $firstDayOfWeek ?? NovaCalendar::MONDAY;

        return new self(
            $date->copy()->setTime(0, 0),
            self::weekdayColumn($date, $firstDayOfWeek),
            $date->format('j'),
            $date->year == $year && $date->month == $month,
            $date->isToday(),
            $date->isWeekend(),
        );
    }

    public static function weekdayColumn(Carbon $date, int $firstDayOfWeek = 1): int
    {
        $absDay = $date->dayOfWeekIso;
        $mod = ($absDay - $firstDayOfWeek) % 7;
        while ($mod < 0) {
            $mod += 7;
        }
        return $mod + 1;
    }

    public $start;
    protected $weekdayColumn;
    protected $label;
    protected $badges;
    protected $isWithinRange;
    protected $isToday;
    protected $isWeekend;
    protected $events;

    public function __construct(
        DateTimeInterface $start,
        int $weekdayColumn,
        string $label = '',
        bool $isWithinRange = true,
        bool $isToday = false,
        bool $isWeekend = false,
        array $events = []
    ) {
        $this->start = $start;
        $this->weekdayColumn = $weekdayColumn;
        $this->label = $label;
        $this->badges = [];
        $this->isWithinRange = $isWithinRange;
        $this->isToday = $isToday;
        $this->isWeekend = $isWeekend;
        $this->events = $events;
    }

    public function withEvents(array $events): self
    {
        $this->events = $events;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'weekdayColumn' => $this->weekdayColumn,
            'label' => $this->label,
            'badges' => $this->badgesToArray(),
            'isWithinRange' => $this->isWithinRange ? 1 : 0,
            'isToday' => $this->isToday ? 1 : 0,
            'isWeekend' => $this->isWeekend ? 1 : 0,
            'eventsSingleDay' => $this->eventsSingleDay(),
            'eventsMultiDay' => $this->eventsMultiDay(),
        ];
    }

    private function eventsSingleDay(): array
    {
        return array_filter($this->events, fn($e): bool => !!$e['isSingleDayEvent']);
    }

    private function eventsMultiDay(): array
    {

        return array_filter($this->events, fn($e): bool => !$e['isSingleDayEvent']);
    }

    private function badgesToArray(): array
    {
        return array_map(fn($b): array => $b->toArray(), $this->badges);
    }

    public function badges(?array $v = null): array
    {
        if (!is_null($v)) {
            foreach ($v as $badge) {
                $this->addBadge($badge);
            }
        }

        return $this->badges;
    }

    public function addBadge(string $v, ?string $tooltip = null): self
    {
        $this->badges[] = new Badge($v, $tooltip);
        return $this;
    }

    public function addBadges(string ...$v): self
    {
        foreach ($v as $badge) {
            $this->addBadge($badge);
        }

        return $this;
    }

    public function removeBadge(string $v): self
    {
        return $this->removeBadges($v);
    }

    public function removeBadges(string ...$v): self
    {
        foreach ($v as $badge) {
            $this->badges = array_filter($this->badges, function ($b) use ($badge) {
                return $b->badge != $badge;
            });
        }
        return $this;
    }
}
