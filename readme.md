# Laravel 6 Access Telemetry

.env settings
Days 180

```bash
PRUNE_ACCESS_LOGS_SECONDS=15552000 
```


Via Composer:

```bash
composer require waynebrummer/mail-telemetry
```

Publish the `config` file and `migration` files:

```bash
php artisan vendor:publish --provider="Pace\AccessTelemetry\ServiceProvider"
```

Run the migration:

```bash
php artisan migrate
```
Where to start:

Begin by extending adding the event to your login method in your controller. 
Thats about it for hooking it up.

```php
...
use Pace\AccessTelemetry\Events\RequestLoginEvent as LoginEvent;
...
event(new LoginEvent($credentials, request()->server()));
...
```
