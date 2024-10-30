<?php
	/*
	Plugin Name: Inside Social
	Plugin URI: https://insidesoci.al/socialgateway/main
	Description: InsideSocial allows publishers to easily create premium content by requiring or suggesting that viewers connect socially 
	via a social gateway.
	Version: 2.0.9
	Author: InsideSocial
	Author URI: https://insidesoci.al/socialgateway/main
	License: GPLv2
	*/

	// init process
	add_action('init','tcustom_addbuttons');

	function tcustom_addbuttons() {
		if( ! current_user_can('edit_posts') && ! current_user_can('edit_pages')) {
			return;
		}

		if( get_user_option('rich_editing') == 'true') {
			add_filter('mce_external_plugins', 'add_tcustom_tinymce_plugin');
			add_filter('mce_buttons','register_tcustom_button');
		}
	}

	function register_tcustom_button($buttons) {
		array_push($buttons, '|', "insideSocial");
		return $buttons;
	}

	function add_tcustom_tinymce_plugin($plugin_array) {
		$plugin_array['insideSocial'] = WP_PLUGIN_URL .'/inside-social/tinymce-custom/mce/insidesocial/editor_plugin.js';
		return $plugin_array;
	}

	// setup gateway shortcode
	add_action('init','register_shortcodes');
	function register_shortcodes() {
		add_shortcode('is-gateway','is_show_gateway');
	}

	function is_show_gateway($atts,$content = null) {
		extract(shortcode_atts(array(
			      'name' => 'main',
			      'id' => 'main',
			   ), $atts));

		if ( is_single() || is_page() ) {
			$div = '<div display="none" id="is1"></div>';

			if(!is_null($content) && !empty($content)) {
				$div = '<div display="none" id="is1">' . do_shortcode($content)  .'</div>';
			}
			$script = '<script type="text/javascript">var _isc9="' . $name . '",_isn9="' . $id . '";(function(d, tag, id) { var s, r = d.getElementsByTagName(tag)[0]; if(!d.getElementById(id)) { s = d.createElement(tag);s.id = id;s.src = "//cdn1.insidesoci.al/static/js/isloader.js"; r.parentNode.insertBefore(s, r); }  })(document, "script", "is-gateway");</script>';

			return $div . $script;
		} else {
			return '';
		}
	}

	add_action('wp_head','is_inject_javascript');

	function is_inject_javascript() {
		echo '<!-- Inside Social Wordpress Plugin: 2.0.7 -->';
		echo "\n";
		echo '<!-- Find out More: https://www.insidesoci.al -->';
		echo "\n";
		//echo '<script type="text/javascript" src="//cdn1.insidesoci.al/static/js/ist.js"></script>';
		//echo "\n";
		//$imageid = uniqid ();
		//$hash = sha1($_SERVER['HTTP_HOST'] . '' . $_SERVER['REQUEST_URI']);
		//echo '<meta property="og:image" content="http://data.insidesoci.al/img/'. $hash . "/" . $imageid . '/" />';
		//echo "\n";		
	}
	// Hook for making showing the gateway on premium content
	add_action('the_content','is_display_gateway');
	function is_display_gateway($content) {
		global $post;
		$values = get_post_custom( $post->ID );
		$check = isset( $values['inside_social_chkbx'] ) ? esc_attr( $values['inside_social_chkbx'][0]) : 'off';
		if($check === 'off' || $check === '') {
			return $content;
		}
		
		if ( is_single() || is_page() )
		{
			$script = get_option('inside_social_template_data');
			return $script . ' ' . $content;
		}
		
		return $content;
	}
?>
