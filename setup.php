<?php
	php_sapi_name() === 'cli' or die('not allowed on web server');
	include('config.php');
	
	require_once 'jsonRPCClient.php';
	$client = new jsonRPCClient('http://' . $rpc['login'] . ':' . $rpc['password'] . '@' . $rpc['ip'] . ':' . $rpc['port'] . '/') or die('Error: could not connect to RPC server.');
	$client->importprivkey($config['privkey'], $config['ponziacc']);
	
	echo 'Done.';
?>