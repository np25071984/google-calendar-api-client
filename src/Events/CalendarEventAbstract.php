<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient\Events;

use np25071984\GoogleCalendarClient\EventTypeEnum;

abstract class CalendarEventAbstract
{
    abstract function getType(): EventTypeEnum;
}