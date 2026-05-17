# Harden Site URL Host Handling

## Goal

Prevent generated absolute URLs from trusting spoofable request headers by default.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md`.
- Inspect `System\Core\Path`, `System\Config\Globals`, `.env.example`, root READMEs, URL/helper docs, and any tests or scripts used for URL behavior.
- Do not read `.env`.

## Problem

`Path::siteURL()` currently uses `HTTP_X_FORWARDED_PROTO` and `HTTP_X_FORWARDED_HOST` when request server values are present. In direct deployments, clients can send these headers and poison generated absolute URLs or redirects.

## Implementation Plan

1. Change `Path::siteURL()` so the default behavior ignores forwarded headers.
2. Add an explicit trusted-proxy configuration contract before forwarded headers can be used. Recommended environment variables:

```dotenv
# Trust X-Forwarded-* headers only when the request comes from these proxy IPs.
# Leave blank to ignore forwarded headers.
TRUSTED_PROXIES=
```

3. Treat `TRUSTED_PROXIES` as a comma-separated list of IPs or CIDR ranges.
4. Use forwarded protocol/host only when `REMOTE_ADDR` matches a trusted proxy.
5. Validate the final host:
   - reject control characters and path separators;
   - preserve valid host and optional port;
   - fallback to `HTTP_HOST`, `SERVER_NAME`, or `localhost` when no trusted forwarded host exists.
6. Keep `BASE_PATH` behavior unchanged.
7. Consider adding a small helper/private method in `Path` for host normalization if it keeps `siteURL()` readable.

## Documentation Updates

Update:

- `.env.example`;
- `README.md`;
- `README.pt-br.md`;
- `storage/ia-context/mvc.md`;
- `storage/ia-context/mvc-references/02-configuration-routes-urls.md`;
- `system/views/pages/home.php` and language docs if URL behavior is documented there.

Document that forwarded headers are ignored unless the proxy is explicitly trusted.

## Tests And Verification

- Run `php -l` on changed PHP files.
- Run PHP checks that simulate `$_SERVER` values:
  - direct request with `HTTP_HOST`;
  - spoofed `HTTP_X_FORWARDED_HOST` without trusted proxy;
  - trusted proxy with forwarded host/proto;
  - invalid forwarded host fallback;
  - `BASE_PATH` preserved.
- Render `/web-system` and verify URL helper docs still load.
- Use Playwright to open `/web-system`.

## Acceptance Criteria

- Untrusted `X-Forwarded-*` headers do not affect `siteURL()`.
- Trusted proxy configuration enables forwarded host/proto only for trusted proxy addresses.
- `BASE_PATH` and normal local development URLs remain backward compatible.
- Documentation explains the proxy behavior and environment setting.
- The corresponding README item is marked `[CONCLUDED]` only after implementation and verification.
