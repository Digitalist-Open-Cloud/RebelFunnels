# Rebel Funnels (for Matomo)

[![Test plugin with Matomo](https://github.com/Digitalist-Open-Cloud/RebelFunnels/actions/workflows/matomo.yaml/badge.svg)](https://github.com/Digitalist-Open-Cloud/RebelFunnels/actions/workflows/matomo.yaml)
[![PHPCS check](https://github.com/Digitalist-Open-Cloud/RebelFunnels/actions/workflows/phpcs.yaml/badge.svg)](https://github.com/Digitalist-Open-Cloud/RebelFunnels/actions/workflows/phpcs.yaml)
[![Semgrep OSS scan](https://github.com/Digitalist-Open-Cloud/RebelFunnels/actions/workflows/semgrep.yaml/badge.svg)](https://github.com/Digitalist-Open-Cloud/RebelFunnels/actions/workflows/semgrep.yaml)

Funnels for Matomo: see how many visits get through a list of steps in order, and where they
leave. Funnels are counted from the data Matomo already has, so they need no tracking changes
and show the past as far back as Matomo re-archives reports.

## How a funnel counts

- A funnel has 2 to 10 steps, in order.
- A visit enters at the first step. It reaches a step only after the step before it, in the
  same visit. Other pages in between are fine.
- A step is reached by an action that matches any of its rules. A rule compares the page URL,
  page title, event category, action or name, download URL, outlink URL or site search keyword.
  It can be "is exactly", "contains", "starts with", "ends with" or a regular expression.
- A rule can also be "Goal converted": the step is reached when the visit converts a goal of
  the website, places an ecommerce order or abandons a cart. A conversion counts when it
  happens after the step before, also when the page of that step is what converts the goal.
  A funnel for all websites can use orders and carts, but no goals.
- Upper and lower case make no difference. URLs are compared without `https://` and `www.`.
  A regular expression is used as it is written, against the URL without them.
- A step can be set to count only when it comes directly after the step before: the very next
  action of the visit, of any kind.
- A funnel can be restricted to a segment, such as visits from one country.

Each funnel has a page under Funnels with:

- the steps as bars: visits that reached each step, how many went on, how many left and the
  average time to the next step. Click the number that left to see what those visits did
  next: the page, event, download, outlink or search that followed, or that they left the
  website. It can be switched to a table and exported.
- the conversion rate over time.

Both work with segments and date ranges, and can be added to dashboards.

## Install

Install it from the Matomo Marketplace, or put this repository in `plugins/RebelFunnels` and
activate it:

```sh
./console plugin:activate RebelFunnels
```

## Use

Funnels are created under Funnels > Manage funnels, by users with write access to the website.
The editor shows a preview of the last 7 days while you build, and tells which steps a URL you
paste matches. A super user can make a funnel for all websites.

Changing the rules, the order rule of a step or the segment of a funnel builds its data again.
Changing names does not.

## Archiving single funnels

Funnels are archived by Matomo's normal archiving. To archive single funnels right away:

```sh
./console rebelfunnels:list
./console rebelfunnels:archive
./console rebelfunnels:archive --idfunnel=3
./console rebelfunnels:archive --idsite=1
./console rebelfunnels:archive --idfunnel=3,4 --rearchive --date=2024-01-01,2024-03-31
```

Without `--idfunnel` all funnels are archived, or with `--idsite` all funnels of those
websites. Without `--rearchive` it archives what is waiting for the funnel, for example after
it was created or changed. With `--rearchive` the data is built again, for `--date` or, without
a date, as far back as Matomo re-archives reports by default. With `--date` only those dates
are archived, together with the week, month and year they belong to. See `--help` for all
options.

## Limits

A super user sets how many steps a funnel can have under Administration > System > General
settings, in the RebelFunnels section. A value in `config/config.ini.php` is used instead of
the setting, which is then hidden on that page:

```ini
[RebelFunnels]
; Steps a funnel can have. Each step is one query when a day is archived.
max_steps = 10
```

## API

`RebelFunnels.getSteps` returns the steps of a funnel and `RebelFunnels.getSummary` its
entries, conversions and conversion rate. Both take `idFunnel`. With the `idsubdatatable` of a
step as `idSubtable`, `getSteps` returns what the visits that left there did next. Funnels are managed with
`addFunnel`, `updateFunnel`, `deleteFunnel`, `getFunnels` and `setFunnelOrder`.

## License

GPL v3 or later, see [LICENSE.md](LICENSE.md).
