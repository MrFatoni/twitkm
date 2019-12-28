<? ini_set('session.gc_maxlifetime', 3600);session_start();
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
$sesi=isset($_SESSION['access_token'])?$sesi='login':$sesi='notlogin';
if(isset($_REQUEST['u'])){$page='user';$username=$_REQUEST['u'];} else if(isset($_REQUEST['e'])){$page='ekstrak';$username=$_REQUEST['e'];} else{$page='home';}
define('CONSUMER_KEY', 'yorcustomerkey'); 	// add your app consumer key between single quotes
define('CONSUMER_SECRET', 'yorcustomerkeysecret');
define('OAUTH_CALLBACK', 'https://yoursite/callback.php');
if ($sesi=='notlogin') {
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$login_url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
$loginlink="<a href='$login_url'><img src='twitter-login-blue.png' style='margin-left:4%; margin-top: 4%'></a>";
include('./login.php');
} else { // user login
$access_token = $_SESSION['access_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
function getuser($username,$lastid=''){ global $ot,$ots,$connection;
if(!empty($lastid)){
$var=array('screen_name' => $username,'count' => 200,'tweet_mode' =>'extended','include_rts' =>false,'max_id' =>$lastid);} else {
$var=array('screen_name' => $username,'count' => 200,'tweet_mode' =>'extended','include_rts' =>false,);}
$timeline = $connection->get('statuses/user_timeline',$var);
if(!empty($timeline)){$tl=json_decode(json_encode($timeline), true); return $tl;} else {  return null;}
}

function getlastid($tl){if(!empty($tl)){$m1=count($tl);$last=$tl[($m1-1)]['id_str'];return $last;} else {return 'none';}}

if(!isset($_SESSION['user'])){
$_SESSION['user']= $connection->get("account/verify_credentials", ['include_email' => 'true']);
}

if($page=='home'){ 
$user=json_decode(json_encode($_SESSION['user']), true);
$name=$user['name'];
$screen_name=$user['screen_name'];
$description=$user['description'];
$url=$user['url'];
$protected=$user['protected'];
$followers_count=$user['followers_count'];
$friends_count=$user['friends_count'];
$favourites_count=$user['favourites_count'];
$verified=$user['verified'];
$statuses_count=$user['statuses_count'];
$profile_image_url_https=$user['profile_image_url_https'];
$profpic=str_replace('_normal','', $profile_image_url_https);
ob_start(); 
echo "<div class='profwrp'>
<div class='profpic'><img src='$profpic'/></div>
<div class='profdesc'>
<p>$description</p>
</div>
<div class='profstat'>
<div class='status'><span>$statuses_count</span></div>
<div class='flwr'><span>$followers_count</span></div>
<div class='flwng'><span>$friends_count</span></div>
</div>
</div>";
$header = ob_get_clean(); 
include('./dash.php');

} else if($page=='ekstrak'){
$ot=$access_token['oauth_token'];
$ots=$access_token['oauth_token_secret'];

$file_json='./d/'.$username.'.json';
if (file_exists($file_json)) {
$user_timeline=json_decode(file_get_contents($file_json),true);
echo "<a href='/?u=$username'>Klik disini</a> untuk melihat hasil ekstraksi akun <a href='/?u=$username'>$username</a>";
}else {
$v=[];
for ($i=0; $i < 5; $i++) { 
if($i==0){$ar[$i]=getuser($username);$lastid=getlastid($ar[$i]);} else {$ar[$i]=getuser($username,$lastid);
$lastid=getlastid($ar[$i]);}
$v=array_merge($v,$ar[$i]);
}
$fp = fopen('./d/'.$username.'.json', 'w');
fwrite($fp, json_encode($v));
fclose($fp);
echo "<a href='/?u=$username'>Klik disini</a> untuk melihat hasil ekstraksi akun <a href='/?u=$username'>$username</a>";

}


}

else if($page=='user'){
include('./user.php');
}

}
