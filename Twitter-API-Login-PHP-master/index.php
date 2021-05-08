<?php

session_start();
require 'autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

define( 'CONSUMER_KEY', '' );
define( 'CONSUMER_SECRET', '' );
define( 'OAUTH_CALLBACK', '' );
if ( ! isset( $_SESSION['access_token'] ) ) {
	$connection                     = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET );
	$request_token                  = $connection->oauth( 'oauth/request_token', array( 'oauth_callback' => OAUTH_CALLBACK ) );
	$_SESSION['oauth_token']        = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$url                            = $connection->url( 'oauth/authorize', array( 'oauth_token' => $request_token['oauth_token'] ) );
	//echo $url;
	echo "<a href='$url'><img src='twitter-login-blue.png' style='margin-left:4%; margin-top: 4%'></a>";
} else {
	$access_token = $_SESSION['access_token'];
	$connection   = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret'] );
	$user         = $connection->get( "account/verify_credentials", [ 'include_email' => 'true' ] );
	?>


	<?php // Profile info
    echo "Din profil";
	echo "<br>";
	echo "<img src='$user->profile_image_url'>";
	echo "<br>";
	echo "Navn: ";
	echo $user->name;
	echo "<br>";
	echo "Lokasjon: ";
	echo $user->location;
	echo "<br>";
	echo "Opprettet: ";
	echo $user->created_at;
	echo "<br>";

	?>
    <p><strong>Kriterier for tweet</strong></p>
    <ul>
        <li>Tweet må ha en lengde på over 20 tegn</li>
        <li>Tweet kan være på maksimum 140 tegn</li>
        <li>Tweeten kan ikke inneholde bokstavene "Æ, Ø eller Å"</li>
    </ul>
    <input id="twitterbox" type="text" title="your Tweet here" onkeyup="if (event.keyCode == 13){verify_tweet();}">
    <input type="button" value="Tweet" onclick="verify_tweet()">
    <br>
    <br>


	<?php // tidligere tweets fra brukeren
	$statuses = $connection->get( "statuses/home_timeline", [ "count" => 200, "exclude_replies" => true ] );
	$counter  = 0;
	echo "LAST 10 TWEETS";
	foreach ( $statuses as $sss ) {
		if ( $user->id == $sss->user->id && $counter < 10 ) {
			echo "<br>--------------<br>";
			print_r( $sss->text );
			$counter ++;
		}
	}
	?>


    <script> // Sjekker om tweeten er i henhold til kriteriene
        function verify_tweet(events) {
            var tweetOK = true;
            if (document.getElementById("twitterbox").value.length < 20) {
                alert("Tweet er for kort");
                tweetOK = false;
            }
            if (document.getElementById("twitterbox").value.length > 140) {
                alert("Tweet er for lang");
                tweetOK = false;
            }
            if (document.getElementById("twitterbox").value.indexOf("Æ") >= 0 ||
                document.getElementById("twitterbox").value.indexOf("æ") >= 0 ||
                document.getElementById("twitterbox").value.indexOf("Å") >= 0 ||
                document.getElementById("twitterbox").value.indexOf("å") >= 0 ||
                document.getElementById("twitterbox").value.indexOf("Ø") >= 0 ||
                document.getElementById("twitterbox").value.indexOf("ø") >= 0
            ) {
                alert("Tweet inneholder ugyldige karakterer");
                tweetOK = false;
            }
            if (tweetOK) {
                window.location.href = "posttweet.php?msg=" + encodeURIComponent(document.getElementById("twitterbox").value);
            }
        }
    </script>
	<?php
	exit();
}
