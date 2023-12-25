# Google Calendar API client

This library allows to query Google Calendar events for given time period with ServiceAccount authorization (no OAuth2 is required).

## How to use

1. Create a Google Cloud project [link](https://developers.google.com/workspace/guides/create-project)
2. Enable Google Calendar API [link](https://developers.google.com/workspace/guides/enable-apis#google-cloud-console)
3. Create a Service Account [link](https://developers.google.com/workspace/guides/create-credentials#create_a_service_account)
4. Download credentials for the service account [link](https://developers.google.com/workspace/guides/create-credentials#create_credentials_for_a_service_account)
5. Share your calendar with the Service Account email [link](https://support.google.com/calendar/answer/37082?hl=en)
6. Use the library to query events

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

/** @var Array<CalendarEventAbstract> $events */
foreach ($events as $event) {
    echo $event->getId(), PHP_EOL;

    switch ($event->getType()) {
        case EventTypeEnum::Cancellation:
            echo "Cancellation", PHP_EOL;
            break;
        case EventTypeEnum::Task:
            echo "Task", PHP_EOL;
            echo $event->getSummary(), PHP_EOL;
            echo $event->getEventDatetime()->getStartDate()->format("Y-m-d"), PHP_EOL;
            echo $event->getEventDatetime()->getEndDate()->format("Y-m-d"), PHP_EOL;
            break;
        case EventTypeEnum::Event:
            echo "Event", PHP_EOL;
            echo $event->getSummary(), PHP_EOL;
            echo $event->getEventDatetime()->getStartDate()->format("Y-m-d H:m:s"), PHP_EOL;
            echo $event->getEventDatetime()->getEndDate()->format("Y-m-d H:m:s"), PHP_EOL;
            foreach ($event->getAttendees() as $attendee) {
                echo $attendee, PHP_EOL;
            }
            echo $event->getLocation(), PHP_EOL;
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