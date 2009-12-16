<?php
/**
 * Unit tests for JVMonitoring
 * @author jvieilledent
 */
class JVMonitoringTest extends ezpTestCase
{
	public function __construct()
    {
        parent::__construct();
        $this->setName( "JVMonitoring Unit Tests" );
    }
    
    public function testGetMonitoringHandlers()
    {
        $monitoringHandler = new JVMonitoring();
        $aHandlers = $monitoringHandler->getMonitoringHandlers();
        
        // Test if result is an array
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $aHandlers);
        
        foreach($aHandlers as $handler)
        {
        	// Test if handler class exists
        	$handlerClass = $handler['class'];
        	$this->assertTrue(class_exists($handlerClass));
        	
        	// Test if class implements IJVMonitoringHandler
        	$aImplInterfaces = class_implements($handlerClass);
        	$this->assertArrayHasKey('IJVMonitoringHandler', $aImplInterfaces, 
        							 "Handler class '$handlerClass' doesn't implement interface 'IJVMonitoringHandler'");
        }
        
		return $monitoringHandler;
    }

    /**
     * @depends testGetMonitoringHandlers 
     * @param JVMonitoring $monitoringHandler
     */
    public function testDoMonitor(JVMonitoring $monitoringHandler)
    {
        $aMonitorResult = $monitoringHandler->doMonitor();
        // Test result is an array
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $aMonitorResult);
        
        return $monitoringHandler; 
    }

    /**
     * @depends testDoMonitor
     */
    public function testHasErrors(JVMonitoring $monitoringHandler)
    {
        $hasErrors = $monitoringHandler->hasErrors();
        $this->assertType(PHPUnit_Framework_Constraint_IsType::TYPE_BOOL, $hasErrors);
    }
}

