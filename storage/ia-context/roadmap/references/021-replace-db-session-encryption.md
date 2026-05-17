# Replace DB Session Encryption

## Goal

Replace deterministic AES-CBC session encryption with authenticated encryption for database-backed PHP sessions.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md`.
- Inspect `System\Session\DBHandler`, `system/includes/session_handlers.php`, `System\Config\Session`, session docs, `.env.example`, and available Composer dependencies/extensions.
- Do not read `.env`.

## Problem

`System\Session\DBHandler` encrypts session payloads with AES-256-CBC using an IV derived from the key and without authentication. This leaks repeated plaintext patterns and does not detect tampering.

## Implementation Plan

1. Prefer libsodium when available:

```php
sodium_crypto_secretbox()
sodium_crypto_secretbox_open()
```

2. If libsodium is not available, use OpenSSL with:
   - `random_bytes()` IV per payload;
   - AES-256-CBC or AES-256-GCM when available;
   - HMAC authentication for CBC payloads.
3. Enforce key material requirements for `SESSION_ENCRYPT_KEY`:
   - require a strong key when encryption is enabled;
   - document recommended length and generation method.
4. Store encrypted payloads with a versioned format, for example:

```text
v2:base64(nonce):base64(ciphertext):base64(mac)
```

5. Preserve backward compatibility only if explicitly safe:
   - decide whether old deterministic payloads should be readable during a transition;
   - if supported, re-encrypt old payloads on write;
   - if not supported, document that existing DB sessions must be cleared.
6. Keep encryption optional: if `SESSION_ENCRYPT_KEY` is empty, preserve current unencrypted DB session behavior.
7. Do not log plaintext session data or encryption keys.

## Documentation Updates

Update:

- `.env.example`;
- `README.md`;
- `README.pt-br.md`;
- `storage/ia-context/mvc.md`;
- `storage/ia-context/mvc-references/05-database-session-forms.md`;
- `storage/ia-context/mvc-references/09-errors-cautions.md`.

Document:

- how to enable DB session encryption;
- key requirements;
- whether old encrypted session rows must be cleared;
- that encryption protects stored session payloads, not application-level authorization.

## Tests And Verification

- Run `php -l` on changed PHP files.
- Add or run PHP checks for:
  - encryption round-trip;
  - tampered payload rejection;
  - different ciphertext for identical plaintext;
  - empty key keeps unencrypted behavior;
  - invalid key produces a translated/internal configuration error if implemented.
- If compatibility mode exists, test legacy payload read behavior.
- Validate documentation and use Playwright to open `/web-system`.

## Acceptance Criteria

- DB session encryption uses random nonces/IVs and authentication.
- Tampered encrypted session payloads fail closed.
- Existing unencrypted behavior remains available when no key is configured.
- Documentation clearly describes key setup and migration behavior.
- The corresponding README item is marked `[CONCLUDED]` only after implementation and verification.
