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
	  
	// fetch all blocks
	$allBlocks = $block->getAll();
	foreach ($allBlocks as $block) 
	{
		try {
		  $blockInfo = $bitcoin->getblock($block['blockhash']);		  
		}
		catch(Exception $e)
		{
			// don't report orphan blocks.
			if($block['confirmations']!= -1 && $e->getMessage() == 'RPC call did not return 200: HTTP error: 500 - JSON Response: [-5] Block not found')
			{
				echo "Block not found: database-id: $block[id] - height: $block[height].\n";
			}
		}
	}

	echo "Done..\n";
?>