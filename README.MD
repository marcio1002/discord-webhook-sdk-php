# Discord Webhook PHP

<div align='center'>
    <img src='./assets/send-message.png' alt='Message sent in Discord by webhook' width='50%'/>
</div>

---------------------------------------------

## installation
```
composer required marcio1002/discord-webhook-sdk-php
```


## Constructor parameter options

Pass an array of options to the constructor, note that the webhook url is required.

| param       | type    | description                                                                                                                       | Required |
| ----------- | ------- | --------------------------------------------------------------------------------------------------------------------------------- | -------- |
| webhook_url | string  | The webhook url (see how to create the webhook [here](https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks)) | Yes      |
| tts         | boolean | If true read message to channel members                                                                                           | No       |
| thread_url  | string  | Name of thread to create (requires the webhook channel to be a forum channel)                                                     | No       |

## Sending simple message

```php

use Marcio1002\DiscordWebhook\DiscordWebhook;

$webhook = new DiscordWebhook([
    'webhook_url' => $webhook_url
]);


//Synchronous envio
$webhook->sendMessageSync('This is a message');

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

Sending raw data 

```php

$webhook->sendMessageSync([
    'username' => 'My Webhook',
    'content' => 'My Content',
    'embeds' => [
        [
            'title' => 'Hello ',
            'description' => 'Description',
            'thumbnail' => ['url' => 'URL'],
            'color' => 16761029
        ]
    ]
]);
```