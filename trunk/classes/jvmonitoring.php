<?php
// SOFTWARE NAME: jvMonitoring
// SOFTWARE RELEASE: @@@VERSION@@@
// COPYRIGHT NOTICE: Copyright (C) 2009 Jerome Vieilledent
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.

class JVMonitoring
{
	/**
	 * @var eZINI
	 */
	private $monitoringINI;
	
	/**
	 * @var boolean
	 */
	private $hasErrors = false;
	
	/**
	 * Array of Exceptions caught by JVMonitoringException before the jvmonitoring/monitor module load
	 * @var array
	 */
	private static $exceptions = array();
	
	public function __construct()
	{
		$this->monitoringINI = eZINI::instance('jvmonitoring.ini');
	}
	
	/**
	 * Launches all monitoring tests and returns the array of handlers. See JVMonitoring::getMonitoringHandlers doc for more info about this array
	 * @return array
	 */
	public function doMonitor()
	{
		$handlers = $this->getMonitoringHandlers();
		foreach($handlers as &$handler)
		{
			try
			{
				$handlerClass = $handler['class'];
				$handlerObject = new $handlerClass();
				$handlerObject->setParams($handler['params']);
				
				if(!$handlerObject->isUp())
				{
					$handler['error'] = $handlerObject->getErrorMessage();
					$handler['is_up'] = false;
					$this->hasErrors = true;
				}
			}
			catch(Exception $e)
			{
				$msg = $e->getMessage();
				eZDebug::writeError($msg, 'JVMonitoring');
				eZLog::write($msg, 'error.log');
				continue;
			}
		}
		
		return $handlers;
	}
	
	/**
	 * Returns an associative array containing infos about all declared monitoring handlers
	 * The key is the handler "identifier" (section in INI file)
	 * Values are :
	 * 		- name		=> Explicit name of the handler
	 * 		- class 	=> Handler class
	 * 		- params	=> Array of params passed to the handler, if any declared. Empty array if not
	 * 		- error		=> Empty. Will be filled by an error message if monitoring test fail
	 * 		- is_up		=> Boolean. Indicates if service is up. Initialized to false. Will be filled in doMonitor() method
	 * @return array
	 */
	public function getMonitoringHandlers()
	{
		$availableHandlers = $this->monitoringINI->variable('GeneralSettings', 'AvailableHandlers');
		$aHandlers = array();
		foreach($availableHandlers as $availHandler)
		{
			if(!$this->monitoringINI->hasSection($availHandler))
			{
				eZDebug::writeError("Handler '$availHandler' declared but no corresponding section in jvMonitoring.ini. Skipping", 'jvMonitoring');
				continue;
			}
				
			$handlerName = $this->monitoringINI->variable($availHandler, 'TestName');
			$handlerClass = $this->monitoringINI->variable($availHandler, 'HandlerClass');
			$handlerParams = $this->monitoringINI->hasVariable($availHandler, 'HandlerParams') ? $this->monitoringINI->variable($availHandler, 'HandlerParams') : array();
			$aHandlers[$availHandler] = array(
				'name'		=> $handlerName,
				'class'		=> $handlerClass,
				'params'	=> $handlerParams,
				'error'		=> '',
				'is_up'		=> true
			);
		}
		
		return $aHandlers;
	}
	
	/**
	 * Checks if at least one monitoring handler has failed
	 * @return boolean
	 */
	public function hasErrors()
	{
		return $this->hasErrors;
	}
	
	public static function setExceptionHandler()
	{
		$scriptName = $_SERVER['REQUEST_URI'];
		if(stripos($scriptName, 'jvmonitoring') !== false)
		{
			require_once('lib/ezutils/classes/ezexecution.php');
			eZExecution::addFatalErrorHandler( 'JVMonitoring', 'setExceptionHandler' ); // Hack to force exception handler
			
			restore_exception_handler();
			include_once('extension/jvmonitoring/classes/jvmonitoringexception.php');
			set_exception_handler(array('JVMonitoringException', 'handleUncaughtException'));
		}
	}
	
	public static function addException(Exception $e)
	{
		self::$exceptions[]=$e;
	}
}