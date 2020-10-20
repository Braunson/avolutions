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

use PHPUnit\Framework\TestCase;

use Avolutions\Di\Container;
use Avolutions\Routing\Route;
use Avolutions\Routing\RouteCollection;

class RouteCollectionTest extends TestCase
{
    public function testRouteCollectionCanBeCreated()
    {
        $Container = Container::getInstance();
        $RouteCollection = $Container->get('Avolutions\Routing\RouteCollection');
        
        $this->assertInstanceOf('Avolutions\Routing\RouteCollection', $RouteCollection);    
    }

    public function testRoutesCanBeAddedToCollection()
    {
        $Container = Container::getInstance();
        $RouteCollection = $Container->get('Avolutions\Routing\RouteCollection');

        $Route = new Route('');
        $Route2 = new Route('', ['method' => 'POST']);
        
        $RouteCollection->addRoute($Route);
        $RouteCollection->addRoute($Route2);

        $this->assertContains($Route, $RouteCollection->items);        
        $this->assertContains($Route2, $RouteCollection->items);
    }

    public function testCountItemsOfCollection()
    {
        $Container = Container::getInstance();
        $RouteCollection = $Container->get('Avolutions\Routing\RouteCollection');

        $this->assertEquals(2, $RouteCollection->count());
    }

    public function testGetAllItemsOfCollection()
    {
        $Container = Container::getInstance();
        $RouteCollection = $Container->get('Avolutions\Routing\RouteCollection');

        $allItems = $RouteCollection->getAll();

        $this->assertEquals(2, count($allItems));
        $this->assertInstanceOf('Avolutions\Routing\Route', $allItems[0]);
        $this->assertInstanceOf('Avolutions\Routing\Route', $allItems[1]);
    }

    public function testGetAllItemsByMethodOfCollection()
    {
        $Container = Container::getInstance();
        $RouteCollection = $Container->get('Avolutions\Routing\RouteCollection');

        $allGet = $RouteCollection->getAllByMethod('GET');        
        $allPost = $RouteCollection->getAllByMethod('POST');

        $this->assertEquals(1, count($allGet));        
        $this->assertEquals(1, count($allPost));
        $this->assertInstanceOf('Avolutions\Routing\Route', $allGet[0]);
        $this->assertInstanceOf('Avolutions\Routing\Route', $allPost[0]);
        $this->assertEquals($allGet[0]->method, 'GET');        
        $this->assertEquals($allPost[0]->method, 'POST');
    }
}