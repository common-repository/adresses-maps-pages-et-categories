<?php

class AJE_AdresseMapsShortcode
{

	public static function carte_adressemaps_html( $atts, $content = "" ) {
		$atts = shortcode_atts(array('id' => NULL, 'categorie' => NULL, 'zoom' => 15, 'largeur' => "100%", 'hauteur' => "300px", 'css' => NULL), $atts);
		global $wpdb;
		global $current_user;
		get_currentuserinfo();
		$id = $atts['id'];
		$zoom = $atts['zoom'];
		$hauteur = $atts['hauteur'];
		$largeur = $atts['largeur'];
		$css = $atts['css'];
		$categorie = $atts['categorie'];

		include(REPERTOIRE_ADRESSEMAPS."fonctions.php");

		?>
		<style type="text/css">
			#ajeadresse-carte {
				width: <?php echo $largeur;?>;
				height: <?php echo $hauteur;?>;
				<?php echo $css; ?>
			}

			.ajeadresse-content {
    			min-width: 200px;
    		}

    		#ajeadresse-carte img {
				margin: 0px;
				height: initial;
				width: initial;
				max-width: initial;
				max-height: initial;
			}
		</style>
		<?php
		ob_start();

		if( $id <= NULL ){
		//Afficher uniquement l'adresse indiqué sur l'article

			$metas = get_post_meta( get_the_ID() );

			$methode = $metas['ajeadresse-methode'][0];
			$adresse = $metas['ajeadresse-adresse'][0];
			$couleur = $metas['ajeadresse-couleur'][0];
			$description = str_replace("'", "&rsquo;", $metas['ajeadresse-description'][0]);//Eviter les bug d'affichage
			$lng = $metas['ajeadresse-lng'][0];
			$lat = $metas['ajeadresse-lat'][0];

			if( $metas['ajeadresse-titre'][0]=="" OR $metas['ajeadresse-titre'][0]<=NULL ){
				$titre = get_the_title();
			}else{
				$titre = str_replace("'", "&rsquo;", $metas['ajeadresse-titre'][0]);
			}

			if( $methode=="adresse" OR $methode<=NULL  ){
				$adresseGeoCodeur = array();
				$adresseGeoCodeur = convertirAdresseGeocodeur($adresse);
				$adresseGeoCodeur_lat = $adresseGeoCodeur[0]->lat;
				$adresseGeoCodeur_lng = $adresseGeoCodeur[0]->lng;
			}elseif( $methode=="points" ) {
				$adresseGeoCodeur_lat = $lat;
				$adresseGeoCodeur_lng = $lng;
			}

			if($couleur<=NULL OR $couleur=="defaut"){
				$icone = NULL;
			}else{
				$icone = 'icon: "'.URL_ADRESSEMAPS.'places/_'.$couleur.'.png",';
			}

			?>
			<?php include(REPERTOIRE_ADRESSEMAPS."chargerMap-1marqueur.php");

		}elseif( $id == "tout" ){
		//Afficher toutes les addresses trouvées

			if( $categorie <= NULL ){
			//Si on veut afficher vraiment tout
				$adressesPagess = $wpdb->get_results("SELECT post_id FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` LIKE 'ajeadresse-adresse' OR `meta_key` LIKE 'ajeadresse-lat' AND `meta_value` != '';");

				$adressesPages = array();
				foreach ($adressesPagess as $adresse) {
					array_push($adressesPages, $adresse->post_id);
				}
			}else{
			//Si on veut afficher que les catégories demandées(les virgules sont les séparateurs)
				$categoriesDemandees = str_replace(",", ", ", $categorie);
				$categoriesUniqueDemandees = array_unique(explode(", ", $categoriesDemandees));
				//echo "Catégories demandées :";var_dump($categoriesUniqueDemandees);
				$adressesPages = array();
				foreach ($categoriesUniqueDemandees as $categorieDemandee) {
					$adressesPagess = $wpdb->get_results("SELECT post_id FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` LIKE 'ajeadresse-categorie' AND `meta_value` LIKE '%$categorieDemandee%';");
					foreach ($adressesPagess as $adresse) {
						array_push($adressesPages, $adresse->post_id);
					}
				}
				//echo "ID des posts correspondant aux catégories :"; var_dump($adressesPages);
			}

			$tableauMarqueurs = "";
			foreach ($adressesPages as $adressePage) {
				$metas = get_post_meta( $adressePage );

				$methode = $metas['ajeadresse-methode'][0];
				$adresse = $metas['ajeadresse-adresse'][0];
				$couleur = $metas['ajeadresse-couleur'][0];
				$description = str_replace("'", "&rsquo;", $metas['ajeadresse-description'][0]);
				$lng = $metas['ajeadresse-lng'][0];
				$lat = $metas['ajeadresse-lat'][0];

				if( $metas['ajeadresse-titre'][0]=="" OR $metas['ajeadresse-titre'][0]<=NULL ){
					$titre = get_the_title( $adressePage );
					$titre2 = $titre;
				}else{
					$titre = str_replace("'", "&rsquo;", $metas['ajeadresse-titre'][0]);
					$titre2 = str_replace("'", "", $metas['ajeadresse-titre'][0]);
				}

				if( $methode=="adresse" OR $methode<=NULL ){
					$adresseGeoCodeur = array();
					$adresseGeoCodeur = convertirAdresseGeocodeur($adresse);
					$adresseGeoCodeur_lat = $adresseGeoCodeur[0]->lat;
					$adresseGeoCodeur_lng = $adresseGeoCodeur[0]->lng;
				}elseif( $methode=="points" ) {
					$adresseGeoCodeur_lat = $lat;
					$adresseGeoCodeur_lng = $lng;
				}


				if( $adresseGeoCodeur_lng>NULL && $adresseGeoCodeur_lat>NULL ){
					//S'il n'y a pas de description, 2 <br> vont ce succéder, ce qui crée une grosse erreur. Ceci est la solution:
					if($description!=""){ $description .= "<br>"; }

					$tableauMarqueurs .= '[ '.$adresseGeoCodeur_lat.', '.$adresseGeoCodeur_lng.', "'.$titre.'", \'<strong>'.$titre.'</strong><br>'.$description.' <a href="'.get_site_url().'/?p='.$adressePage.'">Voir l&rsquo;article</a>\', 1100, "'.$couleur.'", "'.$titre2.'" ],';
				}

				$icone=NULL;
				$adresseGeoCodeur_lat = NULL;
				$adresseGeoCodeur_lng = NULL;

			}

			?><script type="text/javascript">var tableauMarqueurs = [<?php echo $tableauMarqueurs; ?> ];</script><?php
			include(REPERTOIRE_ADRESSEMAPS."chargerMap.html");
		}

		//return $html;
		//return remove_filter( ob_get_clean(), 'wpautop' );
		return wpautop( ob_get_clean(), true );
	}


}
add_shortcode( 'carte-adressemaps', array( 'AJE_AdresseMapsShortcode', 'carte_adressemaps_html' ) );