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
              var image = document.getElementById('image');
              image.src = 'https://graph.facebook.com/' + user.id + '/picture';
              var name = document.getElementById('name');
              
              name.innerHTML = user.name
              
           }
         });
       </script>
           
           <div align="center">Hey,
           <img id="image"/>
           <div id="name"></div> pick a crime below to play.
           </div>
           <div align="center">
           <a href="javascript:FB.ui({ method: 'feed', 
            message: 'Facebook for Websites is super-cool'})">Tell your friends.</a>
            </div>
<?php    include 'footer.php'; ?>