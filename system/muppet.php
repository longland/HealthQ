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
           <h1>Schoolboy error!</h1>
           <div align="center">Hey,
           <img id="image"/>
           <div id="name"></div> unlucky this time :(
           </div>
           <div align="center">
           <a href="javascript:FB.ui({ method: 'feed', 
            message: 'Facebook for Websites is super-cool'})">Ask your friends for help?</a>
            </div>
<?php    include 'footer.php'; ?>