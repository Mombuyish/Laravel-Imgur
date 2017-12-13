<p align="center"><img src="https://i.imgur.com/tjwClcs.png"></p>

# Laravel Imgur
> Laravel-Imgur is super easy upload image to imgur package.

## Installation

### Via composer
``` bash
$ composer require yish/imgur
```

If you use 5.5 or later, you don’t need to add provider into app.php, just use discovery autoloading.

### Facade binding
app.php
``` php
'Imgur' => Yish\Imgur\Facades\Upload::class,
```

## Usage
``` php
Imgur::upload($args);
```

Arguments can be a image link or file, for example, you can pass a https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/1200px-Apple_logo_black.svg.png or use file upload *MUST* instance of `Illuminate\Http\UploadedFile` .

### Customize
If you want to customize your headers or form params, you can do belong:

``` php
Imgur::setHeaders([
            'headers' => [
                'authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID'),
                'content-type' => 'application/x-www-form-urlencoded',
            ]
        ])->setFormParams([
            'form_params' => [
                'image' => $image,
            ]
        ])->upload($image);
```

## Quick Getter
You can use pretty methods to get what you want informations.

``` php
$image = Imgur::upload($file);

// Get imgur image link.
$image->link(); //"https://i.imgur.com/XN9m1nW.jpg"

// Get imgur image file size.
$image->fileszie(); //43180

// Get imgur image file type.
$image->type(); //"image/jpeg"

// Get imgur image width.
$image->width(); //480

// Get imgur image height.
$image->height(); //640

// Or you can get usual data.
$imag->usual();

//[
//  'link' => "https://i.imgur.com/XN9m1nW.jpg",
//  'filesize' => 43180,
//  'type' => "image/jpeg",
//  'width' => 480,
//  'height' => 640,
//]
```

Sometimes, you need get more image size, you can call `size` to get more thumbnails.
``` php

$image = Imgur::upload($file);

// Support: https://api.imgur.com/models/image

// Get small square.
$small_square = Imgur::size($image->link(), 's');

// Get big square thumbbnail.
$small_square = Imgur::size($image->link(), 'b');

// Get small small thumbbnail.
$small_square = Imgur::size($image->link(), 't');

// Get small medium thumbbnail.
$small_square = Imgur::size($image->link(), 'm');

// Get small large thumbbnail.
$small_square = Imgur::size($image->link(), 'l');

// Get small huge thumbbnail.
$small_square = Imgur::size($image->link(), 'h');
```