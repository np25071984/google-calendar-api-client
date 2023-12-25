<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient\Events;

use np25071984\GoogleCalendarClient\EventDateTime;
use np25071984\GoogleCalendarClient\EventTypeEnum;

class Task extends Cancellation {
    protected string $summary;
    protected EventDateTime $eventDateTime;

    function __construct(
        string $id,
        string $summary,
        EventDateTime $eventDateTime,
    ) {
        parent::__construct($id);
        $this->id = $id;
        $this->summary = $summary;
        $this->eventDateTime = $eventDateTime;
    }

    function getType(): EventTypeEnum {
        return EventTypeEnum::Task;
    }

    function getSummary(): string {
        return $this->summary;
    }

    function getEventDatetime(): EventDateTime {
        return $this->eventDateTime;
    }
}