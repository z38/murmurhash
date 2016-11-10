# MurmurHash

[![Build Status on Linux](https://travis-ci.org/z38/murmurhash.png?branch=master)](https://travis-ci.org/z38/murmurhash)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/z38/murmurhash/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/z38/murmurhash/?branch=master)

PHP implementation of [MurmurHash3_x64_128](https://github.com/aappleby/smhasher/wiki/MurmurHash3).


## Installation

Just install [Composer](http://getcomposer.org) and run `composer require z38/murmurhash` in your project directory.


## Usage

```php
$hash = new Z38\MurmurHash\Murmur3F();

$hash->write('The quick brown fox jumps over the lazy dog.');

echo bin2hex($hash->sum()); // c902e99e1f4899cde7b68789a3a15d69
```
