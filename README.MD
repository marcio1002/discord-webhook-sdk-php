# Discord Webhook PHP

<div align='center'>
    <img src='./assets/send-message.png' alt='Message sent in Discord by webhook' width='50%'/>
</div>

---

## installation

```
composer require marcio1002/discord-webhook-sdk-php
```

## Constructor parameter options

Pass an array of options to the constructor, note that the webhook url is required.

| param       | type    | description                                                                                                                       | Required |
| ----------- | ------- | --------------------------------------------------------------------------------------------------------------------------------- | -------- |
| webhook_id | string  | The webhook ID | Yes      |
| token | string | The webhook token | Yes
| tts         | boolean | If true read message to channel members                                                                                           | No       |
| thread_id  | string  | Name of thread to create (requires the webhook channel to be a forum channel)                                                     | No       |

## Sending simple message

```php
use Marcio1002\DiscordWebhook\DiscordWebhook;

$webhook = new DiscordWebhook([
    'webhook_id' => '12345686',
    'token' => 'dghjt4fw3tk7865edfgt67iykmdw45g$Gdeg4C_VGHT43RW',
]);


//Synchronous envio
$webhook->sendMessage('This is a message', true);

//Asynchronous envio
$webhook->sendMessage('This is a message');

```

## Changing the webhook

With the Message class you can change the name, url, define the content and create embeds

```php
use Marcio1002\DiscordWebhook\DiscordWebhook;
use Marcio1002\DiscordWebhook\Message;

$message = new Message();
    ->setUsername('DC Notification')
    ->setAvatar('URL');
    ->setContent('Alert message');

$webhook
    ->sendMessage($message)
    ->then(
        fn() => print_r('Success!'),
        fn(\Throwable $err) => print_r($err->getMessage())
    )

```

## Sending embed

```php
use Marcio1002\DiscordWebhook\DiscordWebhook;
use Marcio1002\DiscordWebhook\MessageEmbed;

$message_embed = new MessageEmbed();

$message_embed
    ->setTitle('Title')
    ->setDescription('Description');
    ->setThumbnail('URL')

$discord->sendMessage($message_embed);


$embeds = range(0, 9); // limit 10 embeds

$embeds = array_map(
    fn ($n) => (new MessageEmbed)
        ->setTitle("Title $n"),
        ->setColor(MessageEmbed::randomColor())
    $embeds
);

$webhook->sendMessage($embeds);
```

## Sending facades class

```php
use Marcio1002\DiscordWebhook\Facades\DiscordWebhook;

DiscordWebhook::configure([
    'webhook_id' => '12345686',
    'token' => 'dghjt4fw3tk7865edfgt67iykmdw45g$Gdeg4C_VGHT43RW',
]);

DiscordWebhook::sendMessage('Message');
```

## Edit, get and delete message

When editing the message you can pass the same parameter that is passed in the **sendMessage** method

```php
DiscordWebhook::configure([
    'webhook_id' => '12345686',
    'token' => 'dghjt4fw3tk7865edfgt67iykmdw45g$Gdeg4C_VGHT43RW',
]);

DiscordWebhook::editMessage('ID', 'message', true);

DiscordWebhook::getMessage('ID')->then(fn($message) => print_r($message));

DiscordWebhook::deleteMessage('ID', true);

```