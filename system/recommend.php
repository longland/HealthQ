 <?php include "header.php"; ?>
 <div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js">
      </script>
      <script>
         FB.init({ 
            appId:'175392325877131', cookie:true, 
            status:true, xfbml:true 
         });

         FB.ui({ method: 'feed', 
            message: 'Facebook for Websites is super-cool'});
      </script>
      
      <?php include "footer.php"; ?>