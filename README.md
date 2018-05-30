
# CodeIgniter Ratchet WebSocket Library

> CodeIgniter library who allow you to make **powerfull applications** with realtime interactions by using Websocket technology and Ratchetphp ([Socketo.me](http://socketo.me))

<center>Development and examples in progress ..</center>

## :books: Dependencies

- PHP 5.4+
- CodeIgniter Framework (3.1.* recommanded)
- Composer
- PHP sockets extension enabled

## :beginner: Installation

### :one: Step 1 : Library installation by Composer

Just by running following command in the folder of your project :
```sh
composer require romainrg/codeigniter-ratchet-websocket
```
Or by adding following lines to your `composer.json` file :
```json
"require": {
    "romainrg/codeigniter-ratchet-websocket": "^1.0.0"
},
```
### :two: Step 2 : Create library config file in your project (Optional)

You have to create in your CI config folder located in `./application/config/ratchet_websocket.php` or the library will take his own config file based on host `localhost:8282`

```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Ratchet Websocket Library: config file
 * @author Romain GALLIEN <romaingallien.rg@gmail.com>
 * @var array
 */
$config['ratchet_websocket'] = array(
    'host' => 'localhost',
    'port' => 8282
);
```
### :three: Step 3 : Loading the library

You can add the following lines direclty in your Controller file or your MY_Controller global file

```php
$this->load->add_package_path(FCPATH.'vendor/romainrg/ratchet-websocket');
$this->load->library('ratchet_websocket');
$this->load->remove_package_path(FCPATH.'vendor/romainrg/ratchet-websocket');
```

### :heavy_check_mark: You'r almost done

## Examples of use

#### :one: Running your first Websocket server

It's not very difficult, the library will do your job and much more !


## For more CodeIgniter libraries, give me a :beer::grin:

## :construction: To do

 - Add app routing fonctionnality
 - Websocket native library

## :lock: License [MIT License](http://opensource.org/licenses/MIT)
