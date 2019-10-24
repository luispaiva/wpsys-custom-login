<?php
/**
* Plugin Name:     WPSYS Custom Login
* Plugin URI:      http://luispaiva.com.br
* Description:     Plugin desenvolvido para customizar a tela de autenticação do WordPress.
* Author:          Luis Paiva
* Author URI:      http://luispaiva.com.br
* Text Domain:     wpsys
* Domain Path:     /languages
* Version:         0.1.0
*
* @package         Wpsys_Custom_Login
*/

defined( 'ABSPATH' ) || die;

define( 'WPSYS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPSYS_URL', plugin_dir_url( __FILE__ ) );

require_once WPSYS_PATH . 'includes/class.init.php';
