# Improve Example Page Visuals

## Goal

Improve the visual quality of the existing example pages while preserving the framework's simple PHP MVC style.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/templates/template.php`
  - `app/views/pages/home.php` or `system/views/pages/home.php`
  - `app/views/pages/user-profile.php`
  - `languages/doc/en.json`
  - `languages/doc/pt-br.json`
  - `languages/pages/users/en.json`
  - `languages/pages/users/pt-br.json`

## Current Example Pages

The currently available example pages are:

- the documentation home page;
- the user profile page at `app/views/pages/user-profile.php`;
- the shared template at `app/views/templates/template.php`.

If the documentation home is moved into `system/` by another task, apply the same visual improvement rules to the new system home view instead of the old app copy.

## Implementation Plan

1. Improve the shared template styling so example pages look cleaner and more intentional.
2. Keep the template lightweight and framework-agnostic:
   - no external CSS framework dependency;
   - no build step;
   - no JavaScript dependency for basic layout.
3. Improve spacing, typography, code blocks, details/summary states, links, and responsive behavior.
4. Make the documentation home easier to scan:
   - better section spacing;
   - clearer method cards/details;
   - more readable code examples;
   - visible hierarchy between class and method sections.
5. Improve the user profile example page:
   - present user fields in a small profile panel or table-like layout;
   - keep escaped output with `htmlspecialchars`;
   - keep translated labels through `lg()`.
6. Preserve existing helper usage and template render logic.
7. Keep all public asset paths compatible with `BASE_PATH` by using existing path helpers if assets are added.

## Visual Constraints

- Do not convert the project into a frontend framework.
- Do not introduce Bootstrap, Tailwind, Vue, React, or a bundler for this task.
- Keep cards and containers modest; this is framework documentation, not a marketing page.
- Ensure text and code blocks remain readable on mobile screens.
- Use semantic HTML where it fits the current structure.

## Acceptance Criteria

- Existing example pages look cleaner on desktop and mobile.
- The documentation home remains usable as dynamic framework documentation.
- The user profile page keeps translated labels and escaped user data.
- The default PHP template still renders PHP pages and raw HTML as before.
- No route, controller, helper, or bootstrap behavior changes are introduced by this visual-only task.
