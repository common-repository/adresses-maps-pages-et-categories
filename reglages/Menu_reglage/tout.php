<?php
if ($_GET['p']<=NULL) {
	include(REPERTOIRE_ADRESSEMAPS.'reglages/Menu_reglage/index.php');
}else{
	include(REPERTOIRE_ADRESSEMAPS.'reglages/Menu_reglage/'.$_GET['p'].'.php');
}