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

class JVMonitoringImageHandler implements IJVMonitoringHandler
{
	/**
	 * @var eZINI
	 */
	private $imageINI;
	
	private $errorMessage;
	
	public function __construct()
	{
		
	}
	
	public function setParams(array $args)
	{
		$this->imageINI = eZINI::instance('image.ini');
		return; 
	}
	
	public function isUp()
	{
		try
		{
			$isUp = true;
			
			$this->testGD();
			if($this->imageINI->variable('ImageMagick', 'IsEnabled') === 'true')
				$this->testImageMagick();
		}
		catch(JVMonitoringException $e)
		{
			$isUp = false;
			$this->errorMessage = $e->getMessage();
		}
		
		return $isUp;
	}
	
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
	/**
	 * Checks if GD extension is loaded. Throws an exception if not
	 * @return void
	 * @throws JVMonitoringException
	 */
	private function testGD()
	{
		if(!extension_loaded('gd'))
			throw new JVMonitoringException("GD Extension not loaded !");
	}
	
	/**
	 * Checks if ImageMagick is available. Throws an exception if not
	 * @return void
	 * @throws JVMonitoringException
	 */
	private function testImageMagick()
	{
		$binary = $this->imageINI->variable('ImageMagick', 'ExecutablePath').'/'.$this->imageINI->variable('ImageMagick', 'Executable');
		$systemString = $binary.' -version';
		$output = shell_exec($systemString);
		if(stripos($output, 'ImageMagick') === false)
			throw new JVMonitoringException("ImageMagick 'convert' binary unavailable : $binary");
	}
}