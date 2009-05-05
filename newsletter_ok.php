<?php

//error_reporting(E_ALL);

include_once 'lib/config.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Chocolat Chaud &#183; Lettre d'information</title>
	<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" title="no title" charset="utf-8">
	
</head>

<body class="error">
	<div id="container">
		<div id="header">
			<h1><a href="/" class="h1">Chocolat Chaud</a></h1>
			<h3>Dites “Bonjour” et “Bonne nuit” à vos amis !</h3>
		</div>
		<div id="content">
			<div id="content-inner">
				<h2>Lettre d'information</h2>
				<p>Votre inscription s'est bien déroulée.</p>
				<p><a href="/">‹ Retourner sur Chocolat Chaud</a></p>
			<?php 
			echo render_footer();
			?>