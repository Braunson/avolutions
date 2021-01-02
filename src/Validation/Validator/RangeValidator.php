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

namespace Avolutions\Validation\Validator;

use Avolutions\Validation\Validator;

/**
 * RangeValidator
 *
 * TODO
 * 
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.6.0
 */
class RangeValidator extends Validator
{
    /**
     * TODO
     */
    private $range = [];
    
    /**
     * TODO
     */
    private $not = false;

    /**
     * TODO
     */
    private $strict = false;

    /**
     * setOptions
     * 
     * TODO
     */
    public function setOptions($options = null) {
        if(!isset($options['range']) || !is_array($options['range'])) {
            // TODO
        } else {
            $this->range = $options['range'];
        }

        if(isset($options['not'])) {
            if(!is_bool($options['not'])) {
                // TODO
            } else {
                $this->not = $options['not'];
            }
        }

        if(isset($options['strict'])) {
            if(!is_bool($options['strict'])) {
                // TODO
            } else {
                $this->strict = $options['strict'];
            }
        }
    }

    /**
     * isValid
     * 
     * TODO
     * 
     * @return bool TODO
     */
    public function isValid($value) {
        if($this->not) {
            return !in_array($value, $this->range, $this->strict);
        } else {
            return in_array($value, $this->range, $this->strict);
        }
    }
}