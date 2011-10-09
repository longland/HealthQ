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
              
              name.innerHTML = user.name
              
           }
         });
       </script>
           
           
           
            
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
      
   <h1>Not just a pretty face are we, <span id="name"></span>?</h1>
   
    <p>
     Well done! You got the answer right.
    </p>
    
 <p>
 ...oh you're still here? What are you waiting for? A medal?
 </p>
 <p>
 Yes? Oh, ok, here you go..
 </p>
 <center><img align="center" src="http://health.itza.uk.com/extras/medal.png" /></center>
   
 <a href="http://health.itza.uk.com/" class="signup_button round">Continue</a>
  

		
      </section>
      <footer>
  
  <nav class="round">
   <br />
   <h2>Play with your friends. Ask them on Facebbok</h2>
    <a href="javascript:FB.ui({ method: 'feed', 
            message: 'Facebook for Websites is super-cool'})" class="tell_button round">Tell your friends.</a>
    <br />
  </nav>
<?php    include 'footer.php'; ?>
