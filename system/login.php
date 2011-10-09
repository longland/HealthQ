<?php

define('YOUR_APP_ID', '175392325877131');
define('YOUR_APP_SECRET', '982a178a39076f0ad1ea5cba1e22ae38');

function get_facebook_cookie($app_id, $app_secret) {
  $args = array();
  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
  ksort($args);
  $payload = '';
  foreach ($args as $key => $value) {
    if ($key != 'sig') {
      $payload .= $key . '=' . $value;
    }
  }
  if (md5($payload . $app_secret) != $args['sig']) {
    return null;
  }
  return $args;
}

$cookie = get_facebook_cookie(YOUR_APP_ID, YOUR_APP_SECRET);

$user = json_decode(file_get_contents(
    'https://graph.facebook.com/me?access_token=' .
    $cookie['access_token']));

?>
<html>
  <body>
<div class="container">
      <header>
  <a href="/"><img alt="Riddled with Crime" class="round" src="http://health.itza.uk.com/extras/logo.png" /></a>
  </header>
  
  <section class="round">
<h2>Welcome to Riddled with Crime</h2>
<p>Please log in via facebook to start solving crimes close to you.</p>


    <?php if ($cookie) { ?>
      <script type="text/javascript">

window.location.href = "http://health.itza.uk.com/dashboard";

</script>
    <?php } else { ?>
      <fb:login-button>Log in securely with Facebook</fb:login-button>
    <?php } ?>
    <div id="fb-root"></div>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script>
      FB.init({appId: '<?= YOUR_APP_ID ?>', status: true,
               cookie: true, xfbml: true});
      FB.Event.subscribe('auth.login', function(response) {
        window.location.reload();
      });
    </script>
    </section>
</div>
  </body>
</html>