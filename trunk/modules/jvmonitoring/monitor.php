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

/**
 * View for applicative monitoring
 *
 * @author Jerome Vieilledent
 * @copyright Jerome Vieilledent <http://www.lolart.net>
 * @version 1.0
 * @since 2009-12-04
 */

// Hide errors and XDebug exception traces
ini_set('display_errors','Off');
ini_set('xdebug.show_exception_trace', 'Off');

include_once("kernel/common/template.php");
$tpl = eZTemplate::factory();
$monitoringINI = eZINI::instance('jvmonitoring.ini');

$monitor = new JVMonitoring();
$aMonitorResult = $monitor->doMonitor();

// Final result, OK or KO
$finalResult = $monitoringINI->variable('GeneralSettings', 'GlobalStatusOK');
$hasErrors = $monitor->hasErrors();
if($hasErrors)
{
	$finalResult = $monitoringINI->variable('GeneralSettings', 'GlobalStatusKO');
	header( 'HTTP/1.x 500 Internal Server Error' );
}

$tpl->setVariable('monitoring_status', $finalResult);
$tpl->setVariable('has_errors', $hasErrors);
$tpl->setVariable('monitor_result', $aMonitorResult);

$Result['pagelayout'] = 'jvmonitoring/pagelayout_jvmonitoring.tpl';
$Result['content'] = $tpl->fetch('design:jvmonitoring/monitoringtests.tpl');
