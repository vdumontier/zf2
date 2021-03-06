<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Application
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace ZendTest\Application\Resource;

use Zend\Loader\Autoloader,
    Zend\Application,
    Zend\Application\Resource\Router as RouterResource,
    Zend\Controller\Front as FrontController;

/**
 * @category   Zend
 * @package    Zend_Application
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Application
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->application = new Application\Application('testing');
        $this->bootstrap = new Application\Bootstrap($this->application);

        FrontController::getInstance()->resetInstance();
    }

    public function tearDown()
    {
    }

    public function testInitializationInitializesRouterObject()
    {
        $resource = new RouterResource(array());
        $resource->setBootstrap($this->bootstrap);
        $resource->init();
        $this->assertTrue($resource->getRouter() instanceof \Zend\Controller\Router\Rewrite);
    }

    public function testInitializationReturnsRouterObject()
    {
        $resource = new RouterResource(array());
        $resource->setBootstrap($this->bootstrap);
        $test = $resource->init();
        $this->assertTrue($test instanceof \Zend\Controller\Router\Rewrite);
    }

    public function testChainNameSeparatorIsParsedOnToRouter()
    {
        $resource = new RouterResource(array('chainNameSeparator' => '_unitTestSep_'));
        $resource->setBootstrap($this->bootstrap);
        $router = $resource->init();
        $this->assertEquals('_unitTestSep_', $router->getChainNameSeparator());
    }

    public function testOptionsPassedToResourceAreUsedToCreateRoutes()
    {
        $options = array('routes' => array(
            'archive' => array(
                'route'    => 'archive/:year/*',
                'defaults' => array(
                    'controller' => 'archive',
                    'action'     => 'show',
                    'year'       => 2000,
                ),
                'reqs'     => array(
                    'year' => '\d+',
                ),
            ),
        ));

        $resource = new RouterResource($options);
        $resource->setBootstrap($this->bootstrap);
        $resource->init();
        $router   = $resource->getRouter();
        $this->assertTrue($router->hasRoute('archive'));
        $route = $router->getRoute('archive');
        $this->assertTrue($route instanceof \Zend\Controller\Router\Route\Route);
        $this->assertEquals($options['routes']['archive']['defaults'], $route->getDefaults());
    }
}
