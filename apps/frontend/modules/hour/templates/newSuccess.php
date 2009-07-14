<div id="content-inner">
	<h2>Bonjour <?php echo $sf_user->getUsername() ?> !</h2>
	<p>Maintenant que vous êtes connecté, vous pouvez dire à vos amis que vous venez de vous lever ou que vous allez vous coucher. Pour ce faire, cliquez sur “Bonjour” ou “Bonne nuit”.</p>
	<p>À ce moment-là, Chocolat Chaud publiera une ligne de statut sur votre profil Facebook.</p>
	
			<?php // include_partial('form', array('form' => $form)) ?>
			
			<div id="action_area">
				<form action="/frontend_dev.php/hour/create" name='hour_form' method="post" >
					<div id="left">
						<p>Je me suis levé…</p>
						<p>
							<select name="wakeup">
								<option value="0">à l'instant</option>
								<option value="5">il y a 5 minutes</option>
								<option value="10">il y a 10 minutes</option>
								<option value="15">il y a un quart d'heure</option>
								<option value="30">il y a une demi-heure</option>
							</select></p>
						<p>
							<a class="bonjour" href="javascript:submitDate();"><span>Bonjour !</span></a>
						</p>
					</div>
					<div id="right">
						<p>Je vais me coucher…</p>
						<p>
							<select name="gotosleep">
								<option value="0">à l'instant</option>
								<option value="5">il y a 5 minutes</option>
								<option value="10">il y a 10 minutes</option>
								<option value="15">il y a un quart d'heure</option>
								<option value="30">il y a une demi-heure</option>
							</select></p>
						<p>
							<a class="bonne-nuit" href="javascript:submitDate();"><span>Bonne nuit !</span></a>
						</p>
					</div>
					<input type='hidden' name='timeZone' value=''>
				</form>
			</div>
			
	<p>&#160;</p>
	
	<div id="content-footer">
		<p>
			<a href="logged_newsletter.html">Lettre d'information</a> &#183; 
			<?php echo link_to('Déconnexion', '@sf_guard_signout') ?>
		</p>
	</div>