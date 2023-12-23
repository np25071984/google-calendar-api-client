<?php

declare(strict_types = 1);

namespace np25071984\GoogleCalendarClient;

use DateTimeImmutable;
use DateTime;
use DateTimeZone;
use Google\Service\Calendar as GoogleCalendar;
use Google\Client as GoogleClient;
use np25071984\GoogleCalendarClient\Events\Event;
use np25071984\GoogleCalendarClient\Events\Task;
use np25071984\GoogleCalendarClient\Events\Cancellation;
use np25071984\GoogleCalendarClient\EventDateTime;

class Calendar {
    private const MAX_RESULT = 500;
    private GoogleCalendar $service;

    function __construct(string $credentialsPath) {
        $client = new GoogleClient();
        $client->setApplicationName("calendar");
        $client->addScope(GoogleCalendar::CALENDAR);
        $client->setAuthConfig($credentialsPath);

        $this->service = new GoogleCalendar($client);
    }

    /**
     * @return Array<string $syncToken, Array<Event>>
     */
    function getEvents(string $calendarId, EventDateTime $eventDateTime): array {
        $syncToken = null;
        $events = [];

        $optParams = array(
            'maxResults' => self::MAX_RESULT,
            'pageToken' => null,
            'singleEvents' => true,
            'timeMin' => $eventDateTime->getStartDate()->format(DateTime::RFC3339),
            'timeMax' => $eventDateTime->getEndDate()->format(DateTime::RFC3339),
            // 'syncToken' => "CKDhxqvEo4MDEKDhxqvEo4MDGAEgvLaUmgIovLaUmgI=",
        );

        do {
            try {
                $result = $this->service->events->listEvents($calendarId, $optParams);
            } catch (\Throwable $e) {
                echo $e->getMessage(), PHP_EOL;
                throw $e;
            }

            $pageToken = $result->getNextPageToken();
            $optParams['pageToken'] = $pageToken;

            $syncToken = $result->getNextSyncToken();

            foreach ($result->getItems() as $event) {
                if ($event->getStatus() === "cancelled") {
                    $events[] = new Cancellation($event->getId());
                } else {
                    $startDate = $event->getStart()->getDate();
                    if (is_null($startDate)) {
                        $startTZ = $event->getStart()->getTimeZone();
                        if (!is_null($startTZ)) {
                            $startTimeZone = new DateTimeZone($startTZ);
                        } else {
                            $startTimeZone = null;
                        }

                        $start = DateTimeImmutable::createFromFormat(
                            DateTimeImmutable::RFC3339,
                            $event->getStart()->getDateTime(),
                            $startTimeZone
                        );

                        $endTZ = $event->getEnd()->getTimeZone();
                        if (!is_null($endTZ)) {
                            $endTimeZone = new DateTimeZone($endTZ);
                        } else {
                            $endTimeZone = null;
                        }

                        $end = DateTimeImmutable::createFromFormat(
                            DateTimeImmutable::RFC3339,
                            $event->getEnd()->getDateTime(),
                            $endTimeZone
                        );

                        $eventDateTime = new EventDateTime($start, $end);

                        $events[] = new Event(
                            $event->getId(),
                            $event->getSummary(),
                            $eventDateTime,
                        );

                    } else {
                        $start = DateTimeImmutable::createFromFormat(
                            "Y-m-d",
                            $event->getStart()->getDate()
                        );

                        $end = DateTimeImmutable::createFromFormat(
                            "Y-m-d",
                            $event->getEnd()->getDate()
                        );

                        $eventDateTime = new EventDateTime($start, $end);

                        $events[] = new Task(
                            $event->getId(),
                            $event->getSummary(),
                            $eventDateTime,
                        );
                    }
                }
            }
        } while ($pageToken);

        return [$syncToken, $events];
    }

    function getUpdates(string $calendarId, string $syncToken): array {
        $newSyncToken = null;
        $events = [];

        $optParams = array(
            'maxResults' => self::MAX_RESULT,
            'pageToken' => null,
            'syncToken' => $syncToken,
        );

        do {
            try {
                $result = $this->service->events->listEvents($calendarId, $optParams);
            } catch (\Throwable $e) {
                echo $e->getMessage(), PHP_EOL;
                throw $e;
            }

            $pageToken = $result->getNextPageToken();
            $optParams['pageToken'] = $pageToken;

            $sTkn = $result->getNextSyncToken();
            if (!is_null($sTkn)) {
                $newSyncToken = $sTkn;
            }

            foreach ($result->getItems() as $event) {
                if ($event->getStatus() === "cancelled") {
                    $events[] = new Cancellation($event->getId());
                } else {
                    $startDate = $event->getStart()->getDate();
                    if (is_null($startDate)) {
                        $startTZ = $event->getStart()->getTimeZone();
                        if (!is_null($startTZ)) {
                            $startTimeZone = new DateTimeZone($startTZ);
                        } else {
                            $startTimeZone = null;
                        }

                        $start = DateTimeImmutable::createFromFormat(
                            DateTimeImmutable::RFC3339,
                            $event->getStart()->getDateTime(),
                            $startTimeZone
                        );

                        $endTZ = $event->getEnd()->getTimeZone();
                        if (!is_null($endTZ)) {
                            $endTimeZone = new DateTimeZone($endTZ);
                        } else {
                            $endTimeZone = null;
                        }

                        $end = DateTimeImmutable::createFromFormat(
                            DateTimeImmutable::RFC3339,
                            $event->getEnd()->getDateTime(),
                            $endTimeZone
                        );

                        $eventDateTime = new EventDateTime($start, $end);

                        $events[] = new Event(
                            $event->getId(),
                            $event->getSummary(),
                            $eventDateTime,
                        );
                    } else {
                        $start = DateTimeImmutable::createFromFormat(
                            "Y-m-d",
                            $event->getStart()->getDate()
                        );

                        $end = DateTimeImmutable::createFromFormat(
                            "Y-m-d",
                            $event->getEnd()->getDate()
                        );

                        $eventDateTime = new EventDateTime($start, $end);

                        $events[] = new Task(
                            $event->getId(),
                            $event->getSummary(),
                            $eventDateTime,
                        );
                    }
                }
            }
        } while ($pageToken);

        return [$newSyncToken, $events];
    }
}