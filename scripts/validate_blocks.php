#!/usr/bin/php
<?php
	/* script to validate blocks */

	// Change to working directory
	chdir(dirname(__FILE__));

	// Include all settings and classes
	require_once('shared.inc.php');	

	if ( $bitcoin->can_connect() !== true )
	  die("Failed to connect to RPC server\n");
	  
	echo "Validating blocks in database against coind..\n";
	
	$mask = "| %6s | %8s | %13s | %20s | %10s | \n";
	printf($mask, 'DB-ID', 'Height', 'Confirmations', 'Time', 'Status');
	  
	// fetch all blocks
	$allBlocks = $block->getAll();
	foreach ($allBlocks as $block) 
	{
		//print_r($block);
		$status = 'VALID';
		
		try {
		  $blockInfo = $bitcoin->getblock($block['blockhash']);		  
		}
		catch(Exception $e)
		{
			if($block['confirmations']== -1)
			{
				$status = 'ORPHAN';
			}			
			else if($e->getMessage() == 'RPC call did not return 200: HTTP error: 500 - JSON Response: [-5] Block not found')
			{
				$status = 'INVALID';
			}
			else 
			{
				$status = 'UNKNOWN';	
			}
		}
		finally {
			printf($mask, $block['id'], $block['height'], $block['confirmations'], strftime("%Y-%m-%d %H:%M:%S", $block['time']), $status);
		}
	}

	echo "Done..\n";
?>