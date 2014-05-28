<?php
// getting the fucking network diff... wtf
//echo dirname(__FILE__);
// Change to working directory
chdir(dirname(__FILE__)."/../../scripts/");

// Include all settings and classes
require_once('shared.inc.php');

switch ($_GET['w']) {
	case 'diff_mpos':
		echo file_get_contents('https://awesomehash.com/index.php?page=api&action=getdifficulty&api_key=3305940bf99102ef8d6a6476a7f9f9083d64c51128e2d958776176ecd0fac3da');
		break;
	case 'blocks':
		echo file_get_contents('https://awesomehash.com/index.php?page=api&action=getblocksfound&api_key=3305940bf99102ef8d6a6476a7f9f9083d64c51128e2d958776176ecd0fac3da');
		break;
	case 'status':
		echo file_get_contents('https://awesomehash.com/index.php?page=api&action=getpoolstatus&api_key=3305940bf99102ef8d6a6476a7f9f9083d64c51128e2d958776176ecd0fac3da');
		break;
	case 'diff':
		echo getCurrentDiff($bitcoin->getblocktemplate());
		break;
	default:
		echo file_get_contents('https://awesomehash.com/index.php?page=api&action=getdifficulty&api_key=3305940bf99102ef8d6a6476a7f9f9083d64c51128e2d958776176ecd0fac3da');
}

function getCurrentDiff($tmpArr)
{
	$bits = $tmpArr['bits'];
	$height = $tmpArr['height'];
	$exp = base_convert(substr($bits,0,2),16,10);
	$base = base_convert(substr($bits,2),16,10);
	$target = $base * pow(2,(8*($exp-3)));
	$diff = (65535 * pow(2,208))/$target;
	$sharediff = $diff * 65536;
	//echo $diff."<br>".$sharediff."<br>";
	echo '{"getdifficulty":{"version":"1.0.0","runtime":69.69,"data":'.$diff.'}}';
}
	
