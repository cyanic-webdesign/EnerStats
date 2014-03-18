<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

//	include the autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/Cyanic/AutoLoader.php';

//	namespaces
use Cyanic\DbAdapter;
use Cyanic\Authenticate;
use Cyanic\Mapper\Energy as EnergyMapper;
use Cyanic\Mapper\EnergyUser as EnergyUserMapper;

//	initiate the database adapter (width the config)
try {
	$adapter = new DbAdapter(require $_SERVER['DOCUMENT_ROOT'] . '/vendor/config.global.php');
} catch (InvalidArgumentException $e) {
	echo $e->getMessage();
}

// mappers
$energyMapper = new EnergyMapper($adapter);
$energyUserMapper = new EnergyUserMapper($adapter);
$authenticate = new Authenticate($energyUserMapper);

//	front controller
$currentPage = filter_input(INPUT_GET, 'page');
$currentPage = ($authenticate->isAuthenticated() || $currentPage == 'uitloggen') ? $currentPage : 'inloggen';
$websitePages = array('dashboard', 'electra', 'gas', 'instellingen', 'inloggen', 'uitloggen');
if(in_array($currentPage, $websitePages)) {
	$showPage = $currentPage.'.php';
} else {
	$showPage = ($currentPage == '') ? 'dashboard.php' : 'dashboard.php';
}

?>
<!DOCTYPE html>
    <head>
        <!-- meta -->
        <meta charset="utf-8">
        <meta name="robots" content="index,follow">        
        <meta name="description" content="Statisfying your enery usage">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="keywords" content="enerstats, cyanic webdesign, p1, slimme meter, electiciteit, gas, verbruik, statistieken">
        <meta name="author" content="Cyanic Webdesign"> 
        <meta name="publisher" content="http://www.cyanicwebdesign.nl"> 
        <meta name="copyright" content="Cyanic Webdesign">         
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="HandheldFriendly" content="true">
        <meta name="MobileOptimized" content="320">               
        <!-- open graph -->
        <meta property="og:title" content="EnerStats maakt jouw energieverbruik inzichtelijk!" />
        <meta property="og:site_name" content="EnerStats - Statisfy your energy use" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="http://www.enerstats.nl/" />
        <meta property="og:description" content="EnerStats is een gratis open-source statistieken tool waarmee je snel en eenvoudig P1 data omzet naar grafieken en statistieken. " />
        <meta property="og:image" content="http://www.enerstats.nl/images/open-graph-icon.png" />
        <meta property="og:language" content="nl_NL" />        
        <!-- styles -->
        <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700|Pontano+Sans|Indie+Flower" rel="stylesheet">
        <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="/style/style.css" rel="stylesheet">
		<!-- javascripts -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="/js/highcharts.js"></script>
		<script src="/js/enerstats.main.min.js"></script>		
        <!-- icons -->
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<link rel="apple-touch-icon-precomposed" href="/images/touch-icon-iphone.png" /> <!-- 57x57 -->
		<link rel="apple-touch-icon" sizes="72x72" href="/images/touch-icon-ipad.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/images/touch-icon-iphone-retina.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/images/touch-icon-ipad-retina.png" />      
        <!-- title -->
        
        <?php require($showPage); ?>
		
		<script src="js/jquery.stayInWebApp.min.js"></script>
		<script>
			$(function() { $.stayInWebApp(); });			
		</script>
	</body>
</html>        