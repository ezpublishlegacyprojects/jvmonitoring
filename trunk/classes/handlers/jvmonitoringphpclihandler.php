<?php
// SOFTWARE NAME: jvMonitoring
// SOFTWARE RELEASE: @@@VERSION@@@
// // COPYRIGHT NOTICE: Copyright (C) 2009 Jerome Vieilledent
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

class JVMonitoringPHPCLIHandler implements IJVMonitoringHandler
{
	private $phpBinary;
	
	public function __construct()
	{
		
	}
	
	public function setParams(array $args)
	{
		if(isset($args['phpbinary']))
			$this->phpBinary = $args['phpbinary']; 
	}
	
	public function isUp()
	{
		$systemString = $this->phpBinary.' -v';
		$output = shell_exec($systemString);
		if (preg_match("/cli/i", $output))
			$isUp = true;
		else
			$isUp = false;
		
		return $isUp;
	}
	
	public function getErrorMessage()
	{
		return 'PHP Binary '.$this->phpBinary.' unavailable';
	}
}