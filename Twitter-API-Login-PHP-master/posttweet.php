

<?php //Etter tweeten har blitt sjekket på klient-siden, så gjøres det en ekstra sjekk på server-siden for å forsikre om at alt stemmer.
session_start();
require 'autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

define( 'CONSUMER_KEY', 'xWDoh6IrvFW5gs7UfIAewSQWN' );
define( 'CONSUMER_SECRET', 'nAaU0u5LniDergV58HxnNWN7dodpOH4HeYk3Z8e8vkpdHLs3gL' );
define( 'OAUTH_CALLBACK', 'http://applikasjon.com/Twitter-API-Login-PHP-master/callback.php' );

if ( isset( $_REQUEST["msg"] ) ) {
	$msg     = $_REQUEST["msg"];
	$msgokay = true;
	if ( strlen( $msg ) < 20 ) {
		$msgokay = false;
	}
	if ( strlen( $msg ) > 140 ) {
		$msgokay = false;
	}
	if ( strpos( $msg, "Æ" ) !== false ) {
		$msgokay = false;
	}
	if ( strpos( $msg, "æ" ) !== false ) {
		$msgokay = false;
	}
	if ( strpos( $msg, "Å" ) !== false ) {
		$msgokay = false;
	}
	if ( strpos( $msg, "å" ) !== false ) {
		$msgokay = false;
	}
	if ( strpos( $msg, "Ø" ) !== false ) {
		$msgokay = false;
	}
	if ( strpos( $msg, "ø" ) !== false ) {
		$msgokay = false;
	}
} else {
	echo "Something wen't wrong, please try again";

}

if ( $msgokay ) {
	if ( isset( $_SESSION['access_token'] ) ) {
		$access_token = $_SESSION['access_token'];
		$connection   = new TwitterOAuth( CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret'] );
		$status       = $msg;
		$post_tweets  = $connection->post( "statuses/update", [ "status" => $status ] );
		header("location:index.php?notify=tweet+posted");
		echo "tweet posted";
	} else {
		echo "You are not logged in, please try again";
	}
} else {
	echo "Something does not work properly";
}
