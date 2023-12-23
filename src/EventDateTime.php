<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient;

use DateTimeInterface;

class EventDateTime
{
    private DateTimeInterface $start;
    private DateTimeInterface $end;

    function __construct(DateTimeInterface $start, DateTimeInterface $end) {
        $this->start = $start;
        $this->end = $end;
    }

    function getStartDate(): DateTimeInterface {
        return $this->start;
    }

    function getEndDate(): DateTimeInterface {
        return $this->end;
    }
}