# Laravel CDZ Localization
This package provides a minimal and simple starting point for building a multilang Laravel application. Styled with Tailwind, the package publishes ressources and views to your application that can be easily customized based on your own application's needs.

## Requirements
[Laravel](https://laravel.com/docs/8.x) >= 8.0

## Installation
First, you should create a new Laravel application, then install the package.
    
    composer require cdz/localization
    
After Composer has installed the package, you may run the **cdz-localization:install** Artisan command. This command publishes the views, routes, and other resources to your application. The package publishes all of its code to your application so that you have full control and visibility over its features and implementation. After CDZ Localization is installed, you should also compile your assets so that your application's CSS file is available:
    
    php artisan cdz-localization:install
    npm install
    npm run dev
    
Now your home url should be accessible in several languages.

## Configuration
The config file is published at **config/localization.php**
    
## Credits
[Codeuz](http://codeuz.com/)

This package is based on [alexjoffroy
/
laravel-localization](https://github.com/alexjoffroy/laravel-localization) package.

