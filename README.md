# Notifications

![GitHub release](https://img.shields.io/github/v/release/snebes/notifications)
[![GitHub license](https://img.shields.io/github/license/snebes/notifications)](https://github.com/snebes/notifications/blob/master/LICENSE)
![Scrutinizer build](https://img.shields.io/scrutinizer/build/g/snebes/notifications)
![Scrutinizer coverage](https://img.shields.io/scrutinizer/coverage/g/snebes/notifications)
![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/snebes/notifications?logo=scrutinizer)
![PHP](https://img.shields.io/travis/php-v/snebes/notifications)

`snebes/notifications` is an abstraction layer, inspired by [Laravel](https://laravel.com), which allows you to easily add support for email and web-interface messaging.

## Prerequisites

This bundle utilizes Symfony 3.4+ components as well as `SwiftMailer` to provide notifications.

## Installation

Add `snebes/notifications` to your `composer.json` file:

```shell script
composer require snebes/notifications
```

If you are looking for to use this component in a Symfony 3.4+ based application, the `snebes/notifications-bundle` can be installed to simplify this configuration. [Check out the bundle documentation.](https://github.com/snebes/notifications-bundle) 

## Setup / Bootstrap

The Notifications component is build around Symfony's Event Dispatcher, which is commonly used in the PHP ecosystem.

```php
use Symfony\Component\EventDispatcher\EventDispatcher;

$eventDispatcher = new EventDispatcher();
$notificationSender = new NotificationSender($eventDispatcher);
``` 

#### Notification Channels

Register the database channel to utilize web-interface notifications.
The database channel included in this component depends on Doctrine.

```php
$databaseChannel = new DatabaseChannel($doctrineEntityManager);
$eventDispatcher->addListener(NotificationEvents::SEND, [$databaseChannel, 'send']);
```

Register the mail channel to utilize email notifications.
The mail channel included in this component depends on SwiftMailer.

```php
$transport = new Swift_SmtpTransport('smtp.example.org', 25);
$mailer = new SwiftMailerMailer(new Swift_Mailer($transport));

$mailChannel = new MailChannel($mailer);
$eventDispatcher->addListener(NotificationEvents::SEND, [$mailChannel, 'send']);
``` 

## Sending Notifications

Notifications may be sent by the `NotificationSender` service.

```php
<?php

namespace App\Service;

use App\Entity\User;
use App\Notifications\OrderNotification;
use SN\Notifications\NotificationSender;

class UserService
{
    private $notificationSender;

    public function __construct(NotificationSender $notificationSender)
    {
        $this->notificationSender = $notificationSender;    
    }

    public function send(User $user)
    {
        $this->notificationSender->send($user, new OrderNotification());
    }
}
```

This component provides helpers to enable classes, such as the above `User` class, to accept notifications.
`NotifiableTrait` adds the ability for any class to receive a database or mail message.

To reference the database notifications from the entity, a `$notification` field will need to be configured for Doctrine to map the relationship.

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SN\Bundle\NotificationsBundle\Entity\Notification;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\NotifiableTrait;

/**
 * @ORM\Entity()
 */
class User implements NotifiableInterface
{
    use NotifiableTrait;

    /**
     * This field is used by the Mail channel.
     * 
     * @var string
     */
    private $email = 'demo@example.com';

    /**
     * This field is used by the SMS channel.
     * 
     * @var string
     */
    private $phoneNumber = '+1 555 555 5555';

    /**
     * @var Notification[]
     *
     * @ORM\ManyToMany(targetEntity=Notification::class)
     * @ORM\JoinTable(name="user_notifications")
    */
    private $notifications;
}
```

## Specifying Delivery Channels

Every notification class has a `via` method that determines on which channels the notification will be delivered. 
Notifications may be sent on the `mail` and `database` channels.

The `via` method receives a `NotifiableInterface` instance, which will be an instance of the class to which the notification is being sent.
You may use the `NotifiableInterface` to determine which channels the notification should be delivered on:

```php
/**
 * Get the notification's delivery channels.
 *
 * @param NotifiableInterface $notifiable
 *
 * @return array
 */
public function via(NotifiableInterface $notifiable): array
{
    return $notifiable->doNotSendEmail() ? ['database'] : ['database', 'mail'];
}
```

## Mail Notifications

If a notification supports being sent as an email, the class should implement `MailableInterface` and define a `toMail` method.
This method will receive a `NotifiableInterface` entity and should return a `SN\Notifications\Contracts\EmailInterface;` instance.
Let's take a look at an example `toMail` method:

```php
<?php

namespace App\Notification;

use SN\Notifications\Contracts\EmailInterface;
use SN\Notifications\Contracts\MailableInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;
use SN\Notifications\Email\Address;
use SN\Notifications\Email\Email;

class OrderNotification implements NotificationInterface, MailableInterface
{
    /**
     * Get the notification's delivery channels.
     *
     * @param NotifiableInterface $notifiable
     *
     * @return array
     */
    public function via(NotifiableInterface $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param NotifiableInterface $notifiable
     *
     * @return EmailInterface
     */
    public function toMail(NotifiableInterface $notifiable): EmailInterface
    {
        return (new Email())
            ->from(new Address('orders@example.com'))
            ->subject('Thank you for your order.')
            ->text('We are processing your order right now!');
    }
}
```

In this example, we created an email with a subject and text line.
These methods provided by the `Email` object make it simple and fast to format small transactional emails.
The mail channel will automatically fill in the `to` address for each `NotifiableInterface`.

#### Customizing The Recipient

When sending notifications via the `mail` channel, the notification system will automatically look for an `email` property on your `NotifiableInterface` entity.
You may customize which email address is used to deliver the notification by defining a `routeNotificationForMail` method on the entity:

```php
<?php

namespace App\Entity;

use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;use SN\Notifications\NotifiableTrait;

class User implements NotifiableInterface
{
    use NotifiableTrait;

    /**
     * Route notifications for the mail channel.
     *
     * @param NotificationInterface $notification
     *
     * @return string
     */
    public function routeNotificationForMail(NotificationInterface $notification)
    {
        return $this->getEmailAddress();
    }
}
```

## Database Notifications

#### Prerequisites

The `database` notification channel stores the notification information in a database table.
This table will contain information such as the notification type as well as custom JSON data that describes the notification.

You can query the table to display the notifications in your application's user interface.
But, before you can do that, you will need to create a database table to hold your notifications.
Depending on your applications setup, you will need to create table with this schema:

```sql
CREATE TABLE `sn_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `notifiable_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` datetime DEFAULT NULL,
  `data` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifiable_idx` (`notifiable_id`,`notifiable_type`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Formatting Database Notifications

If a notification supports being stored in a database table, the class should implement `ArrayableInterface` and define a `toArray` method. 
This method will receive a `NotifiableInterface` entity and should return a plain PHP array.
The returned array will be encoded as JSON and stored in the data column of your notifications table.
Let's take a look at an example `toArray` method:

```php
<?php

namespace App\Notification;

use SN\Notifications\Contracts\ArrayableInterface;
use SN\Notifications\Contracts\NotifiableInterface;
use SN\Notifications\Contracts\NotificationInterface;

class OrderNotification implements NotificationInterface, ArrayableInterface
{
    /**
     * Get the notification's delivery channels.
     *
     * @param NotifiableInterface $notifiable
     *
     * @return array
     */
    public function via(NotifiableInterface $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param NotifiableInterface $notifiable
     *
     * @return array
     */
    public function toArray(NotifiableInterface $notifiable): array
    {
        return [
            'order_id' => $this->orderId,
            'amount'   => $this->amount,
        ];
    }
}
```

#### Accessing The Notifications

Once notifications are stored in the database, you need a convenient way to access them from your notifiable entities.
The `NotifiableTrait`, which is included in this component, includes helpers for a $notifications relationship that returns the notifications for the entity.
To fetch notifications, you may used the `getNotifications()`, `getUnreadNotifations()` and `getReadNotifications()` methods.
By default, notifications will be sorted by the `createdAt` timestamp:

```php
$user = $this->getUser();

foreach ($user->getUnreadNotifications() as $notification) {
    echo $notification->data['message'];
}
```

#### Marking Notifications As Read

Typically, you will want to mark a notification as "read" when a user views it.
The `SSN\Bundle\NotificationsBundle\Entity\Notification` entity class provides a `setReadAt` method, which updates the `readAt` column on the notification's database record:

```php
$user = $this->getUser();

foreach ($user->getUnreadNotifications() as $notification) {
    $notification->setReadAt(new \DateTime());
}
```

## Notification Events

When a notification is sent, the component dispatches multiple events which you can use to modify how the notification is handled.

#### NotificationEvents::SENDING

##### Event Class: `SN\Notifications\Event\NotificationSendingEvent`

This event is dispatched before the Notification is sent. It's useful to add more information to the Notification or stop a Notification from being sent.

Execute this command to find out which listeners are registered for this event and their priorities:

```shell script
bin/console debug:event-dispatcher sn.notifications.sending
```

#### NotificationEvents::SEND

##### Event Class: `SN\Notifications\Event\NotificationSendEvent`

This event is dispatched to send the Notification. It's main use is to send the Notification to the desired Channel, which is how Channels are used internally in this component.

Execute this command to find out which listeners are registered for this event and their priorities:

```shell script
bin/console debug:event-dispatcher sn.notifications.send
```

#### NotificationEvents::SENT

##### Event Class: `SN\Notifications\Event\NotificationSentEvent`

This event is dispatched after the Notification is successfully sent. It's useful to perform tasks on Notifications that have been sent.

Execute this command to find out which listeners are registered for this event and their priorities:

```shell script
bin/console debug:event-dispatcher sn.notifications.sent
```

#### NotificationEvents::EXCEPTION

##### Event Class: `SN\Notifications\Event\NotificationExceptionEvent`

This event is dispatched as soon as an error occurs during the handling of the Notification. It's useful to recover from errors or modify the Notification.

Execute this command to find out which listeners are registered for this event and their priorities:

```shell script
bin/console debug:event-dispatcher sn.notifications.exception
```

## Custom Channels

This component ships with a handful of notification channels, but you may want to write your own to deliver notifications via other methods.
The dispatched notification events makes it simple.
To get started, define a class that listens or subscribes to the `NotificationEvents::SEND` event.

```php
<?php

namespace App\Channel;

use SN\Notifications\Event\NotificationSendEvent;
use SN\Notifications\NotificationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomChannel implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NotificationEvents::SEND => 'send',
        ];
    }

    /**
     * Send the given notification.
     *
     * @param NotificationSendEvent $event
     */
    public function send(NotificationSendEvent $event): void
    {
        $notifiable = $event->getNotifiable();
        $notification = $event->getNotification();

        // Send notification to the $notifiable.
    }
}
```

Once your notification channel class has been defined, you may return the class name from the `via` method of any of your notifications.
