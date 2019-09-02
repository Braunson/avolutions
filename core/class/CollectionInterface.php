<?php
/**
 * AVOLUTIONS
 * 
 * Just another open source PHP framework.
 * 
 * @author		Alexander Vogt <alexander.vogt@avolutions.de>
 * @copyright	2019 avolutions (http://avolutions.de)
 * @license		MIT License (https://opensource.org/licenses/MIT)
 * @link		https://github.com/avolutions/avolutions
 */

namespace core;

/**
 * Collection class
 *
 * A abstract class which implements the base collection functionality.
 * 
 * @package		core
 * @author		Alexander Vogt <alexander.vogt@avolutions.de>
 */
interface CollectionInterface
{		
	/**
	 * getAll
	 * 
	 * Returns all items of the Collection.
	 * 
	 * @return array An array of all items of the Collection
	 */
	public function getAll();
}
?>