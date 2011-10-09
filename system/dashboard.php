<?php    include 'header.php'; ?>
      <div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js">
      </script>
      <script>
         FB.init({ 
            appId:'175392325877131', cookie:true, 
            status:true, xfbml:true 
         });
         FB.api('/me', function(user) {
           if(user != null) {
              var name = document.getElementById('name');
              
              name.innerHTML = user.name + ',';
              
           }
         });
       </script>
           
           
           <div align="center">
           
            </div>
<?php    include 'footer.php'; ?>

<div class="container">
      <header>
  <a href="/"><img alt="Riddled with Crime" class="round" src="http://health.itza.uk.com/extras/logo.png" /></a>

  <nav class="round">
    <ul>
      <li><a href="/">Home</a></li>
      <li><a href="/help">Help</a></li>
      <li><a href="#">Log out</a></li>
    </ul>
  </nav>
</header>

      <section class="round">
      
   <h1>Hey <span id="name"></span> help us solve a crime today!</h1>
   
    <p>
      Solve a crime from your local area to unlock that location. Unlock more locations to win
      entire sections of London. Compete with your friends to see who will rule the city!
    </p>
    <h2>Nearby crimes</h2>
    <p>
	<?php
		$crimetype = $this->db->query("SELECT TYPE_NAME FROM CRIME_TYPES WHERE TYPE_ID = " . $data["type"] . " LIMIT 1;");
		$crimetype = $crimetype->fetch_assoc();
		$crimetype = $crimetype["TYPE_NAME"];
	?>
    We found <?=mt_rand(5,15)?> crimes around you and need your help solving the <?=$crimetype?> crime. Click
    solve to unravel the mystery and become the owner of this location.
    </p>
 <a href="http://health.itza.uk.com/crime/<?=$data["action"]?>/" class="signup_button round">Solve</a>
  

		
      </section>
      <footer>
  
  <nav class="round">
   <br />
    <?php require_once "stats.php"; ?>
    <a href="javascript:FB.ui({ method: 'feed', 
            message: 'Facebook for Websites is super-cool'})" class="tell_button round">Tell your friends.</a>
    <br />
  </nav>

</footer>

      
    </div>
