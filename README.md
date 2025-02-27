<h1 align="center">Event calendar for Laravel Nova 5</h1>

<p align="center">An event calendar that displays Nova resources or other time-related data in your Nova 5 project on a monthly calendar view that adapts nicely to clear and dark mode.</p>

![The design of the calendar in both clear and dark mode](https://github.com/marshmallow-packages/nova-calendar/blob/main/resources/doc/screenshot.jpg?raw=true)

> [!important]
> This package was originally forked from [wdelfuego/nova-calendar](https://github.com/wdelfuego/nova-calendar). Since we were making many opinionated changes, we decided to continue development in our own version rather than submitting pull requests that might not benefit all users of the original package. You're welcome to use this package, we're actively maintaining it. If you encounter any issues, please don't hesitate to reach out.

# Installation

```sh
composer require marshmallow/nova-calendar
```

For help implementing and using the calendar, take a look at the [documentation](https://wdelfuego.github.io/nova-calendar).

# License summary

Anyone can use and modify this package in any way they want, including commercially, as long as the commercial use is a) creating implemented calendar views and/or b) using the implemented calendar views.
Basically the only condition is that you can't sublicense the package or embed it in a framework (unless you do so under the AGPLv3 license).
Usage in Nova is not compatible with the AGPLv3 license. More details [below](#license).

# What can it do?

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
