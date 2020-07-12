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
 
namespace Avolutions\Di;

/**
 * Container class
 *
 * The Dependency Injection Container.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.4.0
 */
class Container
{
    private $resolvedEntries = [];

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $name Identifier of the entry to look for.
     *
     * @return mixed The resolved entry.
     */
    public function get($name)
    {
        if (isset($this->resolvedEntries[$name])) {
            return $this->resolvedEntries[$name];
        }

        $parameters = [];

        $ReflectionClass = new \ReflectionClass($name);        
        $Constructor = $ReflectionClass->getConstructor();

        if(!is_null($Constructor)) {
            foreach ($Constructor->getParameters() AS $parameter) {
                $className = $parameter->getType()->getName();
                $parameters[] = $this->get($className);
            }
        }

        $entry = new $name(...$parameters);
        $this->resolvedEntries[$name] = $entry;

        return $entry;
    }
}