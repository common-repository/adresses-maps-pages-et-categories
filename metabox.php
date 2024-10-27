<?php

function ajeadresse_custom_meta() {
	
	add_meta_box( 'ajeadresse_meta', __( 'Associated address', 'ajeadresse-textdomain' ), 'ajeadresse_meta_callback', 'page' );
	add_meta_box( 'ajeadresse_meta', __( 'Associated address', 'ajeadresse-textdomain' ), 'ajeadresse_meta_callback', 'post' );
	//au lieu de 'page', ou peut aussi mettre 'post','dashboard','link','attachment','custom_post_type' et 'comment'.
}
add_action( 'add_meta_boxes', 'ajeadresse_custom_meta' );

function ajeadresse_meta_callback( $post ) {
	global $wpdb;

	wp_nonce_field( basename( __FILE__ ), 'ajeadresse_nonce' );
	$ajeadresse_stored_meta = get_post_meta( $post->ID );
	include(REPERTOIRE_ADRESSEMAPS."fonctions.php");

	$adresses = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}ajeadresse` ORDER BY `adresse` ASC;");


	if ( isset ( $ajeadresse_stored_meta['ajeadresse-methode'] ) ) $ajeadresse_methode = $ajeadresse_stored_meta['ajeadresse-methode'][0];
	if ( isset ( $ajeadresse_stored_meta['ajeadresse-adresse'] ) ) $ajeadresse_adresse = $ajeadresse_stored_meta['ajeadresse-adresse'][0];
	if ( isset ( $ajeadresse_stored_meta['ajeadresse-lat'] ) ) $ajeadresse_lat = $ajeadresse_stored_meta['ajeadresse-lat'][0];
	if ( isset ( $ajeadresse_stored_meta['ajeadresse-lng'] ) ) $ajeadresse_lng = $ajeadresse_stored_meta['ajeadresse-lng'][0];
	if ( isset ( $ajeadresse_stored_meta['ajeadresse-titre'] ) ) $ajeadresse_titre = $ajeadresse_stored_meta['ajeadresse-titre'][0];
	if ( isset ( $ajeadresse_stored_meta['ajeadresse-categorie'] ) ) $ajeadresse_categorie = $ajeadresse_stored_meta['ajeadresse-categorie'][0];
	if ( isset ( $ajeadresse_stored_meta['ajeadresse-couleur'] ) ) $ajeadresse_couleur = $ajeadresse_stored_meta['ajeadresse-couleur'][0];
	if ( isset ( $ajeadresse_stored_meta['ajeadresse-description'] ) ) $ajeadresse_description = $ajeadresse_stored_meta['ajeadresse-description'][0];

	$categories = $wpdb->get_results("SELECT meta_value FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` LIKE 'ajeadresse-categorie' AND `meta_value` != '';");
	$categoriesTrouvees = NULL;
	foreach ($categories as $categorie) {
		 	$categoriesTrouvees .= str_replace(",", ", ", $categorie->meta_value).", ";
	}
	$categoriesUnique = array_unique(explode(", ", $categoriesTrouvees));
	$categoriesTrouvees = NULL;
	foreach ($categoriesUnique as $categorie) {
		 	$categoriesTrouvees .= "<strong>".$categorie."</strong>, ";
	} ?>
	<style type="text/css">
		#ajeadresse-carte {
			width: 100%;
			height: 200px;
		}
		input.code{
			background-color: #EFEDED;
			padding: 10px;
			width: 100%;
		}

		.adresseAsso_form input, .adresseAsso_form textarea, .adresseAsso_form #ajeadresse-preremplir, .adresseAsso_form #ajeadresse-couleur {
			padding: 10px;
			width: 100%;
			height: auto;
		}
		.adresseAsso_form label {
			padding-left: 3px;
		}

		#votreadresse {
			
		}

		#vospoints {
			
		}
	</style>
	<?php

	if( $ajeadresse_methode=="adresse" OR $ajeadresse_methode<=NULL ){
		$adresseGeoCodeur = array();
		$adresseGeoCodeur = convertirAdresseGeocodeur($ajeadresse_adresse);

		$titre = $ajeadresse_titre;
		$description = $ajeadresse_description;
		$zoom = 17;
		$adresseGeoCodeur_lat = $adresseGeoCodeur[0]->lat;
		$adresseGeoCodeur_lng = $adresseGeoCodeur[0]->lng;

	}elseif( $ajeadresse_methode=="points" ) {
		$adresseGeoCodeur_lat = $ajeadresse_lat;
		$adresseGeoCodeur_lng = $ajeadresse_lng;
	}

	if($ajeadresse_couleur<=NULL OR $ajeadresse_couleur=="defaut"){
		$icone = NULL;
	}else{
		$icone = 'icon: "'.URL_ADRESSEMAPS.'places/_'.$ajeadresse_couleur.'.png",';
	}
	

	if( $adresseGeoCodeur_lat > NULL && $adresseGeoCodeur_lng > NULL ){
		echo "<strong>"; _e( 'Address found', 'ajeadresse-textdomain' ); echo "</strong>:<br>".$adresseGeoCodeur[1]."<br><br>";
		include(REPERTOIRE_ADRESSEMAPS."chargerMap-1marqueur-deplacer.php");
	} ?>

	<div class="adresseAsso_form">
		<p>
			<label for="ajeadresse-preremplir"></label>
			<select name="ajeadresse-preremplir" id="ajeadresse-preremplir" onchange="ff()">
			  <option value="nouvelle" <?php if($ajeadresse_preremplir=="adresse"){echo "selected";}?> ><?php _e( 'Use a new address :', 'ajeadresse-textdomain' );?></option>
			  <?php
			  $js_adresses = '{';
			  foreach ($adresses as $_adresse) {
			  	$js_adresses .= '
			  	"'.$_adresse->id.'":{
			  		"adresse":"'.str_replace("'", "\'", $_adresse->adresse).'",
			  		"lat":"'.$_adresse->lat.'",
			  		"lng":"'.$_adresse->lng.'"
			  	},';

			  	if( $_adresse->lat > NULL && $_adresse->lng > NULL ){
			  		if( $_adresse->titre > NULL ){
			  			$utiliser = $_adresse->titre;
			  		}else{
			  			$utiliser = __( 'Use', 'ajeadresse-textdomain' ).' "LAT:'.$_adresse->lat.' LNG:'.$_adresse->lng.'" :';
			  		}
				   	?>
				  	<option value="<?php echo $_adresse->id; ?>" <?php if($_adresse->lat==$ajeadresse_lat && $_adresse->lng==$ajeadresse_lng){echo "selected";}?> ><?php echo $utiliser; ?></option>
				  	<?php
				}else{
					if( $_adresse->titre > NULL ){
			  			$utiliser = $_adresse->titre;
			  		}else{
			  			$utiliser = __( 'Use', 'ajeadresse-textdomain' ).' "'.$_adresse->adresse.'" :';
			  		}
					?>
				  	<option value="<?php echo $_adresse->id; ?>" <?php if($_adresse->adresse==$ajeadresse_adresse){echo "selected";}?> ><?php echo $utiliser; ?></option>
				  	<?php
				}

				$utiliser = NULL;


			  }
			  $js_adresses .= "}"
			  ?>
			  
			</select>
			<br><br>
		</p>

		<div id="remplirsolo">
			<p>
				<label for="ajeadresse-methode"><?php _e( "Select a search method of address", 'ajeadresse-textdomain' );?></label>
				<select name="ajeadresse-methode" id="ajeadresse-methode" onchange="f()">
				  <option value="adresse" <?php if($ajeadresse_methode=="adresse"){echo "selected";}?> ><?php _e( 'Give an address', 'ajeadresse-textdomain' );?></option> 
				  <option value="points" <?php if($ajeadresse_methode=="points"){echo "selected";}?> >
				  	<?php _e( 'Give Latitude and Longitude', 'ajeadresse-textdomain' );?></option>
				</select>
			</p>

			<div id="votreadresse">
				<p>
					<label for="ajeadresse-adresse"><?php _e( 'Your address', 'ajeadresse-textdomain' );?></label>
					<input type="text" name="ajeadresse-adresse" id="ajeadresse-adresse" value="<?php echo $ajeadresse_adresse; ?>" />
				</p>
			</div>

			<div id="vospoints">
				<p>
					<label for="ajeadresse-lat"><?php _e( 'Latitude', 'ajeadresse-textdomain' );?></label>
					<input type="text" name="ajeadresse-lat" id="ajeadresse-lat" value="<?php echo $ajeadresse_lat; ?>" />
					<label for="ajeadresse-lng"><?php _e( 'Longitude', 'ajeadresse-textdomain' );?></label>
					<input type="text" name="ajeadresse-lng" id="ajeadresse-lng" value="<?php echo $ajeadresse_lng; ?>" />
				</p>
			</div>
		</div>

		<p>
			<label for="ajeadresse-titre"><?php _e( 'Title to display', 'ajeadresse-textdomain' );?></label>
			<input type="text" name="ajeadresse-titre" id="ajeadresse-titre" value="<?php echo $ajeadresse_titre; ?>" />
		</p>

		<p>
			<label for="ajeadresse-categorie"><?php _e( 'Add to a category ?', 'ajeadresse-textdomain' );?></label>
			<input type="text" name="ajeadresse-categorie" id="ajeadresse-categorie" value="<?php echo $ajeadresse_categorie; ?>" placeholder="categorie1,categorie2,.." /><br>
			<?php 
			if ($categoriesTrouvees>NULL) {
				_e( 'Previous categories used :', 'ajeadresse-textdomain' )."<br>";
				echo $categoriesTrouvees;
			}
			?>
			<br><br>
		</p>

		<p>
			<label for="ajeadresse-couleur"></label>
			<select name="ajeadresse-couleur" id="ajeadresse-couleur">
			  <option value="defaut" <?php if($ajeadresse_couleur=="defaut" OR $ajeadresse_couleur<=NULL){echo "selected";}?> ><?php _e( "Color default icon", 'ajeadresse-textdomain' );?></option> 
			  <option value="rouge" <?php if($ajeadresse_couleur=="rouge"){echo "selected";}?> ><?php _e( "Color of the icon", 'ajeadresse-textdomain' );?> Rouge</option>
			  <option value="orange" <?php if($ajeadresse_couleur=="orange"){echo "selected";}?> ><?php _e( "Color of the icon", 'ajeadresse-textdomain' );?> Orange</option>
			  <option value="jaune" <?php if($ajeadresse_couleur=="jaune"){echo "selected";}?> ><?php _e( "Color of the icon", 'ajeadresse-textdomain' );?> Jaune</option>
			  <option value="bleu-pale" <?php if($ajeadresse_couleur=="bleu-pale"){echo "selected";}?> ><?php _e( "Color of the icon", 'ajeadresse-textdomain' );?> Bleu pale</option>
			  <option value="vert" <?php if($ajeadresse_couleur=="vert"){echo "selected";}?> ><?php _e( "Color of the icon", 'ajeadresse-textdomain' );?> Vert</option>
			  <option value="bleu-fonce" <?php if($ajeadresse_couleur=="bleu-fonce"){echo "selected";}?> ><?php _e( "Color of the icon", 'ajeadresse-textdomain' );?> Bleu foncé</option>
			  <option value="rose" <?php if($ajeadresse_couleur=="rose"){echo "selected";}?> ><?php _e( "Color of the icon", 'ajeadresse-textdomain' );?> Rose</option>
			</select>
		</p>

		<p>
			<label for="ajeadresse-description" class="ajeadresse-row-title"><?php _e( "Description Address", 'ajeadresse-textdomain' );?></label>
			<textarea name="ajeadresse-description" id="ajeadresse-description"><?php echo $ajeadresse_description; ?></textarea>
			<p><?php _e( 'HTML formatting is possible', 'ajeadresse-textdomain' );?></p>
		</p>

		<p><br><strong><?php _e( "Place the card into the page by inserting this shortcode to the desired position", 'ajeadresse-textdomain' );?></strong><br>
		<input class="code" type="text" value="[carte-adressemaps zoom=15 largeur=&quot;100%&quot; hauteur=&quot;300px&quot;]" /></p>

		<p><br><strong><?php _e( "To view the card with the address of all pages :", 'ajeadresse-textdomain' );?></strong><br>
		<input class="code" type="text" value="[carte-adressemaps id=&quot;tout&quot; largeur=&quot;100%&quot; hauteur=&quot;300px&quot;]" />
		<br>
		<br><strong><?php _e( "To view the card with the address of all designated categories :", 'ajeadresse-textdomain' );?></strong><br>
		<input class="code" type="text" value="[carte-adressemaps id=&quot;tout&quot; categorie=&quot;categorie1,categorie2&quot; largeur=&quot;100%&quot; hauteur=&quot;300px&quot;]" />
		<br><?php _e( 'The code can contain one or more categories.', 'ajeadresse-textdomain' );?></p>
	</div>

	<script src="<?php echo URL_ADRESSEMAPS; ?>jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		//remplacement des "$" de jQuery par "version1_11_3" pour utiliser la version 1.11.3
		version1_11_3 = jQuery.noConflict( true );
			
		function f(){
			var select = version1_11_3('#ajeadresse-methode option:selected').val();
								
			version1_11_3( "#votreadresse" ).css( "display", "none" );
			version1_11_3( "#vospoints" ).css( "display", "none" );

			if(select=="adresse"){
				version1_11_3( "#votreadresse" ).css( "display", "block" );
			}

			if(select=="points"){
				version1_11_3( "#vospoints" ).css( "display", "block" );
			}
		}

		function ff(){
			var select = version1_11_3('#ajeadresse-preremplir option:selected').val();
								
			version1_11_3( "#remplirsolo" ).css( "display", "none" );
			var adresses = <?php echo $js_adresses; ?>;

			if(select=="nouvelle"){
				version1_11_3( "#remplirsolo" ).css( "display", "block" );
			}else{
				if( adresses[select]["lat"] != "" && adresses[select]["lng"] !="" ){
					document.getElementById("ajeadresse-adresse").value = "";
					document.getElementById("ajeadresse-lat").value = adresses[select]["lat"];
					document.getElementById("ajeadresse-lng").value = adresses[select]["lng"];
					document.getElementById("ajeadresse-methode").value = "points";
					f();
				}else{
					document.getElementById("ajeadresse-adresse").value = adresses[select]["adresse"];
					document.getElementById("ajeadresse-lat").value = "";
					document.getElementById("ajeadresse-lng").value = "";
					document.getElementById("ajeadresse-methode").value = "adresse";
					f();
				}
			}
		}


		f();
		ff();
	</script>

	<?php
}



function ajeadresse_meta_save( $post_id ) {
 
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'ajeadresse_nonce' ] ) && wp_verify_nonce( $_POST[ 'ajeadresse_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}
	
	if( isset( $_POST[ 'ajeadresse-methode' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-methode', sanitize_text_field( $_POST[ 'ajeadresse-methode' ] ) );
	}

	if( isset( $_POST[ 'ajeadresse-adresse' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-adresse', sanitize_text_field( str_replace('"', "", $_POST[ 'ajeadresse-adresse' ]) ) );
	}

	if( isset( $_POST[ 'ajeadresse-lat' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-lat', sanitize_text_field( $_POST[ 'ajeadresse-lat' ] ) );
	}
	if( isset( $_POST[ 'ajeadresse-lng' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-lng', sanitize_text_field( $_POST[ 'ajeadresse-lng' ] ) );
	}

	if( isset( $_POST[ 'ajeadresse-titre' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-titre', sanitize_text_field( $_POST[ 'ajeadresse-titre' ] ) );
	}

	if( isset( $_POST[ 'ajeadresse-categorie' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-categorie', sanitize_text_field( str_replace(" ", "", $_POST[ 'ajeadresse-categorie' ]) ) );
	}

	if( isset( $_POST[ 'ajeadresse-couleur' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-couleur', sanitize_text_field( $_POST[ 'ajeadresse-couleur' ] ) );
	}

	if( isset( $_POST[ 'ajeadresse-description' ] ) ) {
		update_post_meta( $post_id, 'ajeadresse-description', sanitize_text_field( $_POST[ 'ajeadresse-description' ] ) );
	}

}
add_action( 'save_post', 'ajeadresse_meta_save' );

