## Laravel Package to send Push notification

## Description
Send a push notification to user mobile, and web using FCM (Firebase Cloud Messaging).

### Installing via Composer

[Composer](http://getcomposer.org).

```bash
# Install Composer
composer require heyharpreetsingh/fcm
```

Next, Register the service provider in file `bootstrap/providers.php` for L11 AND `config/app.php` in the `providers` key for l10

```bash
# Register service provider
Heyharpreetsingh\FCM\Providers\FCMServiceProvider::class
```

### How to send a push notification on mobile (android, ios) and web.

use a `FCMFacade` to send a notification

```bash

use Heyharpreetsingh\FCM\Facades\FCMFacade;

FCMFacade::send([
   "message" => [
        "token":"bk3RNwTe3H0:CI2k_HHwgIpoDKCIZvvDMExUdFQ3P1...",
        "notification": [
                "body":"This is an FCM notification message!",
                "title":"FCM Message"
        ]
    ]
]);

```

You can pass additional data using the official doc of FCM.

    - https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages/send
    - https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#Message

If you have any issue or suggestion feel free to [contact](mailto:heyharpreetsingh@gmail.com "mail").

## License

open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
