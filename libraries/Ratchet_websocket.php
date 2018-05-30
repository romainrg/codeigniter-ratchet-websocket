<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package   CodeIgniter Ratchet WebSocket Library
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
     * __construct : Constructor
     * @method __construct
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  array $config Configuration
     */
    public function __construct(array $config = array())
    {
        // Load the CI instance
        $this->CI = & get_instance();
    }
}
