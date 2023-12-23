<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient\Events;

use np25071984\GoogleCalendarClient\EventDateTime;

class Task {
    private string $id;
    private string $summary;
    private EventDateTime $eventDateTime;

    function __construct(
        string $id,
        string $summary,
        EventDateTime $eventDateTime,
    ) {
        $this->id = $id;
        $this->summary = $summary;
        $this->eventDateTime = $eventDateTime;
    }

    function getId(): string {
        return $this->id;
    }

    function getSummary(): string {
        return $this->summary;
    }

    function getEventDatetime(): EventDateTime {
        return $this->eventDateTime;
    }
}