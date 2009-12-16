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

class JVMonitoringDatabaseHandler implements IJVMonitoringHandler
{
	private $errorMsg;
	
	/**
	 * @var eZDBInterface
	 */
	private $db;
	
	public function __construct()
	{
		
	}
	
	public function setParams(array $args)
	{
		return;
	}
	
	public function isUp()
	{
		try
		{
			$this->db = eZDB::instance(false, false, true);
			$isUp = $this->db->isConnected();
			$this->cleanup();
			$this->checkInsert();
			$this->checkDelete();
			$this->checkSelect();
		}
		catch(Exception $e)
		{
			$isUp = false;
			$this->errorMsg = $e->getMessage();
		}
		
		return $isUp;
	}
	
	public function getErrorMessage()
	{
		return $this->errorMsg;
	}
	
	/**
	 * Checks if current DB connection can do delete statements. Throws an exception if not
	 * @return void
	 * @throws JVMonitoringException
	 */
	private function checkDelete()
	{
		$resultDelete = $this->db->query('DELETE FROM `ezsite_data` WHERE `name` = "jvmonitoring"');
		if(!$resultDelete)
			throw new JVMonitoringException($this->db->errorMessage());
	}
	
	/**
	 * Checks if current DB connection can do insert statements. Throws an exception if not
	 * @return void
	 * @throws JVMonitoringException
	 */
	private function checkInsert()
	{
		$resultInsert = $this->db->query('INSERT INTO `ezsite_data` (`name`, `value` ) VALUES("jvmonitoring", "test")');
		if(!$resultInsert)
			throw new JVMonitoringException($this->db->errorMessage());
	}
	
	/**
	 * Checks if current DB connection can do select statements. Throws an exception if not
	 * @return void
	 * @throws JVMonitoringException
	 */
	private function checkSelect()
	{
		$resultSelect = $resultSelect = $this->db->query('SELECT * FROM `ezsite_data`');
		if(!$resultSelect)
			throw new JVMonitoringException($this->db->errorMessage());
	}
	
	public function cleanup()
	{
		$this->checkDelete();
	}
}