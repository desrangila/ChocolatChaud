<?php use_helper('I18N') ?>



<div id="content-inner">
	<h2>Bienvenue ! Identifiez-vous pour commencer !</h2>
	<p>Pour indiquer à vos amis sur Facebook lorsque vous vous levez et vous allez vous coucher, vous devez d'abord vous identifier. Cette opération est totalement sécurisée.</p>
	<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
	  <table>
	    <?php echo $form ?>
	  </table>

	  <input type="submit" value="<?php echo __('sign in') ?>" />
	  <a href="<?php echo url_for('@sf_guard_password') ?>"><?php echo __('Forgot your password?') ?></a>
	</form>
	<div id="newsletter">
		<img src="/images/newsletter-logo.png" style="float:left;padding-right:20px;"/><h4>Pour ne rater aucune saveur,<br/>inscrivez-vous à la lettre d'informations :</h4>
		<form action="http://desrangila.createsend.com/t/r/s/yhiyb/" method="post">
<div>
<label for="name">Nom </label><input type="text" name="cm-name" id="name" /><br />
<label for="yhiyb-yhiyb">Email </label><input type="text" name="cm-yhiyb-yhiyb" id="yhiyb-yhiyb" /><br />

<input type="submit" value="Oh oui, tartinez-moi de Chocolat !" />
</div>
</form>
		<p><u>Note :</u> Comme vous, nous n'aimons pas le spam. Nous n'envoyons pas de courriers non sollicités ni ne revendons vos adresses. De plus vous pouvez vous désabonner à tout moment.</p>
	</div>
</div>