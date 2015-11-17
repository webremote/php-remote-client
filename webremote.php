<?php
/*
This file is part of WebRemote.io PHP Remote Client.

    WebRemote.io PHP Remote Client is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WebRemote.io PHP Remote Client is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WebRemote.io PHP Remote Client.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * webremote.php - PHP Remote Client
 *
 * Version: 1.0.1002
 * 
 * For details: https://webremote.io/php-remote-client
 */

// Define authentication key, automatically generated or replaced by you
define('AUTHENTICATION', 'STRINGTHATISGOINGTOBEREPLACED');

// Setup headers
header('X-Powered-By: WebRemote/1.0');
header('Content-type: application/json');

// Check if the Authentication-header is correct
if (empty($_SERVER['HTTP_X_WEBREMOTE']) || $_SERVER['HTTP_X_WEBREMOTE'] != AUTHENTICATION) {
	exit('[]');
}

// Receive the task
if (!empty($_POST['task'])) {

	// Write to a temporary file
	$tempFP = tmpfile();
	fwrite($tempFP, $_POST['task']);

	// Start capturing output
	ob_start();
	include(stream_get_meta_data($tempFP)['uri']);
	$response = ob_get_contents();

	// Remote the task
	fclose($tempFP);

	// Output the task result
	if (is_array($response)) {
		$response = json_encode($response);
	}
	header('Content-length: ' . strlen((string)$response));
	exit($response);
}
header('Location: http://' . $_SERVER['REMOTE_HOST'] . '.weblets.webremote.io/');
exit;