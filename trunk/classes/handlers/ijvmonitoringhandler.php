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

interface IJVMonitoringHandler
{
	/**
	 * Sets parameters for handler. Parameters handling is up to the handler
	 * @param array $args Associative array. Key is the param name, value is its value (of course !)
	 * @return void
	 */
	public function setParams(array $args);
	
	/**
	 * Checks if tested service is up and running
	 * @return boolean
	 */
	public function isUp();
	
	/**
	 * In case of down service, this method should return an error string that will be displayed on monitoring page
	 * @return string
	 */
	public function getErrorMessage();
}