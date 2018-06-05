# CodeIgniter Ratchet WebSocket Library
CodeIgniter library who allow you to make **powerfull applications** with realtime interactions by using Websocket technology and Ratchetphp ([Socketo.me](http://socketo.me))

## :books: Dependencies

- PHP 5.4+
- CodeIgniter Framework (3.1.* recommanded)
- Composer
- PHP sockets extension enabled

## :beginner: Installation

### :arrow_right: Step 1 : Library installation by Composer

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
Don't forget to include your autoload to CI config file :
```php
$config['composer_autoload'] = FCPATH.'vendor/autoload.php';
```
### :arrow_right: Step 2 : Create library config file in your project (Optional)

You have to create in your CI config folder located in `./application/config/ratchet_websocket.php` or the library will take his own config file based on host `0.0.0.0:8282`

```php
<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Ratchet Websocket Library: config file
 * @author Romain GALLIEN <romaingallien.rg@gmail.com>
 * @var array
 */
$config['ratchet_websocket'] = array(
    'host' => '0.0.0.0',    // Default host
    'port' => 8282,         // Default port (be carrefull to set unused server port)
    'auth' => true,         // If authentication is mandatory
    'debug' => true         // Better to set as false in Production
);
```
### :arrow_right: Step 3 : Loading the library

You can add the following lines direclty in your Controller file or your MY_Controller global file

```php
$this->load->add_package_path(FCPATH.'vendor/romainrg/codeigniter-ratchet-websocket');
$this->load->library('ratchet_websocket');
$this->load->remove_package_path(FCPATH.'vendor/romainrg/codeigniter-ratchet-websocket');
```

### You'r almost done :heavy_check_mark:

## Examples of use

#### :arrow_right: Create your first App

It's not very difficult, the library will do your job and much more !
- Edit your CI controller `Welcome.php` with the following lines (this will be our server)

```php
class Welcome extends CI_Controller
{
    public function index()
    {
        // Load package path
        $this->load->add_package_path(FCPATH.'vendor/romainrg/codeigniter-ratchet-websocket');
        $this->load->library('ratchet_websocket');
        $this->load->remove_package_path(FCPATH.'vendor/romainrg/codeigniter-ratchet-websocket');

        // Run server
        $this->ratchet_websocket->run();
    }
}
```
- Create CI controller `User.php` and add following lines
```php
class User extends CI_Controller
{
    public function index($user_id = null)
    {
	// We load the CI welcome page with some lines of Javascript
        $this->load->view('welcome_message', array('user_id' => $user_id));
    }
}
```
- Edit your CI view `welcome_message.php` with following lines (again :stuck_out_tongue_winking_eye:)
```php
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
    <style type="text/css">
    #container,code{border:1px solid #D0D0D0}::selection{background-color:#E13300;color:#fff}::-moz-selection{background-color:#E13300;color:#fff}body{background-color:#fff;margin:40px;font:13px/20px normal Helvetica,Arial,sans-serif;color:#4F5155}a,h1{background-color:transparent;font-weight:400}a{color:#039}h1{color:#444;border-bottom:1px solid #D0D0D0;font-size:19px;margin:0 0 14px;padding:14px 15px 10px}code{font-family:Consolas,Monaco,Courier New,Courier,monospace;font-size:12px;background-color:#f9f9f9;color:#002166;display:block;margin:14px 0;padding:12px 10px}#body{margin:0 15px}p.footer{text-align:right;font-size:11px;border-top:1px solid #D0D0D0;line-height:32px;padding:0 10px;margin:20px 0 0}#container{margin:10px;box-shadow:0 0 8px #D0D0D0}
    </style>
</head>
<body>
    <div id="container">
        <h1>Welcome to CodeIgniter!</h1>
        <div id="body">
            <div id="messages"></div>
            <input type="text" id="text" placeholder="Message ..">
            <input type="text" id="recipient_id" placeholder="Recipient id ..">
            <button id="submit" value="POST">Send</button>
        </div>
        <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
    var conn = new WebSocket('ws://localhost:8282');
    var client = {
        user_id: <?php echo $user_id; ?>,
        recipient_id: null,
        message: null
    };

    conn.onopen = function(e) {
        conn.send(JSON.stringify(client));
        $('#messages').append('<font color="green">Successfully connected as user '+ client.user_id +'</font><br>');
    };

    conn.onmessage = function(e) {
        var data = JSON.parse(e.data);
        if (data.message) {
            $('#messages').append(data.user_id + ' : ' + data.message + '<br>');
        }
    };

    $('#submit').click(function() {
        client.message = $('#text').val();
        if ($('#recipient_id').val()) {
            client.recipient_id = $('#recipient_id').val();
        }
        conn.send(JSON.stringify(client));
    });
    </script>
</body>
</html>
```
**Ok you just created your first app !** :heavy_check_mark: (easy with CTRL+C and CTRL+V)
#### :arrow_right: Run the Websocket server
If you wan't to check you'r work, you have to run the server.
Open you'r command prompt then type the command bellow in you'r project folder :
```sh
php index.php welcome index
```
If you see the message the message bellow,  you are done (don't close your cmd) !

![First_launch.png](https://user-images.githubusercontent.com/14097222/40981263-d568413a-68da-11e8-9ab2-7b3f7224526e.PNG)
#### :arrow_right: Test the App
Open three pages of your project on following url with different IDs :
`http://localhost/myproject/user/index/204`
`http://localhost/myproject/user/index/402`
`http://localhost/myproject/user/index/604`

:heavy_exclamation_mark: In my example, **recipient_id** is defined by **user_id**, as you can see, it's the **auth callback** who defines recipient ids.

If you have something like that, everything is ok for you:

![User_204](https://user-images.githubusercontent.com/14097222/40725234-2d7ea6aa-6423-11e8-975e-4372125c889d.PNG)

You can try is by typing and sending something in each page (see cmd for more logs).

![Cmd_list.png](https://user-images.githubusercontent.com/14097222/40981966-819da07a-68dc-11e8-9717-b0135a107318.PNG)

## Broadcast messages with your php App :boom: !
If you want to broadcast message with php script or something else you can use library like [textalk/websocket](https://github.com/Textalk/websocket-php) ***(who is included in my composer.json as required library)***

> *Note : The first message is mandatory and always here to perform authentication*

```php
$client = new Client('ws://0.0.0.0:8282');

$client->send(json_encode(array('user_id' => 1, 'message' => null)));
$client->send(json_encode(array('user_id' => 1, 'message' => 'Super cool message to myself!')));
```
## Authentication & callbacks :recycle:
The library allow you to define some callbacks, here's an example :
```php
class Welcome extends CI_Controller
{
    public function index()
    {
        // Load package path
        $this->load->add_package_path(FCPATH.'vendor/romainrg/codeigniter-ratchet-websocket');
        $this->load->library('ratchet_websocket');
        $this->load->remove_package_path(FCPATH.'vendor/romainrg/codeigniter-ratchet-websocket');

        // Run server
        $this->ratchet_websocket->set_callback('auth', array($this, '_auth'));
        $this->ratchet_websocket->set_callback('event', array($this, '_event'));
        $this->ratchet_websocket->run();
    }

    public function _auth($datas = null)
    {
        // Here you can verify everything you want to perform user login.
        // However, method must return integer (client ID) if auth succedeed and false if not.
        return (!empty($datas->user_id)) ? $datas->user_id : false;
    }

    public function _event($datas = null)
    {
        // Here you can do everyting you want, each time message is received
        echo 'Hey ! I\'m an EVENT callback'.PHP_EOL;
    }
}
```

 - **Auth** type callback is called at first message posted from client.
 - **Event** type callback is called on every message posted.

## What about Docker :whale: ?

Easy to start with this command (php 7.1 used)
```sh
docker run -ti -v C:\Users\my_user\path_to_my_project\:/app -p 8282:8282 -w /app php:7.1-cli sh -c "php index.php welcome index"
```
## Bugs :bug: or feature :muscle:
Be free to open an issue or send pull request

## To do :construction:
 - Origin check
 - WSS support
 - Add app routing fonctionnality
 - Websocket native library
## For more CodeIgniter libraries, give me a :beer::grin:
[> Beer road](https://www.paypal.me/romaingallien)

## :lock: License
[MIT License](http://opensource.org/licenses/MIT)
