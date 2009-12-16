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

class JVMonitoringHTTPHandler implements IJVMonitoringHandler
{
	private $testURL;
	private $errorMsg;
	
	public function __construct()
	{
		
	}
	
	public function setParams(array $args)
	{
		if(isset($args['url']))
			$this->testURL = $args['url'];
	}
	
	public function isUp()
	{
		if(!$this->testURL)
			throw new InvalidArgumentException(__CLASS__." : An URL must be provided in jvmonitoring.ini for this handler");
		
		// Proxy config
		$ini = eZINI::instance('site.ini');
		$proxy = $ini->hasVariable( 'ProxySettings', 'ProxyServer' ) ? $ini->variable( 'ProxySettings', 'ProxyServer' ) : false;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->testURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if ( $proxy )
		{
			curl_setopt ( $ch, CURLOPT_PROXY , $proxy );
			$userName = $ini->hasVariable( 'ProxySettings', 'User' ) ? $ini->variable( 'ProxySettings', 'User' ) : false;
			$password = $ini->hasVariable( 'ProxySettings', 'Password' ) ? $ini->variable( 'ProxySettings', 'Password' ) : false;
			if ( $userName )
			{
				curl_setopt ( $ch, CURLOPT_PROXYUSERPWD, "$userName:$password" );
			}
		}
		
		if(!curl_exec($ch))
		{
			$isUp = false;
			$this->errorMsg = curl_error($ch);
		}
		else
		{
			$isUp = true;
		}
		
		return $isUp;
	}
	
	public function getErrorMessage()
	{
		return $this->errorMsg;
	}
}