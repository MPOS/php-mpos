<?php
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation, either version 3 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

//Set page starter variables//
$includeDirectory = "/sites/mmc/www/includes/";

//Include site functions
include($includeDirectory."requiredFunctions.php");


	// Update MtGox last price via curl, 3 second timeout on connection
	$mtgox_ticker = exec("/usr/bin/curl -q -s --connect-timeout 3 'https://mtgox.com/code/data/ticker.php'");
	if (!is_null($mtgox_ticker)) {
		$ticker_obj = json_decode($mtgox_ticker);
		if (intval($ticker_obj->ticker->last) > 0) {
			$settings->setsetting('mtgoxlast', round($ticker_obj->ticker->last, 4));
		}
	}

?>
