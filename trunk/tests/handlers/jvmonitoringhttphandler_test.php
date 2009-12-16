<?php

class JVMonitoringHTTPHandlerTest extends ezpTestCase
{
	private $siteINI;
	
    public function providerValidURL()
    {
    	return array(
    		array('http://www.google.com'),
    		array('http://www.apple.com'),
//    		array('https://www.google.com/accounts/Login') // SSL website
    	);
    }
    
    /**
     * @dataProvider providerValidURL
     */
    public function testWithValidURL($url)
    {
    	$handler = new JVMonitoringHTTPHandler();
    	$handler->setParams(array('url' => $url));
    	$isUp = $handler->isUp();
    	$this->assertTrue($isUp);
    }
    
    public function providerInvalidURL()
    {
    	return array(
    		array('http://non-working.url'),
    		array('invalid://www.google.fr')
    	);
    }
    
    /**
     * @dataProvider providerInvalidURL
     */
    public function testWithInvalidURL($url)
    {
    	$handler = new JVMonitoringHTTPHandler();
    	$handler->setParams(array('url' => $url));
    	$isUp = $handler->isUp();
    	$this->assertFalse($isUp);
    }
    
    public function testWithNoURL()
    {
    	$this->setExpectedException('InvalidArgumentException');
    	$handler = new JVMonitoringHTTPHandler();
    	$isUp = $handler->isUp();
    }
}