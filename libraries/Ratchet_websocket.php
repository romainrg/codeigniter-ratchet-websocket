<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Namespaces
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

// Super Global VAR
global $verbose;

/**
 * @package   CodeIgniter Ratchet WebSocket Library: Main class
 * @category  Libraries
 * @author    Romain GALLIEN <romaingallien.rg@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/romainrg
 *
 * CodeIgniter library who allow you to make powerfull applications with realtime interactions by using Websocket technology and Ratchetphp
 */
class Ratchet_websocket
{
    /**
     * CI Super Instance
     * @var array
     */
    private $CI;

    /**
     * Config vars
     * @var array
     */
    protected $config = array();

    /**
     * Default host var
     * @var string
     */
    protected $host = null;

    /**
     * Default host var
     * @var string
     */
    protected $port = null;

    /**
     * Class Constructor
     * @method __construct
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  array $config Configuration
     * @return void
     */
    public function __construct(array $config = array())
    {
        // Load the CI instance
        $this->CI = & get_instance();

        // Load the class helper
        $this->CI->load->helper('ratchet_websocket');

        // Define the config vars
        $this->config = (!empty($config)) ? $config : array();

        // Config file verification
        (!empty($this->config)) or exit('The configuration file does not exist');

        // Assign HOST value to global class var
        $this->host = (!empty($this->config['ratchet_websocket']['host'])) ? $this->config['ratchet_websocket']['host'] : '';

        // Assign PORT value to global class var
        $this->port = (!empty($this->config['ratchet_websocket']['port'])) ? $this->config['ratchet_websocket']['port'] : '';

        // Assign VERBOSE value to super global var
        $GLOBALS['verbose'] = (!empty($this->config['ratchet_websocket']['verbose'])) ? $this->config['ratchet_websocket']['verbose'] : false;
    }

    /**
     * Launch the server
     * @method run
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @return string
     */
    public function run()
    {
        // Initiliaze all the necessary class
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Server()
                )
            ),
            $this->port,
            $this->host
        );

        // Output
        if ($GLOBALS['verbose']) {
            echo 'Running server on host '.$this->host.':'.$this->port.PHP_EOL;
        }

        // Run the socket connection !
        $server->run();
    }
}

 /**
  * @package   CodeIgniter Ratchet WebSocket Library: Server class
  * @category  Libraries
  * @author    Romain GALLIEN <romaingallien.rg@gmail.com>
  * @license   http://opensource.org/licenses/MIT > MIT License
  * @link      https://github.com/romainrg
  *
  * CodeIgniter library who allow you to make powerfull applications with realtime interactions by using Websocket technology and Ratchetphp
  */
class Server implements MessageComponentInterface
{
    /**
     * List of connected clients
     * @var array
     */
    protected $clients;

    /**
     * Class constructor
     * @method __construct
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     */
    public function __construct()
    {
        // Initialize object as SplObjectStorage (see PHP doc)
        $this->clients = new \SplObjectStorage;
    }

    /**
     * Event trigerred on new client event connection
     * @method onOpen
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  ConnectionInterface $connection
     * @return string
     */
    public function onOpen(ConnectionInterface $connection)
    {
        // Add client to global clients object
        $this->clients->attach($connection);

        // Output
        if ($GLOBALS['verbose']) {
            echo 'New client connected as #'.$connection->resourceId.PHP_EOL;
        }
    }

    /**
     * Event trigerred on new message sent from client
     * @method onMessage
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  ConnectionInterface $client
     * @param  string              $message
     * @return string
     */
    public function onMessage(ConnectionInterface $client, $message)
    {
        // Broadcast var
        $broadcast = false;

        // Check if received var is json format
        if (valid_json($message)) {

            // If true, we have to decode it
            $datas = json_decode($message);
        }

        // Once we decoded it, we check look for global broadcast
        $broadcast = (!empty($datas->broadcast) and $datas->broadcast == true) ? true : false;

        // Count real clients numbers (-1 for server)
        $clients = count($this->clients) - 1;

        // Here we have to reassign the client ressource ID, this will allow us to send message to specified client.
        if (!empty($datas->user_id) && $datas->user_id !== $client->resourceId) {

            // Output
            if ($GLOBALS['verbose']) {
                echo 'Client #'.$client->resourceId.' reassigned as user #'.$datas->user_id.PHP_EOL;
            }

            // Asign the new #
            $client->resourceId = $datas->user_id;

            // That's all we can stop here
            return true;
        }

        // Now this is the management of messages destinations, at this moment, 4 possibilities :
        // 1 - Message is not an array OR message has no destination (broadcast to everybody except us)
        // 2 - Message is an array and have destination (broadcast to single user)
        // 3 - Message is an array and don't have specified destination (broadcast to everybody except us)
        // 4 - Message is an array and we wan't to broadcast to ourselves too (broadcast to everybody)
        foreach ($this->clients as $user) {

            // Broadcast to single user
            if (!empty($datas->recipient_id)) {
                if ($user->resourceId == $datas->recipient_id) {
                    $user->send($message);

                    // Output
                    if ($GLOBALS['verbose']) {
                        echo 'Client #'.$client->resourceId.' send "'.$datas->message.'" to #'.$user->resourceId.PHP_EOL;
                    }
                    break;
                }
            } else {
                // Broadcast to everybody
                if ($broadcast) {
                    $user->send($message);

                    // Output
                    if ($GLOBALS['verbose']) {
                        echo 'Client #'.$client->resourceId.' send "'.$datas->message.'" to #'.$user->resourceId.PHP_EOL;
                    }
                } else {
                    // Broadcast to everybody except us
                    if ($client !== $user) {
                        $user->send($message);

                        // Output
                        if ($GLOBALS['verbose']) {
                            echo 'Client #'.$client->resourceId.' send "'.$datas->message.'" to #'.$user->resourceId.PHP_EOL;
                        }
                    }
                }
            }
        }
    }

    /**
     * Event triggered when connection is closed (or user disconnected)
     * @method onClose
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  ConnectionInterface $connection
     * @return string
     */
    public function onClose(ConnectionInterface $connection)
    {
        // Output
        if ($GLOBALS['verbose']) {
            echo 'Client '.$connection->resourceId.' disconnected'.PHP_EOL;
        }

        // Detach client from SplObjectStorage
        $this->clients->detach($connection);
    }

    /**
     * Event trigerred when error occured
     * @method onError
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  ConnectionInterface $connection
     * @param  Exception           $e
     * @return string
     */
    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        // Output
        if ($GLOBALS['verbose']) {
            echo 'An error has occurred: '.$e->getMessage().PHP_EOL;
        }

        // We close this connection
        $connection->close();
    }
}
