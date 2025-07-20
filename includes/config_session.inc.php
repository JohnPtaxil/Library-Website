<?php
// File for session_start() such that session security is increased

// Make session IDs only passable through cookies (prevents session fixation)
ini_set('session.use_only_cookies', 1);

// Makes session IDs more complicated upon creation and only allows session IDs created by server website
ini_set('session.use_strict_mode', 1);

// Associative Array for cookie parameters
session_set_cookie_params
([
	'lifetime' => 1800, // 30 Minutes in seconds
	'domain' => 'localhost', // Server
	'path' => '/', // Works for any subfile / subdirectories
	'secure' => true, // Only run cookie in a secure website (http websites)
	'httponly' => true, // Restricts script access for user (SQL injection prevention)
]);

// Start session
session_start();

// Regenerate session ID
session_regenerate_id(true);

// If first time starting session
if (!isset($_SESSION['last_regeneration']))
{
	regen_id();
}

// If not first time starting session
else
{
	// Set time interval to 30 minutes (60 seconds * 30 minutes)
	$interval = 60 * 30;
	
	// If current time - time of last regen reaches the time interval
	if (time() - $_SESSION['last_regeneration'] >= $interval)
	{
		regen_id();
	}
}

// Function to regenerate session ID
function regen_id()
{
	// Regenerate the session ID
	session_regenerate_id(true);
	
	// Reset the last regen time to current time
	$_SESSION['last_regeneration'] = time();
}