# Monobank open API client
PHP client for Monobank open API 

[![License][license-image]][license-link] [![Build Status][travis-image]][travis-link] [![codecov][codecov-image]][codecov-link] [![scrutinizer][scrutinizer-image]][scrutinizer-link] [![intelligence][intelligence-image]][intelligence-link] 

![](https://api.monobank.ua/docs/images/logo.png)

## Examples

```php
<?php

include 'vendor/autoload.php';

use GuzzleHttp\Client;
use Monobank\MonobankClient;
use Monobank\ValueObject\Token;

$httpClient = new Client();
$token = new Token('...');
$client = new MonobankClient($httpClient, $token);

var_dump($client->getExchangeRates());

var_dump($client->getClientInfo());

var_dump($client->getPersonalStatement(new PersonalStatementRequest(new DateTime('2019-06-20'), new DateTime())));
```
## Source(s)

* [Monobank API docs](https://api.monobank.ua/docs/)

[license-link]: https://github.com/zhooravell/php-monobank/blob/master/LICENSE
[license-image]: https://img.shields.io/dub/l/vibe-d.svg

[travis-link]: https://travis-ci.com/zhooravell/php-monobank
[travis-image]: https://travis-ci.com/zhooravell/php-monobank.svg?branch=master

[codecov-link]: https://codecov.io/gh/zhooravell/php-monobank
[codecov-image]: https://codecov.io/gh/zhooravell/php-monobank/branch/master/graph/badge.svg

[scrutinizer-link]: https://scrutinizer-ci.com/g/zhooravell/php-monobank/?branch=master
[scrutinizer-image]: https://scrutinizer-ci.com/g/zhooravell/php-monobank/badges/quality-score.png?b=master

[intelligence-link]: https://scrutinizer-ci.com/code-intelligence
[intelligence-image]: https://scrutinizer-ci.com/g/zhooravell/php-monobank/badges/code-intelligence.svg?b=master