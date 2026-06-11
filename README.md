<h1 align="center">Event calendar for Laravel Nova 5</h1>

<p align="center">An event calendar that displays Nova resources or other time-related data in your Nova 5 project on a monthly calendar view that adapts nicely to clear and dark mode.</p>

<p align="center">
<a href="https://packagist.org/packages/marshmallow/nova-calendar"><img src="https://img.shields.io/packagist/v/marshmallow/nova-calendar.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://packagist.org/packages/marshmallow/nova-calendar"><img src="https://img.shields.io/packagist/dt/marshmallow/nova-calendar.svg?style=flat-square" alt="Total Downloads"></a>
<a href="LICENSE.md"><img src="https://img.shields.io/packagist/l/marshmallow/nova-calendar.svg?style=flat-square" alt="License"></a>
</p>

![The design of the calendar in both clear and dark mode](https://github.com/marshmallow-packages/nova-calendar/blob/main/resources/doc/screenshot.jpg?raw=true)

> [!important]
> This package was originally forked from [wdelfuego/nova-calendar](https://github.com/wdelfuego/nova-calendar). Since we were making many opinionated changes, we decided to continue development in our own version rather than submitting pull requests that might not benefit all users of the original package. You're welcome to use this package, we're actively maintaining it. If you encounter any issues, please don't hesitate to reach out.

## What can it do?

This calendar tool for Nova 5 shows existing Nova resources and, if you want, dynamically generated events, but comes without database migrations or Eloquent models itself. This is considered a feature. Your project is expected to already contain certain Nova resources for Eloquent models with `DateTime` fields or some other source of time-related data that can be used to generate the calendar events displayed to the end user.

The following features are supported:

-   Automatically display Nova resources on a monthly calendar view
-   Mix multiple types of Nova resources on the same calendar
-   Display events that are not related to Nova resources
-   Use event filters to limit the amount of events shown on the calendar
-   Add badges to events and calendar days to indicate status or attract attention
-   Customize visual style and content of each individual event
-   Laravel policies are respected to exclude events from the calendar automatically
-   Allows end users to navigate through the calendar with hotkeys
-   Allows end users to navigate to the resources' Detail or Edit views by clicking events

## Requirements

-   PHP `^8.2`
-   [Laravel Nova](https://nova.laravel.com) `^5.0`
-   `illuminate/support` `^10`, `^11`, `^12` or `^13`

## Installation

Install the package through Composer:

```sh
composer require marshmallow/nova-calendar
```

The package registers its `ToolServiceProvider` automatically through Laravel's package discovery, so no manual provider registration is needed.

Publish the config file:

```sh
php artisan vendor:publish --provider="Marshmallow\NovaCalendar\ToolServiceProvider" --tag="config"
```

This creates `config/nova-calendar.php` with a working starting configuration.

## Getting started

Adding a calendar to Nova takes a few steps. The full walkthrough lives in the [documentation](https://marshmallow-packages.github.io/nova-calendar/installation.html); the short version is below.

### 1. Create a data provider

A data provider supplies the events for a calendar. Generate the default one:

```sh
php artisan nova-calendar:create-default-calendar-data-provider
```

This creates `App\Providers\CalendarDataProvider`, a subclass of `Marshmallow\NovaCalendar\DataProvider\AbstractCalendarDataProvider`. Implement `novaResources()` to map Nova resources to the `DateTime` attribute(s) that define when each event starts and, optionally, ends:

```php
namespace App\Providers;

use App\Nova\SomeEvent;
use App\Nova\User;
use Marshmallow\NovaCalendar\DataProvider\AbstractCalendarDataProvider;

class CalendarDataProvider extends AbstractCalendarDataProvider
{
    public function novaResources(): array
    {
        return [
            // Single-day event on the model's `created_at` timestamp:
            User::class => 'created_at',

            // Event with a start and (optionally nullable) end timestamp:
            SomeEvent::class => ['starts_at', 'ends_at'],
        ];
    }
}
```

Any attribute named here must be cast to a `DateTime` by the underlying Eloquent model. Returning an empty array yields a working but empty calendar.

### 2. Register the calendar tool

Add the calendar to the `tools()` method of your `App\Providers\NovaServiceProvider`, passing the calendar key from the config file:

```php
use Marshmallow\NovaCalendar\NovaCalendar;

public function tools(): array
{
    return [
        new NovaCalendar('my-calendar'),
    ];
}
```

If you build your Nova main menu manually, link to the calendar with `NovaCalendar::pathToCalendar()`:

```php
use Laravel\Nova\Menu\MenuItem;
use Marshmallow\NovaCalendar\NovaCalendar;

MenuItem::link(__('Calendar'), NovaCalendar::pathToCalendar('my-calendar'));
```

## Configuration

Most calendar configuration is done at runtime from your data provider, but each calendar instance needs an entry in `config/nova-calendar.php`, keyed by a unique _calendar key_. Each entry supports the following options:

| Option         | Required | Description                                                                                                                                                  |
| -------------- | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `dataProvider` | yes      | The data provider class that supplies the event data for this calendar.                                                                                       |
| `uri`          | yes      | URI the calendar is served under, appended to the Nova path. With `my-calendar` and Nova on `/nova`, the calendar lives at `/nova/my-calendar`. Must be unique. |
| `windowTitle`  | no       | Fixed browser tab title. If omitted or empty, the dynamic title generated by the data provider is used instead.                                               |

Multiple calendars are configured by adding more keyed entries:

```php
use App\Calendar\Providers\BirthdayDataProvider;
use App\Calendar\Providers\FlightDataProvider;

return [
    'flights' => [
        'dataProvider' => FlightDataProvider::class,
        'uri' => 'calendar/flights',
    ],
    'birthdays' => [
        'dataProvider' => BirthdayDataProvider::class,
        'uri' => 'calendar/birthdays',
        'windowTitle' => 'Birthdays',
    ],
];
```

See [Adding more calendar views](https://marshmallow-packages.github.io/nova-calendar/adding-more-calendar-views.html) for details.

## Documentation

Full documentation — including event filters, badges, event visibility, custom event generators, customizing events and the calendar, and the upgrade guide — is available at [marshmallow-packages.github.io/nova-calendar](https://marshmallow-packages.github.io/nova-calendar) and in the [`docs/`](docs) directory.

## License summary

Anyone can use and modify this package in any way they want, including commercially, as long as the commercial use is a) creating implemented calendar views and/or b) using the implemented calendar views.
Basically the only condition is that you can't sublicense the package or embed it in a framework (unless you do so under the AGPLv3 license).
Usage in Nova is not compatible with the AGPLv3 license. More details in [LICENSE.md](LICENSE.md).

## Contributing

Contributions are welcome. Please see the [contributing guide](docs/contributing-to-this-package.md) and open a pull request against the `develop` branch. Bug reports and feature requests can be filed on the [issue tracker](https://github.com/marshmallow-packages/nova-calendar/issues).

## Security

If you discover a security vulnerability, please email [security@marshmallow.dev](mailto:security@marshmallow.dev) rather than using the public issue tracker.

## Credits

-   [Marshmallow](https://github.com/marshmallow-packages)
-   [wdelfuego](https://github.com/wdelfuego) — original author of the package this was forked from
-   [All contributors](https://github.com/marshmallow-packages/nova-calendar/contributors)
