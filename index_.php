<?php
session_start();
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
define('CONSUMER_KEY', 'VqBfpmbEtAEPejdeOz2CQ'); 	// add your app consumer key between single quotes
define('CONSUMER_SECRET', 'wvX0fu8dsbx7iPMEbqC3beMszsDG7xGMFzJFTgQKGjg');
define('OAUTH_CALLBACK', 'https://petadata.xyz/callback.php');
if (!isset($_SESSION['access_token'])) {
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
	//echo $url;
	echo "<a href='$url'><img src='twitter-login-blue.png' style='margin-left:4%; margin-top: 4%'></a>";
} else {
	$access_token = $_SESSION['access_token'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
//	$user = $connection->get("account/verify_credentials", ['include_email' => 'true']);
$username=isset($_REQUEST['u'])?$_REQUEST['u']:null;
$trends = $connection->get('statuses/user_timeline', array('screen_name' => $username,'count' => 200,'tweet_mode' =>'extended','include_rts' =>false));
$tr=$array = json_decode(json_encode($trends), true);
$m1=count($tr);
$last=$tr[($m1-1)]['id_str'];
$trends2=$connection->get('statuses/user_timeline', array('screen_name' => $username,'count' => 200,'max_id' =>$last,'tweet_mode' =>'extended', 'include_rts' =>false));
$tr2 = json_decode(json_encode($trends2), true);
$m2=count($tr2);
$last2=$tr2[($m2-1)]['id_str'];
$trends3=$connection->get('statuses/user_timeline', array('screen_name' => $username,'count' => 200,'max_id' =>$last2,'tweet_mode' =>'extended','include_rts' =>false ));
$tr3 = json_decode(json_encode($trends3), true);
$m3=count($tr3);
$last3=$tr3[($m3-1)]['id_str'];
$trends4=$connection->get('statuses/user_timeline', array('screen_name' => $username,'count' => 200,'max_id' =>$last3,'tweet_mode' =>'extended', 'include_rts' =>false));
$tr4 = json_decode(json_encode($trends4), true);
$m4=count($tr4);
$last4=$tr4[($m4-1)]['id_str'];
$trends5=$connection->get('statuses/user_timeline', array('screen_name' => $username,'count' => 200,'max_id' =>$last4,'tweet_mode' =>'extended', 'include_rts' =>false));
$tr5=json_decode(json_encode($trends5), true);
$m5=count($tr5);
$last5=$tr5[($m5-1)]['id_str'];
$trends6=$connection->get('statuses/user_timeline', array('screen_name' => $username,'count' => 200,'max_id' =>$last5,'tweet_mode' =>'extended', 'include_rts' =>false));
$tr6=json_decode(json_encode($trends6), true);
header('Content-Type: application/json');
echo json_encode(array_merge($tr,$tr2,$tr3,$tr4,$tr5,$tr6));
 						//These are the sets of data you will be getting from Twitter 												Database 
}