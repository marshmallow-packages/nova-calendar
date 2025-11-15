<?php

namespace Marshmallow\NovaCalendar;

class Badge
{
    public $badge = '';
    public $tooltip = null;

    public function __construct(string $badge, ?string $tooltip = null)
    {
        $this->badge = $badge;
        $this->tooltip = $tooltip;
    }

    public function toArray(): array
    {
        return [
            'badge' => $this->badge,
            'tooltip' => $this->tooltip
        ];
    }

    public function __toString()
    {
        return $this->badge;
    }
}
