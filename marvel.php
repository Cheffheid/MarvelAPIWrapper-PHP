<?php

	// Helpers
	require_once('MarvelAPI.php');

	// Set constants
	define( 'ABS_PATH', dirname(__FILE__) . '/');

	// Check for local settings, defines API_KEY and PRIV_KEY
	if ( ABS_PATH . 'local_settings.php' ) {
		require_once('local_settings.php');	
	} else {
		echo "local_settings.php file not found.";
	}
	
	$type = "characters";
	
	$marvel = new MarvelAPI();
	$marvel->setPubKey(API_KEY);
	$marvel->setPrivKey(PRIV_KEY);

	echo $marvel->base_url;
	$characters = $marvel->makeRequest($type, 1000);

    var_dump($characters);

    //echo $characters["status"];


