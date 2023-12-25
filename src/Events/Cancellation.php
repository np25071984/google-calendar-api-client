<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient\Events;

use np25071984\GoogleCalendarClient\EventTypeEnum;
use np25071984\GoogleCalendarClient\Events\CalendarEventAbstract;

class Cancellation extends CalendarEventAbstract {
    protected string $id;

    function __construct(string $id) {
        $this->id = $id;
    }

    function getId(): string {
        return $this->id;
    }

    function getType(): EventTypeEnum {
        return EventTypeEnum::Cancellation;
    }
}