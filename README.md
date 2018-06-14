##### Activation API SMS Free Mobile

Active API Free Mobile in your account


##### Installation with composer

    $ composer require augustinfla/free-mobile-sms "~1.0"


##### Example

```php
<?php

$sms = new Sms('your_login', 'your_pass');
$sms->setMessage('Hello world');
$sms->send();
```


##### PHPUnit

    $ ./vendor/bin/phpunit --bootstrap vendor/autoload.php tests


##### Credit

By Augustin Flament
