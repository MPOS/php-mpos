<?php
/**
 * block.t.php - Smarty gettext block plugin
 *
 * ------------------------------------------------------------------------- *
 * This library is free software; you can redistribute it and/or             *
 * modify it under the terms of the GNU Lesser General Public                *
 * License as published by the Free Software Foundation; either              *
 * version 2.1 of the License, or (at your option) any later version.        *
 *                                                                           *
 * This library is distributed in the hope that it will be useful,           *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of            *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU         *
 * Lesser General Public License for more details.                           *
 *                                                                           *
 * You should have received a copy of the GNU Lesser General Public          *
 * License along with this library; if not, write to the Free Software       *
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA *
 * ------------------------------------------------------------------------- *
 *
 * Installation: simply copy this file to the smarty plugins directory.
 *
 * @package	smarty-gettext
 * @version	$Id: block.t.php,v 1.1 2005/07/27 17:58:56 sagi Exp $
 * @link	http://smarty-gettext.sourceforge.net/
 * @author	Sagi Bashari <sagi@boom.org.il>
 * @copyright 2004-2005 Sagi Bashari
 */
 
/**
 * Replaces arguments in a string with their values.
 * Arguments are represented by % followed by their number.
 *
 * @param	string	Source string
 * @param	mixed	Arguments, can be passed in an array or through single variables.
 * @returns	string	Modified string
 */
function smarty_gettext_strarg($str)
{
	$tr = array();
	$p = 0;

	for ($i=1; $i < func_num_args(); $i++) {
		$arg = func_get_arg($i);
		
		if (is_array($arg)) {
			foreach ($arg as $aarg) {
				$tr['%'.++$p] = $aarg;
			}
		} else {
			$tr['%'.++$p] = $arg;
		}
	}
	
	return strtr($str, $tr);
}

/**
 * Smarty block function, provides gettext support for smarty.
 *
 * The block content is the text that should be translated.
 *
 * Any parameter that is sent to the function will be represented as %n in the translation text, 
 * where n is 1 for the first parameter. The following parameters are reserved:
 *   - escape - sets escape mode:
 *       - 'html' for HTML escaping, this is the default.
 *       - 'js' for javascript escaping.
 *       - 'url' for url escaping.
 *       - 'no'/'off'/0 - turns off escaping
 *   - plural - The plural version of the text (2nd parameter of ngettext())
 *   - count - The item count for plural mode (3rd parameter of ngettext())
 */
function smarty_block_t($params, $text, &$smarty)
{
	if ( ! isset($text) ) {
		return $text;
	}
	
	$text = stripslashes($text);
	
	// set escape mode
	if (isset($params['escape'])) {
		$escape = $params['escape'];
		unset($params['escape']);
	}
	
	// set plural version
	if (isset($params['plural'])) {
		$plural = $params['plural'];
		unset($params['plural']);
		
		// set count
		if (isset($params['count'])) {
			$count = $params['count'];
			unset($params['count']);
		}
	}
	
	// use plural if required parameters are set
	if (isset($count) && isset($plural)) {
		$text = ngettext($text, $plural, $count);
	} else { // use normal
		$text = gettext($text);
	}

	// run strarg if there are parameters
	if (count($params)) {
		$text = smarty_gettext_strarg($text, $params);
	}

	if (!isset($escape) || $escape == 'html') { // html escape, default
	   $text = nl2br(htmlspecialchars($text));
   } elseif (isset($escape)) {
		switch ($escape) {
			case 'javascript':
			case 'js':
				// javascript escape
				$text = str_replace('\'', '\\\'', stripslashes($text));
				break;
			case 'url':
				// url escape
				$text = urlencode($text);
				break;
		}
	}
	
	return $text;
}

?>
