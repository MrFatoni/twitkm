<footer>
 <div class='footer'>
<p><a href='/'><span class='brand'>TwitKamu</span></a> &copy; &mdash; 2019-2020 | Analisa Random berdasar Twitter user Timeline.</p>   
 </div> 

</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script> var data=<? echo json_encode($twit);?>; 

$(document).ready(function() {
$(document).on('click','.word',function(){
var type=$(this).attr('data-type');
if(type =='word'){var val=$(this).attr('data-val');} else 
if(type =='emot'){var val=$(this).text();}

var wwrp='';
$.each( data, function(i, word ) {
if (word.text.indexOf(val) >= 0) { 
wwrp +="<div class='twr' id='"+type+"'>"+
"<div class='text'>"+word.text+"</div>"+
"<div class='stat'><div class='retweet'><span>"+word.retweet_count+"</span></div><div class='favorite'><span>"+word.favorite_count+"</span></div>"+
"</div></div>";
}
});

$('.wwrp').html(wwrp);
});  

});






</script> 

</body>
</html>