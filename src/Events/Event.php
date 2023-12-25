<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient\Events;

use np25071984\GoogleCalendarClient\EventDateTime;
use np25071984\GoogleCalendarClient\EventTypeEnum;

class Event extends Task {
    /** @var Array<string> $attendees */
    private array $attendees;
    private ?string $location;

    function __construct(
        string $id,
        string $summary,
        EventDateTime $eventDateTime,
        array $attendees,
        ?string $location,
    ) {
        parent::__construct($id, $summary, $eventDateTime);
        $this->attendees = $attendees;
        $this->location = $location;
    }

    function getType(): EventTypeEnum {
        return EventTypeEnum::Event;
    }

    function getAttendees(): array {
        return $this->attendees;
    }

    function getLocation(): ?string {
        return $this->location;
    }
}