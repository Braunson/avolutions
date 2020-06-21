<?php
/**
 * AVOLUTIONS
 * 
 * Just another open source PHP framework.
 * 
 * @copyright	Copyright (c) 2019 - 2020 AVOLUTIONS
 * @license		MIT License (http://avolutions.org/license)
 * @link		http://avolutions.org
 */

use Avolutions\Http\Request;

/**
 * Load the bootstrap file
 */
require_once '../bootstrap.php';

/**
 * Load the routes file
 */
require_once '../routes.php'; 

/**
 * Load the events file
 */
require_once '../events.php'; 

/**
 * Start the application
 */
$Request = $Container->get('Avolutions\Http\Request');
$Response = $Request->send();