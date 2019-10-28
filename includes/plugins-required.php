<?php
/**
 * Inclui a classe TGM_Plugin_Activation.
 */
require_once WPSYS_PATH . '/plugins/tgm/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'wpsys_register_required_plugins' );

/**
 * Registra os plugins necessários.
 */
function wpsys_register_required_plugins() {
	/*
	 * Plugins necessários.
	 */
	$plugins = array(
		array(
			'name'               => 'Really Simple CAPTCHA',
			'slug'               => 'really-simple-captcha',
			'required'           => true,
         'version'            => '',
			'force_activation'   => false,
			'force_deactivation' => false,
		)
	);

	/*
	 * Configurações.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php',           // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => false,                   // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		/*
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'theme-slug' ),
			'menu_title'                      => __( 'Install Plugins', 'theme-slug' ),
			// <snip>...</snip>
			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
		*/
	);

	tgmpa( $plugins, $config );

}
