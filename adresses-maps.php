<?php

/*
Plugin Name: Adresses Maps to Post and Pages
Plugin URI: https://www.a-j-evolution.com/adressemaps/
Description: Link your pages to a postal address and a category and find them inside a large Map that you can modify to your care.
Author: JANSSENS Arthur
Version: 2.8
Requires at least: 3.1
Tested up to: 4.5
Author URI: https://www.a-j-evolution.com/
Text Domain: ajeadresse-textdomain
Domain Path: /langues/
*/


define( 'REPERTOIRE_ADRESSEMAPS', plugin_dir_path( __FILE__ ) );
define( 'URL_ADRESSEMAPS', plugin_dir_url( __FILE__ ) );

class AJE_AdresseMaps
{
	public function __construct()
    {
		register_activation_hook(__FILE__, array($this, 'install'));
		register_deactivation_hook(__FILE__, array($this, 'uninstall'));
		add_action('admin_menu', array($this, 'adressemaps_menuadmin'));
		add_action('init', array($this, 'initialisationUpdate'), 1);//chaque fois que le plugin est utilisé
		add_action( 'plugins_loaded', array($this, 'myplugin_load_textdomain'));//ajout de la traduction

        include_once REPERTOIRE_ADRESSEMAPS.'metabox.php';
        include_once REPERTOIRE_ADRESSEMAPS.'shortcode.php';
        new AJE_AdresseMapsShortcode();
    }

	public static function install()
	{
		global $wpdb;//Prefixe des tables Wordpress

	    $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ajeadresse` (
		  `id` int(5) NOT NULL AUTO_INCREMENT,
		  `adresse` text NOT NULL,
		  `titre` text NOT NULL,
		  `lat` text NOT NULL,
		  `lng` text NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `id` (`id`)
		);");

		$wpdb->query("INSERT INTO `{$wpdb->prefix}ajeadresse` (`adresse`) VALUES
			('Tour Eiffel, Paris');");
	}

	public static function uninstall()
	{
	}

	public static function initialisationUpdate()
	{
		global $wpdb;//Prefixe des tables Wordpress

	    $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ajeadresse` (
		  `id` int(5) NOT NULL AUTO_INCREMENT,
		  `adresse` text NOT NULL,
		  `titre` text NOT NULL,
		  `lat` text NOT NULL,
		  `lng` text NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `id` (`id`)
		);");

	    $adresses = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ajeadresse");
		$titreExiste = $wpdb->get_results("SELECT titre FROM {$wpdb->prefix}ajeadresse");
		if( count($titreExiste)==0 && count($adresses)>0 ){
			$wpdb->query("ALTER TABLE `{$wpdb->prefix}ajeadresse` ADD `titre` TEXT NOT NULL AFTER `adresse`;");
		}
	}

	function myplugin_load_textdomain() {
	  load_plugin_textdomain( 'ajeadresse-textdomain', false, plugin_basename( dirname( __FILE__ ) ) . '/langues' ); 
	}

    public function adressemaps_menuadmin()
	{	
		add_options_page('Options plugin AdresseMaps', 'AdresseMaps', 'manage_options', 'adresses-maps', array($this, 'menu_html'));
	}

	//page du menu Réglages pour le plugin
	public function menu_html()
	{
		global $wpdb;

		include(REPERTOIRE_ADRESSEMAPS.'reglages/Menu_reglage/tout.php');
	}

}

new AJE_AdresseMaps();