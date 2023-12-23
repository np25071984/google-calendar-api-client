<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient\Events;

use np25071984\GoogleCalendarClient\EventDateTime;

class Cancellation {
    private string $id;

    function __construct(string $id) {
        $this->id = $id;
    }

    function getId(): string {
        return $this->id;
    }
}