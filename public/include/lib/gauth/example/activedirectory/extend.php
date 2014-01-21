<?php

require_once("../../lib/ga4php.php");

// TODO: This code works, but needs to be fixed and commented properly
// TODO: setup encryption into AD


// define our token class
class myGA extends GoogleAuthenticator {
	function getData($username) {
		global $dsconnect, $host, $binduser, $bindpass, $basecn;
		
		// set this to default to begin with
		$tokendata = false;
		
		// we search for a username that matches what we've been passed
		$sr = ldap_search($dsconnect, "$basecn", "samaccountname=$username");
		$info = ldap_get_entries($dsconnect, $sr);
		
		//echo "<pre>";
		//print_r($info);
		//echo "</pre>";
		
		$attr_name = false;
		for($i=1; $i<15; $i++) {
			$valname = "extensionattribute$i";
			if(isset($info[0]["$valname"][0])) {
				$val = $info[0]["$valname"][0];
				// we are looking for an extension attribute that has a start of "ga4php"
				if(preg_match('/^ga4php.*/', $val)>0) {
					$attr_name = $valname;
				}
			}
			
		}
		
		// yeah, totally works.... HAH
		if($attr_name != false) {
			$tokend = $info[0]["$attr_name"][0];
			$expl = explode(":", $tokend);
			$tokendata = $expl[1];
		}
				
		return $tokendata;
		
		// and there you have it, simple eh?
	}
	
	
	// now we need a function for putting the data back into our user table.
	// in this example, we wont check anything, we'll just overwrite it.
	function putData($username, $data) {
		global $dsconnect, $host, $binduser, $bindpass, $basecn;
		
		if($data!="") {
			$data .= "ga4php:";
		}
		
		// set this to default to begin with
		$tokendata = false;
		
		// we need to track the "first" blank attribute
		$blank_attr = false;
		
		// we search for a username that matches what we've been passed
		$sr = ldap_search($dsconnect, "$basecn", "samaccountname=$username");
		$info = ldap_get_entries($dsconnect, $sr);
		$dn = $info[0]["distinguishedname"][0];
		
		//echo "<pre>";
		//print_r($info);
		//echo "</pre>";
		
		$attr_name = false;
		for($i=1; $i<15; $i++) {
			$valname = "extensionattribute$i";
			if(isset($info[0]["$valname"][0])) {
				$val = $info[0]["$valname"][0];
				// we are looking for an extension attribute that has a start of "ga4php"
				if(preg_match('/^ga4php.*/', $val)>0) {
					$attr_name = $valname;
				}
			} else {
				if($blank_attr == false) {
					// this will cathc the first unset extension variable name, if we need it
					$blank_attr = "$valname";
				}
			}
			
		}
		
		// if the attr_name is not set, we need to set $blank_attr
		if($attr_name == false) {
			// we use $blank_attr
			error_log("setting for $username, $blank_attr");
			$infod["$blank_attr"][0] = "$data";
		} else {
			error_log("setting for $username, $attr_name");
			$infod["$attr_name"][0] = "$data";
		}
		
		error_log("att end of put data for $dn, $infod");
		
		return ldap_modify($dsconnect, $dn, $infod); 
		// even simpler!
	}
	
	// not implemented yet
	function getUsers() {
		return false;
	}
}

?>
