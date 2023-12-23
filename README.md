# Google Calendar API client

This library allows to query Google Calendar events for given time period with ServiceAccount authorization (no OAuth2 is required).

## How to use

1. Create Google project
2. Enable Google Calendar API
3. Create Service Account
4. Share your calendar with the Service Account email
5. Use the library to query events

### Get list of events

```
use DateTimeImmutable;
use np25071984\GoogleCalendarClient\Calendar;
use np25071984\GoogleCalendarClient\EventDateTime;

require_once 'vendor/autoload.php';

$credentials = <PATH_TO_SERVICE_ACCOUNT_CREDENTIALS_FILE>;
$calendarId = <CALENDAR_ID>;

$calendar = new Calendar($credentials);
$eventDateTime = new EventDateTime(
    (new DateTimeImmutable("NOW"))->modify('-1 MONTHS'),
    new DateTimeImmutable("NOW"),
);
list($syncToken, $events) = $calendar->getEvents($calendarId, $eventDateTime);

/** @var Array<Event|Task|Cancellation> $events */
foreach ($events as $event) {
    echo $event->getId(), PHP_EOL;

    switch (true) {
        case $event instanceof Cancellation:
            echo "Cancellation", PHP_EOL;
            break;
        case $event instanceof Task:
            echo "Task", PHP_EOL;
            echo $event->getSummary(), PHP_EOL;
            echo $event->getEventDatetime()->getStartDate()->format("Y-m-d"), PHP_EOL;
            echo $event->getEventDatetime()->getEndDate()->format("Y-m-d"), PHP_EOL;
            break;
        case $event instanceof Event:
            echo "Event", PHP_EOL;
            echo $event->getSummary(), PHP_EOL;
            echo $event->getEventDatetime()->getStartDate()->format("Y-m-d H:m:s"), PHP_EOL;
            echo $event->getEventDatetime()->getEndDate()->format("Y-m-d H:m:s"), PHP_EOL;
            break;
        default:
            throw new Exception("Unknown event type");
    }

    echo PHP_EOL;
}

echo "syncToken: ", $syncToken, PHP_EOL;
```

### Get event updates

Use `$syncToken` obtained from `getEvents` method.

```
list($syncToken, $events) = $calendar->getUpdates($calendarId, $syncToken);
```