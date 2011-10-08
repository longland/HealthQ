<?php
$data["body"] = "
<div class='login'>
<h2>Welcome to AetherCop</h2>
<p>Please log in via facebook to start solving crimes in the Aether.</p>
<div id=\"fb-root\"></div>
<script src=\"http://connect.facebook.net/en_US/all.js\"></script>
<script>
FB.init({ 
        appId:'175392325877131', cookie:true, 
        status:true, xfbml:true 
        });
</script>
<fb:login-button perms='email' redirect_uri='http://health.itza.uk.com/dashboard'>Login with Facebook</fb:login-button>
</div>
";
