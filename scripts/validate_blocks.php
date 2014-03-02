#!/usr/bin/php
<?php
	/* script to validate blocks */

	// Change to working directory
	chdir(dirname(__FILE__));

	// Include all settings and classes
	require_once('shared.inc.php');	

	if ( $bitcoin->can_connect() !== true )
	  die("Failed to connect to RPC server". PHP_EOL);
	  
	echo "Validating blocks in database against coind..". PHP_EOL;
	
	$mask = "| %6s | %8s | %13s | %20s | %10s |". PHP_EOL;;
	printf($mask, 'DB-ID', 'Height', 'Confirmations', 'Time', 'Status');
	  
	// fetch all blocks
	$allBlocks = $block->getAll();
	foreach ($allBlocks as $block) 
	{		
		try {
			if($block['confirmations']== -1) // mark orphan blocks.
				$status = 'ORPHAN';
			else 
			{				
				$blockInfo = $bitcoin->getblock($block['blockhash']);	
				$status = 'VALID'; // if the getblock() call didn't throw an exception, it's a valid block then.
			}									
		}
		catch(Exception $e)
		{
			if($e->getMessage() == 'RPC call did not return 200: HTTP error: 500 - JSON Response: [-5] Block not found')
			{
				$status = 'INVALID';
			}
			else 
			{
				$status = 'UNKNOWN';	
			}
		}
		
		printf($mask, $block['id'], $block['height'], $block['confirmations'], strftime("%Y-%m-%d %H:%M:%S", $block['time']), $status);
	}

	echo "Done..". PHP_EOL;
?>