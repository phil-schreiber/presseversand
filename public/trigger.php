#!/usr/bin/php -q

<?php
/**
 * For local execution the shebang might be required
 * 
 * This script is the push trigger. It can be called by the local cronjob 
 * 
 * Basic configuration of host and appID is necessary, only a correct combination of salt and appid will trigger the sending of push notifications
 * 
 * The salt is MD5ed and therefore remains secret.
 */

$data=array(
	'client_id' => 'sendtrigger',
	'client_secret' => 'X4lPahQud43tfojn'
);

$host="http://baywa-nltool.iq-pi.org/triggersend/generate/";



$process = curl_init($host);



curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($process, CURLOPT_POST , true);
curl_setopt($process, CURLOPT_POSTFIELDS , $data);


$return = curl_exec($process);
$curl_errno = curl_errno($process);
$curl_error = curl_error($process);

curl_close($process);
if ($curl_errno > 0) {
        echo "cURL Error ($curl_errno): $curl_error\n";
} else {
		//file_put_contents('tokens/tokens.txt', $return['access_token']);
        echo "Data received: $return\n";
}
die();
?>