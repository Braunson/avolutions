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

use Avolutions\Core\AbstractSingleton;

/**
 * Container class
 *
 * The Dependency Injection Container.
 *
 * @author	Alexander Vogt <alexander.vogt@avolutions.org>
 * @since	0.6.0
 */
class Container extends AbstractSingleton
{
    /**
     * TODO
    */
    private $resolvedEntries = [];

    /**
     * TODO
    */
    private $singletons = [];

    /**
     * TODO
     */
    private $interfaces = [];

    /**
     * TODO
     */
    private $constructorParams = [];

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed The resolved entry.
     */
    public function get($id)
    {
        if(isset($this->singletons[$id])) {
            if (isset($this->resolvedEntries[$id])) {
                return $this->resolvedEntries[$id];
            }
        }        

        $parameters = [];
        
        if(isset($this->interfaces[$id])) {
            $id = $this->interfaces[$id];
        }

        $ReflectionClass = new \ReflectionClass($id);        
        $Constructor = $ReflectionClass->getConstructor();

        if(!is_null($Constructor)) {
            foreach ($Constructor->getParameters() AS $parameter) {
                if(isset($this->constructorParams[$id][$parameter->getName()])) {
                    $parameters[] = $this->constructorParams[$id][$parameter->getName()];
                } else {
                    $className = $parameter->getType()->getName();
                    $parameters[] = $this->get($className);
                }
            }
        }

        if($ReflectionClass->isSubclassOf(AbstractSingleton::class)) {
            $entry = $id::getInstance();
        } else {
            $entry = new $id(...$parameters);
        }
        $this->resolvedEntries[$id] = $entry;

        return $entry;
    }

    /**
     * TODO
     */
    public function setSingleton($id) 
    {
        if (!isset($this->singletons[$id])) {
            $this->singletons[$id] = $id;
        }
    }

    /**
     * TODO
     */
    public function setInterface($interface, $instance)
    {
        $this->interfaces[$interface] = $instance;
    }

    /**
     * TODO
     */
    public function setConstructorParams($class, $params = [])
    {
        $this->constructorParams[$class] = $params;
    }
}