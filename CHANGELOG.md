# Changelog

## [Unreleased]

### Fixed

- The text of "Has to come directly after the step before" in the editor has the size and colour of the other text.

## [0.2.0] - 2026-11-21

### Added

- A step that is reached by a goal, an order or an abandoned cart shows the revenue of those conversions, in the funnel, in the table view and in the API as `funnel_revenue`. Data archived before this version has no revenue until the funnel is archived again.
- "Funnels documentation" under Administration > System tells how funnels count.
- Two example funnels under the list open the editor filled in.

### Fixed

- In the table view the last step shows "-" for went on, left and drop-off rate instead of 0.

## [0.1.0] - 2026-11-20

### Added

- Funnels with 2 to 10 steps, counted per visit from the data Matomo already has, with a page per funnel under Funnels.
- Step rules for the page URL, page title, events, downloads, outlinks and site search keywords, with several rules per step.
- Goals, ecommerce orders and abandoned carts as steps.
- Steps that only count directly after the step before.
- For each step, what the visits that left there did next.
- Average time between steps, conversion rate over time, and a table view that can be exported.
- An editor with a preview of the last 7 days, a test of which steps a URL matches and a segment the funnel is restricted to.
- Funnels for all websites, for super users.
- The commands `rebelfunnels:list` and `rebelfunnels:archive`.
- English and Swedish.
