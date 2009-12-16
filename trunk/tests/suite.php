<?php

class JVMonitoringTestSuite extends ezpTestSuite
{
	public function __construct()
	{
		parent::__construct();
		$this->setName( "JVMonitoring Test Suite" );
		
		$this->addTestSuite( 'JVMonitoringTest' );
		$this->addTestSuite( 'JVMonitoringHTTPHandlerTest' );
	}
	
	public static function suite()
	{
		return new self();
	}
	
}