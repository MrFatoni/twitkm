<?
$user_timeline=json_decode(file_get_contents('./d/'.$username.'.json'),true);
$emo=json_decode(file_get_contents('./emo.json'),true);
$egb=[];$ekode=[];
foreach ($emo as $value) {$egb[]=$value['emo'];$ekode[]=$value['kode'];$emot[$value['kode']]=$value['emo'];}

$ign="dan|yang|yg|ke|di|dari|dr|dengan|dg|the|ini|itu|and|dan|atau|juga|akan|pada|saja|aja|rt|tp|tapi|sih|nih|dah|deh|ya|ndak|nggak|ga|gak|tidak|ngga|jg|juga|jd|jadi|bs|bgt|ada|n|klo|sih|ky|untuk|dalam|tentang|kalau|lebih|oleh|hanya|lagi|belum|udah|paling|adalah|mbak|mas|bro|atas|para|baru|biar|agar|sama|besar|bagi|sudah|dulu|ingin|pengen|depan|blkg|belakang|dpn|bukan|tidak|jangan|naik|turun|sedikit|dikit|dkt|byk|banyak|bisa|buat|bbrp|sebagai|kalo|&amp;";
$ignore=explode('|',$ign);


$profile=$user_timeline[0]['user'];
$usern=$user_timeline[0]['user']['screen_name'];
// ekstrak profile
function profile_wrap($profile){
$id=$profile['id'];
$id_str=$profile['id_str'];
$name=$profile['name'];
$screen_name=$profile['screen_name'];
$description=$profile['description'];
$followers_count=$profile['followers_count'];
$friends_count=$profile['friends_count'];
$listed_count=$profile['listed_count'];
$created_at=$profile['created_at'];
$favourites_count=$profile['favourites_count'];
$verified=$profile['verified'];
$statuses_count=$profile['statuses_count'];
$lang=$profile['lang'];
$profile_image_url=$profile['profile_image_url'];
$profile_image_url_https=str_replace('_normal', '', $profile['profile_image_url_https']);
$has_extended_profile=$profile['has_extended_profile'];
echo "
<div class='header'><div class='profwrp'>
<div class='profpic'><img src='$profile_image_url_https'></div>
<div class='profdesc'><h3><span class='name'>$name</span> <span class='screen_name'>$screen_name</span></h3><p class='$lang'>$description</p></div>
<div class='profstat'>
<div class='status'><span>$statuses_count</span></div>
<div class='flwr'><span>$followers_count</span></div>
<div class='flwng'><span>$friends_count</span></div>
</div></div></div>
";

}

function ekstraksi($user_timeline){
$words='';
$twit=[];
foreach($user_timeline as $key => $f):
$twit[]=$f;
$words .=preg_replace('/\s+/', ' ', $f['full_text']);
$kal[]=preg_replace('/\n\n\n\s+/', ' ', $f['full_text']);
if(isset($f['entities']['user_mentions'])&&!empty($f['entities']['user_mentions']))
{foreach($f['entities']['user_mentions'] as $m){ $men[]=$m['screen_name'];} $usermention=$men; }
if(isset($f['entities']['hashtags'])&&!empty($f['entities']['hashtags']))
{foreach($f['entities']['hashtags'] as $h){	$has[]=$h['text'];} $hashtag=$has;}
if(isset($f['entities']['media'])&&!empty($f['entities']['media']))
{foreach($f['entities']['media'] as $m){$med[]=$m;} $media=$med;}
endforeach;	

$counts = array_count_values(array_map('trim',explode(' ',$words)));
foreach(explode(' ',$words) as $d){$kata[$d]=$counts[$d];} arsort($kata);
usort($twit, function($a, $b) {return ($b['retweet_count']+$b['favorite_count']) - ($a['retweet_count']+$a['favorite_count']);});
$mention = array_count_values($usermention);arsort($mention);
$hashtags = array_count_values($hashtag);arsort($hashtags);
return array($kata,$mention,$hashtags,$twit);
}

list($kata,$mention,$hashtags,$twit)=ekstraksi($user_timeline);

// #werp kata paling sering digunakan
function favwords(){
global $kata, $emot, $ignore;$i=0;
$wraper="<div class='kata'><h3>Kata yang paling sering digunakan</h3><div>";
foreach ($kata as $k => $value) {
$ke=str_replace('.','',$k);	$key=str_replace(array_values($emot), '', $ke);
if($i<=100 && !in_array($key,$ignore) && strpos($key,'@') === false && strlen($key)>3){
$wraper .= "<span class='word countr' data-type='word' data-val='$key' data-key='$value'>$key </span>";
$i++;}}
$wraper .= "</div></div>";
echo $wraper;
}

function favemo(){
global $kata, $emot;	
$ewrp="<div class='emot'><h3>Emoticon Favorit</h3><div>";
foreach ($kata as $k => $value) {if(in_array(trim($k),array_values($emot))){
$kod=array_search($k,$emot);$ewrp .="<span  class='word countr' data-type='emot' data-key='$value' data-val='$value' data-code='$kod'>$k</span>";}}
$ewrp .= "</div></div>";echo $ewrp;
}

function favuser(){global $mention;	$m=0;
$uwrp="<div class='users'><h3>User paling sering disebut/berinteraksi</h3><div>";
foreach ($mention as $u => $c) {if($m<20){$uwrp .="<span  class='mention countr' data-type='mention'  data-key='$c'>$u</span>";$m++;}} 
$uwrp .= "</div></div>";echo $uwrp;
}

//hashtag 
function favhash(){ global $hashtags;$h=0;
$hwrp="<div class='hash'><h3>Hastags paling sering digunakan</h3><div>";
foreach ($hashtags as $h => $hc) {if($h<20){$hwrp .="<span  class='hashtag countr' data-type='hashtag' data-val='$hc' data-key='$hc'>$h</span>";$h++;}}
$hwrp .= "</div></div>";echo $hwrp;
}


function favtwit(){
global $twit;	

$tc=0;
$twlist="<div class='twit'><h3>Twit dengan interaksi terbanyak</h3><div class='wwrp'>";
foreach ($twit as $key => $value) {
if($tc<=4){
$date = new DateTime($value['created_at']);
$dt= $date->format( 'd/m/y' );
$usern=$value['user']['screen_name'];
$twlist.="
<div class='twr'>
<div class='twh'><span class='uname'>@".$usern."</span> &mdash; 
<a href='https://twitter.com/".$usern."/status/".$value['id_str']."' class='date' target='_blank' rel='nofollow'>".$dt."</a></div>
<div class='text' data-lang='".$value['lang']."'>".nl2br($value['full_text'])."</div>
<div class='stat'>
<div class='retweet'><span>".$value['retweet_count']."</span></div>
<div class='favorite'><span>".$value['favorite_count']."</span></div>
</div>
</div>
";
$tc++;
}}
$twlist.="</div></div>";
echo $twlist;
}
// end of #twlist

include "./main.php";
//var_dump($hashtags);
?>




