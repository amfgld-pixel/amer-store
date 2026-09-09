# Production Setup

## Required environment settings

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain.com`
- PostgreSQL production database values
- Redis/database cache, session and queue values supported by the host
- Mail provider values for order notifications
- Approved payment and courier credentials only

## Scheduler

Run every minute:

`php artisan schedule:run`

## Queue worker

Run a supervised worker:

`php artisan queue:work --sleep=3 --tries=3 --timeout=120`

## Writable directories

- `storage`
- `bootstrap/cache`

## Before live payments

1. Complete merchant onboarding.
2. Store secrets only in server environment variables.
3. Register HTTPS callback/webhook URLs.
4. Verify signatures and transaction amounts server-side.
5. Test success, failure, cancellation, timeout and refund flows.
6. Keep sandbox and production credentials separate.

## Before live courier booking

1. Obtain approved courier API access.
2. Test location mapping, weight, COD amount and delivery charges.
3. Verify consignment creation and tracking callbacks.
4. Document cancellation, return and failed-delivery procedures.

## Final business checks

- Company identity, phone, email and policies
- Product prices, variants, stock and images
- Bangladesh and international shipping zones
- Tax and currency configuration
- Cart, checkout and order confirmation
- Mobile, tablet and desktop layouts
- Analytics consent and production IDs
- Backup creation and restore test
- Admin roles and least-privilege permissions
