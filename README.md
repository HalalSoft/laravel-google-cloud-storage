# Laravel Google Cloud Storage

A Google Cloud Storage filesystem for Laravel.

[![StyleCI](https://github.styleci.io/repos/277488235/shield?branch=master)](https://github.styleci.io/repos/277488235?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/HalalSoft/laravel-google-cloud-storage/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/HalalSoft/laravel-google-cloud-storage/?branch=master)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/810881222d7f41e7ba0f14998418dc00)](https://www.codacy.com/gh/HalalSoft/laravel-google-cloud-storage?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=HalalSoft/laravel-google-cloud-storage&amp;utm_campaign=Badge_Grade)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/HalalSoft/laravel-google-cloud-storage/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![Build Status](https://scrutinizer-ci.com/g/HalalSoft/laravel-google-cloud-storage/badges/build.png?b=master)](https://scrutinizer-ci.com/g/HalalSoft/laravel-google-cloud-storage/build-status/master)
[![GitHub repo size](https://img.shields.io/github/repo-size/halalsoft/laravel-google-cloud-storage?label=Repository%20size)](https://github.com/neneone/SnapeBot)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![GitHub top language](https://img.shields.io/github/languages/top/neneone/SnapeBot?label=PHP)](https://github.com/neneone/SnapeBot)
[![Packagist Version](https://img.shields.io/packagist/v/halalsoft/laravel-google-cloud-storage.svg?style=flat-square)](https://packagist.org/packages/halalsoft/laravel-google-cloud-storage)
[![Total Downloads](https://img.shields.io/packagist/dt/halalsoft/laravel-google-cloud-storage.svg?style=flat-square)](https://packagist.org/packages/halalsoft/laravel-google-cloud-storage)

This is using [flysystem-aws-s3-v3](https://packagist.org/packages/league/flysystem-aws-s3-v3). Because  Google Cloud Storage uses the same api as Amazon S3, so actually I just use the same driver but I renamed to gcs.
## Installation
```
composer require halalsoft/laravel-google-cloud-storage
```

Add a new disk to your `filesystems.php` config

```php
'gcs' => [
        'driver'   => 'gcs',
        'key'      => env('GCP_ACCESS_KEY_ID'),
        'secret'   => env('GCP_SECRET_ACCESS_KEY'),
        'bucket'   => env('GCP_BUCKET'),
],
```

Above is the config that required, here is other possible configs:
```php
'gcs' => [
        'driver'   => 'gcs',
        'key'      => env('GCP_ACCESS_KEY_ID'),
        'secret'   => env('GCP_SECRET_ACCESS_KEY'),
        'bucket'   => env('GCP_BUCKET'),
        'visibility' => 'public', //Default visibility, you can set public or private
        'url'    => "https://custom.domain.com", //Your public URL (if you use custom domain or CDN)
        'endpoint' => "https://storage.googleapis.com", //Your endpoint URL (if you use custom driver)
        'cache' => [
            'store' => 'memcached',
            'expire' => 600,
            'prefix' => 'cache-prefix',
          ],
],
```
## Usage
You can use most of [Laravel Filesystem API](https://laravel.com/docs/7.x/filesystem)

Examples:
```php
$disk = Storage::disk('gcs');

// create a file
$disk->put('avatars/1', $request->file("image"));

// check if a file exists
$exists = $disk->exists('image.jpg');

// get file last modification date
$time = $disk->lastModified('image1.jpg');

// copy a file
$disk->copy('old/image1.jpg', 'new/image1.jpg');

// move a file
$disk->move('old/image1.jpg', 'new/image1.jpg');

// get url to file
$url = $disk->url('avatar/yaskur.jpg');

```
