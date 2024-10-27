<div style="margin-top:20px;">
	<h2><?php _e( 'AdresseMaps plugins', 'ajeadresse-textdomain' ); ?></h3>
	<p><br></p>
	<p></p>
	<p>Le plugin n'as besoin d'aucun réglage particulier mais les cartes fonctionnant avec <a href="https://developers.google.com/maps/" target="blank">Google Maps</a>, une génération trop importante de carte(plus de 200 000 dans la journée) blocquera temporairement vos affichages.<br>Pour palier à ce problème, il suffit de fournir votre <strong>clé d'accès GoogleMaps</strong>.</p>
	<p><br><br>Ce réglage est donc destiné aux connaisseurs de l'API Google et est en aucun cas obligatoire.<br>Ce réglage permet entre autre d'avoir des statistiques sur l'utilisation de votre quota Google.</p>

	<p><br></p>

	<?php
	$nom_option = 'cleAPIGoogleMaps';
	if( isset($_POST['cle']) && $_POST['cle']>NULL) {
		$valeur = $_POST['cle'];
		if ( get_option( $nom_option ) !== false ) {
	    	update_option( $nom_option, $valeur );
		}else{
		    add_option( $nom_option, $valeur, null, 'yes' );
		}
	}

	$nom_option2 = 'cleAPIGoogleMaps2';
	if( isset($_POST['cle2']) && $_POST['cle2']>NULL) {
		$valeur2 = $_POST['cle2'];
		if ( get_option( $nom_option2 ) !== false ) {
	    	update_option( $nom_option2, $valeur2 );
		}else{
		    add_option( $nom_option2, $valeur2, null, 'yes' );
		}
	}
 	?>

	<form action="" method="POST">
		<input type="text" placeholder="<?php _e( 'Navigator key', 'ajeadresse-textdomain' ); ?>" name="cle" id="cle" value="<?php echo get_option($nom_option);?>" size="50">
		<br><br>

		<input type="text" placeholder="<?php _e( 'Server key', 'ajeadresse-textdomain' ); ?>" name="cle2" id="cle2" value="<?php echo get_option($nom_option2);?>" size="50">

		<br><br>
		<input type="submit" value="Ajouter">
	</form>
	<p><strong>Comment obtenir ma clé API ?</strong> Ne vous inquiétez pas, c'est totalement gratuit et c'est ici:<br>
	<a href="?page=adresses-maps&p=obtenirApiKey" target="blank">?page=adresses-maps&p=obtenirApiKey</a>
	<br>
	<br>
	<br>
	Pour ajouter d'autres clés, ces images devront vous aider.
	</p>

	<style type="text/css">
	div#images > img {
		width:98%;
		max-width:1000px;
		border: 1px solid black;
	}
	div#images > p {
		margin-bottom: 40px;
		border-bottom: 1px solid #CECECE;
	}
	</style>
	<div style="text-align:center;" id="images">
		<img src="<?php echo plugins_url( 'obtenirApiKey_8.png', __FILE__ );?>">
		<p></p>

		<img src="<?php echo plugins_url( 'obtenirApiKey_9.png', __FILE__ );?>">
		<p></p>

		<img src="<?php echo plugins_url( 'obtenirApiKey_10.png', __FILE__ );?>">
		<p>Visualisation du quota</p>
	</div>
	
</div>