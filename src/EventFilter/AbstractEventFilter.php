<?php

namespace Marshmallow\NovaCalendar\EventFilter;

use Marshmallow\NovaCalendar\Contracts\EventFilterInterface;
use Marshmallow\NovaCalendar\Event;

abstract class AbstractEventFilter implements EventFilterInterface
{
    private string $key = '';
    private string $label = '';
    private bool $isDefault = false;
    private $callbackFilter = null;

    abstract public function showEvent(Event $event): bool;

    public function __construct(string $label)
    {
        $this->label = $label;
        $this->setKey(md5(implode('-', [get_called_class(), $label])));
    }

    public function getKey(): string
    {
        if (!strlen(trim($this->key))) {
            throw new \Exception("Event filter has no filter key");
        }

        return $this->key ?? '';
    }

    public function hasKey(string $filterKey): bool
    {
        return $this->getKey() == $filterKey;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function useAsDefaultFilter(bool $set = true): self
    {
        $this->isDefault = $set;
        return $this;
    }

    public function isDefaultFilter(): bool
    {
        return $this->isDefault;
    }

    public function setCallbackFilter($callable)
    {
        $this->callbackFilter = $callable;
    }

    protected function setKey(string $filterKey): void
    {
        $this->key = $filterKey;
    }

    protected function passesCallbackFilter(Event $event): bool
    {
        if ($this->callbackFilter) {
            return ($this->callbackFilter)($event);
        }

        return true;
    }
}
