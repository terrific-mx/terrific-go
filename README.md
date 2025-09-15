# Terrific SaaS Starter Kit

A personal, opinionated Laravel starter kit for building SaaS applications quickly and securely. This kit includes:

- **Laravel 12.x**
- **Stripe billing** via [Laravel Cashier](https://laravel.com/docs/billing) (one plan, subscription required for dashboard access)
- **Stripe Checkout** for new subscriptions
- **Billing portal** for subscribers
- **Flux UI Pro** components (paid license required)
- **Honeybadger** for error tracking

## Features

- User authentication and registration
- Enforced active subscription for dashboard access
- Simple billing system (single plan, easy to extend)
- Stripe Checkout integration for seamless payments
- Access to Stripe's billing portal for managing subscriptions
- Modern, beautiful UI with Flux UI Pro components
- Error monitoring with Honeybadger

## Requirements

- PHP >= 8.2
- Composer
- Node.js & npm
- [Flux UI Pro license](https://www.fluxui.com/pricing) (required for UI components)
- [Stripe account](https://dashboard.stripe.com/register)
- [Honeybadger account](https://www.honeybadger.io/)

## Getting Started

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/terrific-starter-kit.git
   cd terrific-starter-kit
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Copy and configure environment:**
   ```bash
   cp .env.example .env
   # Set your database, Stripe, Flux UI Pro, and Honeybadger credentials in .env
   ```

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate
   ```

6. **Start the development server:**
   ```bash
   php artisan serve
   ```

## Stripe Billing (Laravel Cashier)

- Uses [Laravel Cashier](https://laravel.com/docs/billing) for Stripe subscription management.
- Only one plan is configured by default (easy to extend).
- Users must have an active subscription to access the dashboard.
- Stripe Checkout is used for new subscriptions.
- Subscribers can access the Stripe billing portal to manage their subscription.

**Configuration:**
- Set your `STRIPE_KEY` and `STRIPE_SECRET` in `.env`.
- Update the plan ID in your billing configuration as needed.

## Flux UI Pro

- All UI components use [Flux UI Pro](https://www.fluxui.com/).
- **A paid Flux UI Pro license is required.**
- Set your Flux UI Pro credentials in `.env` as per the [Flux UI Pro documentation](https://www.fluxui.com/docs/pro/introduction).

## Honeybadger Error Tracking

- Integrated with [Honeybadger](https://www.honeybadger.io/) for error monitoring.
- Set your `HONEYBADGER_API_KEY` in `.env`.
- See the [Honeybadger Laravel docs](https://docs.honeybadger.io/lib/php/integration/laravel.html) for advanced configuration.

## Opinionated Structure

This starter kit is intentionally opinionated:
- Enforces subscription for all app access (except auth/billing)
- Uses Stripe exclusively for billing
- UI is built with Flux UI Pro (no fallback)
- Honeybadger is the only error tracking provider

Feel free to fork and adapt to your needs!

## License

This project is open source, but you must purchase your own Flux UI Pro and Honeybadger licenses to use those services.

---

**Happy hacking!**

---

> _Built by Oliver as a personal SaaS starter kit. PRs and suggestions welcome._
