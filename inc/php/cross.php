<?php
/**
*  crossDomain
* 
*  cURL's out to a supplied URL while passing the $_REQUEST global onto the 
*  endpoint allowing all supplied data to submit properly.
*
*   This is a simple utility script that could be improved upon, don't be 
*  afraid to do so.
*
*  @author Chris Chapman - twitter - @_chapman
*  @param  (str)$url A valid URL should be provided
*  @return (ary) or (false)
*/

function crossDomain($url = ''){
	// Reg Express for URLS
	$urlPattern = "~^(https?|ftp)://www\.([a-z\d.-]+(:[a-z\d.&%$-]+)*@)*((25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]\d|[1-9])\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]\d|[1-9]|0)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]\d|[1-9]|0)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]\d|\d)|([a-z\d-]+\.)+(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-z]{2}))(:\d+)*(/($|[\w.,?'\\+&%$#=\~-]+))*$~i";
	$validURL = preg_match($urlPattern, $url);
	if(!$validURL) return false;
	$data 			= $_REQUEST;

	// instance a new cURL
	$ch = curl_init();

	// configure the cURL
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// execute the cURL
	$result = curl_exec($ch);

	// close cURL
	curl_close($ch);

	return $result;
}
	
// Call crossDomain. If an error occured, force an error msg into $data;
if(!$data = crossDomain( $_REQUEST['url'] )){
	$data = json_encode( array('Error' => 'Request did not succeed.' ) );

} 

// We are about to echo out some json text, so we should set the content type of this file
header('Content-Type: application/json');

// display the content in a json format
echo $data;