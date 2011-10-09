 <?php include "header.php"; ?>
 <div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js">
      </script>
      <script>
         FB.init({ 
            appId:'YOUR_APP_ID', cookie:true, 
            status:true, xfbml:true 
         });

         FB.ui({ method: 'feed', 
            message: 'Facebook for Websites is super-cool'});
      </script>
      
      <?php include "footer.php"; ?>