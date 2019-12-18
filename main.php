<? include('./head.php');?>
<? profile_wrap($profile);?>
 <div class='wraper mainflex'>
      <main>
<? 
favwords();
favuser();
favhash();
favemo();?>      
      </main>
      <aside>
      <div class='textwrp wwrp'>
      <? favtwit();?>  
      </div>  
      </aside>
 </div>     
<? include('./foot.php');?>