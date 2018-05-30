<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Ratchet Websocket Library: helper file
 * @author Romain GALLIEN <romaingallien.rg@gmail.com>
 */
if (!function_exists('valid_json')) {

    /**
     * Check JSON validity
     * @method valid_json
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  mixed  $var  Variable to check
     * @return bool
     */
    function valid_json($var) {
        return (is_string($var)) && (is_array(json_decode($var, true))) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}
