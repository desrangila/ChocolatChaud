<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
  </head>
  <body class="home">
		<div id="container">
			<div id="header">
					<h1><a href="<?php echo url_for('hour/new'); ?>" class="h1">Chocolat Chaud</a></h1>
				<h3>Dites “Bonjour” et “Bonne nuit” à vos amis !</h3>
			</div>
			
			<div id="content">
				<?php echo $sf_content ?>
			</div>
			
			<div id="footer">
				<p>TM &amp; &copy; 2009 
					<a href="http://www.desrangila.com/">Des Rangila</a>. Tous droits réservés. &#183; 
					<a href="http://public.desrangila.com/Chocolat-Chaud-%3A-Conditions-d'utilisation">Conditions d'utilisation</a> &#183; 
					<a href="http://public.desrangila.com/Chocolat-Chaud-%3A-Aide">Aide</a> &#183; 
					<a href="http://public.desrangila.com/Chocolat-Chaud-%3A-Développeurs-%28API%29">Développeurs (API)</a>
				</p>
			</div>	
			
		</div>    
  </body>
</html>
