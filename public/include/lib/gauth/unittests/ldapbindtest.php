<?php 

// just trying to test how extensionAttributes work in AD. they seem to be exactly what we're looking for in terms of a place to plonk our data
$host = ""; // for eg "1.2.3.4"
$binduser = ""; // for eg "administrator"
$bindpass = ""; // for eg "password"
$basecn = ""; // for eg "CN=users, DC=google, dc=com"

// this is here so i can keep my atributes somewhere in the tree and not have them float around on git/svn
if(file_exists("../../../.dontappearingitandsvn.php")) require_once("../../../.dontappearingitandsvn.php");

$ds = ldap_connect("$host", 389);

if($ds) {
	$r = ldap_bind($ds, "$binduser", "$bindpass");
	if($r) {
		echo "r is r\n";
	} else {
		echo "r is not r\n";
	}
	
	$sr = ldap_search($ds, "$basecn", "objectclass=user");
	
	if($sr) {
		echo "sr is sr\n";
	}
	
	$info = ldap_get_entries($ds, $sr);
	//$info["extensionattribute2"] = "-----";
	
	
	//print_r($info);
	$i = 0;
	foreach($info as $key => $val) {
		//echo "$key is ".$val["distinguishedname"][0]."\n";
		if($val["distinguishedname"][0] != "") {
			$user[$i]["dn"] = $val["distinguishedname"][0];
			$user[$i]["acn"] = $val["samaccountname"][0];
		}

		$i ++;
		//return 0;
	}
	
	foreach($user as $value) {
		$cn = $value["dn"];
		$sr2 = ldap_search($ds, "$cn", "objectclass=*");
		$info = ldap_get_entries($ds, $sr2);
		print_r($info);
		return 0;
	}
	
	print_r($user);
	
}

?>