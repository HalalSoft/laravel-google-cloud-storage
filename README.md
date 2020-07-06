# laravel-google-cloud-storage

A Google Cloud Storage filesystem for Laravel.

[![StyleCI](https://github.styleci.io/repos/277488235/shield?branch=master)](https://github.styleci.io/repos/277488235?branch=master)
[![GitHub repo size](https://img.shields.io/github/repo-size/halalsoft/laravel-google-cloud-storage?label=Repository%20size)](https://github.com/neneone/SnapeBot)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![GitHub top language](https://img.shields.io/github/languages/top/neneone/SnapeBot?label=PHP)](https://github.com/neneone/SnapeBot)
[![Packagist Version](https://img.shields.io/packagist/v/halalsoft/laravel-google-cloud-storage.svg?style=flat-square)](https://packagist.org/packages/halalsoft/laravel-google-cloud-storage)
[![Total Downloads](https://img.shields.io/packagist/dt/halalsoft/laravel-google-cloud-storage.svg?style=flat-square)](https://packagist.org/packages/halalsoft/laravel-google-cloud-storage)

Created by <a href="https://yaskur.com/" target="_blank">Dyas Yaskur</a>

## Installation
Add a new disk to your `filesystems.php` config

```php
'gcs' => [
        'driver'   => 'gcs',
        'key'      => env('GCP_ACCESS_KEY_ID'),
        'secret'   => env('GCP_SECRET_ACCESS_KEY'),
        'bucket'   => env('GCP_BUCKET'),
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

// get file modification date
$time = $disk->lastModified('image1.jpg');

// copy a file
$disk->copy('old/image1.jpg', 'new/image1.jpg');

// move a file
$disk->move('old/image1.jpg', 'new/image1.jpg');

// get url to file
$url = $disk->url('avatar/yaskur.jpg');

```
