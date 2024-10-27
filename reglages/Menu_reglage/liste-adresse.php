<div style="margin-top:20px;">
	<h2><?php _e( 'Your addresses', 'ajeadresse-textdomain' ); ?></h3>
	<p><br></p>
	<p><?php _e( 'You have the choice of pre-populate an address to easily associate with your articles or your pages.', 'ajeadresse-textdomain' ); ?></p>
	<p><br></p>

	<?php
	if ( $_GET['ajouter'] == "oui" ) {
		$code = wp_generate_password( 8, false );
		$wpdb->query("INSERT INTO `{$wpdb->prefix}ajeadresse` (`id`, `titre`, `adresse`) VALUES (NULL, 'Une autre', 'Paris');");
	}

	if ( $_GET['suprim_id'] > NULL ) {

		$wpdb->query( $wpdb->prepare( 
	    "
	      DELETE FROM `{$wpdb->prefix}ajeadresse`
	      WHERE `id` = %s;
	    ", 
	    array($_GET['suprim_id']) 
	  ));
	}

	$adresses = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ajeadresse ORDER BY `{$wpdb->prefix}ajeadresse`.`id` ASC");


	?>
	<style type="text/css">
	.tg  {border-collapse:collapse;border-spacing:0;border-color:#ccc;}
	.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;}
	.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f0f0f0;}
	.tg .tg-hjma{background-color:#ffffff; text-align: center;}
	.tg .tg-hjma-noborder{background-color:#ffffff; border: none;}

	.tg-hjma input[type="text"] {
	  width: 100%;
	  border: none;
	  box-shadow: none;
	  background-color: #D6D6D6;
	  font-family: monospace;
	  padding: 6px 5px 6px 5px;
	}
	</style>

	<table class="tg" style="undefined;table-layout: fixed; width: 95%;margin-left:2%">
	<colgroup>
	<col style="width: 30%">
	<col style="width: 23%">
	<col style="width: 18%">
	<col style="width: 18%">
	<col>
	</colgroup>
	<tr>
	<th class="tg-031e"><?php _e( 'Title', 'ajeadresse-textdomain' ); ?></th>
	<th class="tg-031e"><?php _e( 'Adresse', 'ajeadresse-textdomain' ); ?></th>
	<th class="tg-031e"><?php _e( 'Latitude', 'ajeadresse-textdomain' ); ?></th>
	<th class="tg-031e"><?php _e( 'Longitude', 'ajeadresse-textdomain' ); ?></th>
	<th class="tg-031e"><?php _e( 'Delete', 'ajeadresse-textdomain' ); ?></th>
	</tr>
	<?php
	foreach ($adresses as $adresse) { ?>
	  <tr>
	  	<td class="tg-hjma" title="Modifier"><?php echo '<a href="admin.php?page=adresses-maps&p=modifAdresse&id='.$adresse->id.'"><strong>'.$adresse->titre.'</strong></a>'; ?></td>
	    <td class="tg-hjma" title="Modifier"><?php echo '<a href="admin.php?page=adresses-maps&p=modifAdresse&id='.$adresse->id.'"><strong>'.$adresse->adresse.'</strong></a>';?></td>
	    <td class="tg-hjma" title="Modifier"><?php echo '<a href="admin.php?page=adresses-maps&p=modifAdresse&id='.$adresse->id.'"><strong>'.$adresse->lat.'</strong></a>';?></td>
	    <td class="tg-hjma" title="Modifier"><?php echo '<a href="admin.php?page=adresses-maps&p=modifAdresse&id='.$adresse->id.'"><strong>'.$adresse->lng.'</strong></a>';?></td>
	    <td class="tg-hjma"><?php echo "<a onclick='confirmation_supprimer(\"$adresse->id\");' href='javascript:' >".__( 'Delete', 'ajeadresse-textdomain' )."</a>"; ?></td>
	  </tr>
	  <?php
	} ?>

	</table>

	<br><br>
	<input type="button" name="ok" value="<?php _e( 'Add an address', 'ajeadresse-textdomain' ); ?>" onclick="document.location='?page=adresses-maps&p=liste-adresse&ajouter=oui'">

	<script type="text/javascript">
	function confirmation_supprimer(id){
		var r = confirm("<?php _e( 'Do you really want to delete this address ?', 'ajeadresse-textdomain' ); ?>");
		if (r == true) {
		    document.location='?page=adresses-maps&p=liste-adresse&suprim_id='+id;
		} else {}
	}
	</script>

</div>