<?php

$vars = array();

if ($_REQUEST["id"] === "1") {
	$dataset = 'agmerra';
	$methBCList = '1';
	$varlist = 'pr';
	$rcpList = 'rcp45';
	$lon = '-73.5';
	$lat = '3.4';
	$gcmlist = 'bcc_csm1_1';
	$statList = '1';
	$file = '';
	$delimit = '';
	$order = '100';
	$vars = $_REQUEST;

	$vars['dataset'] = $dataset;
	$vars['methBCList'] = $methBCList;
	$vars['varlist'] = $varlist;
	$vars['periodh'] = $_REQUEST["periodh"];
	$vars['period'] = $_REQUEST["period"];
	$vars['rcpList'] = $rcpList;
	$vars['lon'] = $_REQUEST["lon"];
	$vars['lat'] = $_REQUEST["lat"];
	$vars['gcmlist'] = $gcmlist;
	$vars['statList'] = $statList;
	$vars['file'] = $file;
	$vars['delimit'] = $delimit;
	$vars['order'] = $_REQUEST["order"];
	$vars['email'] = $_REQUEST["email"];
	$vars['scenarios-acronym'] = 'rcp45';
	$vars['variables-acronym'] = 'prec';
	$vars['observation-acronym'] = 'agmerra';
	$vars['formats-name'] = 'na';
	$vars['fileSet-acronym'] = 'tap';
	$vars['email_ver'] = 'jaime.tm8@gmail.com';

	// $Date_Submitted=date("d-M-Y h:i:s");
	// $vars["Date_Submitted"]=$Date_Submitted;

} else {
	$vars['lat'] = 4.5830;
	$vars['lon'] = -75.4453;
	$vars['fileSet'] = 1;
	$vars['scenarios'] = 2;
	$vars['model'] = "bcc_csm1_1";
	$vars['observation'] = 3;
	$vars['periodh'] = "1980;1990";
	$vars['period'] = "2020;2030";
	$vars['variables'] = 2;	
	$vars['variables-acronym']='prec';
    $vars['scenarios-acronym']='rcp45';
	$vars['methods'] = 1;
	$vars['formats'] = 1;
	$vars['file'] = false;
	$vars['observation-acronym'] = 'AgMERRA';
	$vars['delimitator'] = 'tab';
	$vars['email'] = 'h.sotelo@cgiar.org';
	$vars['email_ver'] = 'h.sotelo@cgiar.org';
	$vars['order'] = 1;
}

$url = "http://localhost/bias_correction.php";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_POST, count($vars));
// curl_setopt($curl, CURLOPT_POSTFIELDS, $vars);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($vars));
curl_setopt($curl, CURLOPT_TIMEOUT, 4);
$data = curl_exec($curl);
curl_close($curl);

print_r($data);

?>