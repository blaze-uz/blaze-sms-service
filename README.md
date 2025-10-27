# Blaze SMS Service

Reusable SMS sending package for Laravel applications.

## Installation

### Via Composer (recommended)

```bash
composer require blaze/sms-service
```

Add the service provider to `config/app.php`:

```php
'providers' => [
    // ...
    Blaze\SmsService\SmsServiceProvider::class,
],
```

Or in Laravel 11+, the package will auto-register.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=sms-config
```

## Usage

```php
use Blaze\SmsService\Facades\SMS;
use App\SmsReceivers\Tenant\StudentSmsReceiver;

// Send SMS by action
SMS::sendByAction('payment_received', $receiver);

// Send SMS by template ID
SMS::sendByTemplateId($templateId, $receiver);
```

## License

MIT

