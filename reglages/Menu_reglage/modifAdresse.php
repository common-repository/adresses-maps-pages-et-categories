<?php
global $wpdb;

if ( !empty($_POST['ok']) ) {
	$adresse = str_replace('"', "", sanitize_text_field($_POST['adresse']) );
	$titre = str_replace('"', "", sanitize_text_field($_POST['titre']) );
	$lat = sanitize_text_field($_POST['lat']);
	$lng = sanitize_text_field($_POST['lng']);

	$wpdb->query( $wpdb->prepare( 
	    "
	      UPDATE `{$wpdb->prefix}ajeadresse`
	      SET `adresse` = %s, `titre` = %s,
	      `lat` = %s, `lng` = %s
	      WHERE `id` = %s;
	    ", 
	    array($adresse, $titre, $lat, $lng, $_GET['id']) 
	  ));
}

$adresse = $wpdb->get_results( $wpdb->prepare(
	"SELECT * FROM `{$wpdb->prefix}ajeadresse` WHERE `id` = %d",
	array($_GET['id'])
) );
$adresse = $adresse[0];
?>

<style type="text/css">
	.tg  {border-collapse:collapse;border-spacing:0;}
	.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border:none;overflow:hidden;word-break:normal;}
	.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border:none;border-width:1px;overflow:hidden;word-break:normal;}
	.tg .tg-cxkv{background-color:#ffffff}
	.tg .tg-bsv2{background-color:#efefef; text-align: left;}
	.tg .tg-bsv2 span {
		font-size: 11px;
		font-style: italic;
	}
	.tg tr {
		border-bottom: 1px solid #E6E6E6;
	}
	.tg tr input[type="text"] {
		width: 100%;
    	border: none;
    	box-shadow: none;
	}

	.tgg  {border-collapse:collapse;border-spacing:0;}
	.tgg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
	.tgg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
	.tgg .tgg-s6z2{text-align:center}
	.tgg .tgg-0onx{background-color:none;color:#000000;border: none;}
	.tgg .tgg-0lge{background-color:#656565;color:#ffffff;text-align:center;border: 1px solid black;}
	.tgg-s6z2 input, .tgg-0onx input{width: 100%;}

	textarea {
		width: 58%;
		height: 200px;
		float: left;
	}
	#zoneverifier {
		float: left;
		height: 200px;
		width: 37%;
		margin-left: 1%;
		overflow: scroll;
	}
	#zoneverifier, textarea {
		font-size: 14px;
		padding: 1%;
	}

</style>


<br><br>
<h2><?php _e( 'Change Address', 'ajeadresse-textdomain' ); ?></h2>
<p><?php _e( 'You can choose complete this is right for you, the addresses being created <strong>only for a pre-filling</strong>.', 'ajeadresse-textdomain' ); ?><br><br></p>

<form action="" method="POST">

<table class="tg" style="undefined;table-layout: fixed; width: 90%; border:none; ">
<colgroup>
<col style="width: 20%">
<col style="width: 70%">
</colgroup>
<tbody>
  <tr>
    <th class="tg-bsv2"><?php _e( 'Title', 'ajeadresse-textdomain' ); ?></th>
    <th class="tg-cxkv"><input type="text" name="titre" value="<?php echo $adresse->titre; ?>"></th>
  </tr>
  <tr>
    <th class="tg-bsv2"><?php _e( 'Adresse', 'ajeadresse-textdomain' ); ?></th>
    <th class="tg-cxkv"><input type="text" name="adresse" placeholder="Tour Eiffel, Paris" value="<?php echo $adresse->adresse; ?>"></th>
  </tr>
  <tr>
    <td class="tg-bsv2"><?php _e( 'Latitude', 'ajeadresse-textdomain' ); ?></td>
    <td class="tg-cxkv"><input type="text" name="lat" placeholder="0.000000" value="<?php echo $adresse->lat; ?>"></td>
  </tr>
  <tr>
    <td class="tg-bsv2"><?php _e( 'Longitude', 'ajeadresse-textdomain' ); ?></td>
    <td class="tg-cxkv"><input type="text" name="lng" placeholder="0.000000" value="<?php echo $adresse->lng; ?>"></td>
  </tr>
</tbody></table>

<br><br>

<input type="submit" name="ok" value="<?php _e( 'Edit', 'ajeadresse-textdomain' ); ?>">

</form>