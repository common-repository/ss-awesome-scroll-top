<?php 

/*
Plugin Name: SS Awesome Scroll Top
Plugin URI: http://ohidul.com/plugins/ss-awesome-scroll-top/
Description: This plugin will activate a awesome scroll to top button to your website. Apply your plugin settings from here */ echo home_url();  /*
Author: Ohidul Islam
Version: 1.0
Author URI: http://ohidul.com/
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/


define('SS_AWESOME_SCROLL_TOP_DIR', WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__)) . '/');


function easy_gallery_scripts() {

	wp_enqueue_style('ss-ast-css', SS_AWESOME_SCROLL_TOP_DIR . 'css/style.css');
	wp_enqueue_script('jquery');
	// wp_enqueue_script('main-js', SS_AWESOME_SCROLL_TOP_DIR . 'js/main.js', array('jquery'));
	
}
add_action('init', 'easy_gallery_scripts');


/*
*****************************************************************
ADD A SETTINGS BUTTON TO PLUGIN
*******************************************************************
*/

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );

function add_action_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'options-general.php?page=ss-awesome-scroll-top-options' ) . '">Settings</a>',
 );
return array_merge( $links, $mylinks );
}

/*
*****************************************************************
ADD SETTINGS MENU
*******************************************************************
*/

function ss_awesome_scroll_top_settings() {

  add_options_page("SS Awesome Scroll Top Options", "SS Awesome Scroll Top", "manage_options", "ss-awesome-scroll-top-options", "ss_awesome_scroll_top_options"); 
}
add_action("admin_menu", "ss_awesome_scroll_top_settings");






function ss_asb_initializing() {

    add_settings_section("section", "", null, "ss-awesome-scroll-top-options");

    //  enabling ss awesome scroll top
    add_settings_field("ss-ast-enabled", "Enable", "ss_ast_enable_display", "ss-awesome-scroll-top-options", "section");  
    register_setting("section", "ss-ast-enabled");

    //  ss awesome scroll top image
    add_settings_field("ss-ast-btn-img", "Select button rocket", "ss_ast__btn_img_display", "ss-awesome-scroll-top-options", "section");  
    register_setting("section", "ss-ast-btn-img");


    //  ss awesome scroll top button opacity
    add_settings_field("ss-ast-btn-img-opacity", "Button image opacity", "ss_ast__btn_img_opacity_display", "ss-awesome-scroll-top-options", "section");  
    register_setting("section", "ss-ast-btn-img-opacity");

    //  ss awesome scroll top scroll offset
    add_settings_field("ss-ast-btn-scroll-offset", "Scroll offset", "ss_ast_btn_scroll_offset", "ss-awesome-scroll-top-options", "section");  
    register_setting("section", "ss-ast-btn-scroll-offset");


    //  ss awesome scroll top time
    add_settings_field("ss-ast-btn-scroll-top-time", "Scroll top animation time", "ss_ast_btn_scroll_top_time", "ss-awesome-scroll-top-options", "section");  
    register_setting("section", "ss-ast-btn-scroll-top-time");

}
add_action("admin_init", "ss_asb_initializing");
/*
*****************************************************************
SS AWESOME SCROLL TOP CALLBACK FUNCTIONS
*******************************************************************
*/


function ss_ast_enable_display() { ?>
    <input type="checkbox" name="ss-ast-enabled" value="enable" <?php checked('enable', get_option('ss-ast-enabled'), true); ?>
<?php }

function ss_ast__btn_img_display() { ?>
	<input type="radio" id="rocket1" name="ss-ast-btn-img" value="rocket1" <?php checked('rocket1', get_option('ss-ast-btn-img'), true); ?>
	>
	<label for="rocket1"><img src="<?php echo SS_AWESOME_SCROLL_TOP_DIR ?>img/rocket1.png" alt=""></label> 

	<input type="radio" id="rocket2" name="ss-ast-btn-img" value="rocket2" <?php checked('rocket2', get_option('ss-ast-btn-img'), true); ?>
	>
	<label for="rocket2"><img src="<?php echo SS_AWESOME_SCROLL_TOP_DIR ?>img/rocket2.png" alt=""></label> 

	<input type="radio" id="rocket3" name="ss-ast-btn-img" value="rocket3" <?php checked('rocket3', get_option('ss-ast-btn-img'), true); ?>
	>
	<label for="rocket3"><img src="<?php echo SS_AWESOME_SCROLL_TOP_DIR ?>img/rocket3.png" alt=""></label> 

<?php }

function ss_ast__btn_img_opacity_display() { ?>
    <input type="text" name="ss-ast-btn-img-opacity" value="<?php echo get_option('ss-ast-btn-img-opacity'); ?>"> %
<?php }

function ss_ast_btn_scroll_offset() { ?>
    <input type="text" name="ss-ast-btn-scroll-offset" value="<?php echo get_option('ss-ast-btn-scroll-offset'); ?>"> px
<?php }

function ss_ast_btn_scroll_top_time() { ?>
    <input type="text" name="ss-ast-btn-scroll-top-time" value="<?php echo get_option('ss-ast-btn-scroll-top-time'); ?>"> in milisecond
<?php }




function ss_awesome_scroll_top_options()
{
  ?>
      <div class="wrap">
         <h1>SS Awesome Scroll Top</h1>
  
         <form method="post" action="options.php">
            <?php
               settings_fields("section");
  
               do_settings_sections("ss-awesome-scroll-top-options");
                 
               submit_button(); 
            ?>
         </form>
      </div>
   <?php
}







/*
*****************************************************************
ACTIVATING SS AWESOME SCROLL TOP
*******************************************************************
*/


function ss_ast_active() {
		$enable = get_option('ss-ast-enabled');

		if($enable == 'enable') :
	?>

	<style>
		#awesome-scroll-top {
		    opacity: 0.<?php echo get_option('ss-ast-btn-img-opacity'); ?> !important;
		}
	</style>
	
	<script>

		jQuery(document).ready(function(){

			var pluginDir = '<?php echo SS_AWESOME_SCROLL_TOP_DIR; ?>',
				scrollTopImg =  pluginDir + 'img/<?php echo get_option('ss-ast-btn-img'); ?>.png',
				createScrollTopBtn = '<a href="#" id="awesome-scroll-top"><img src="'+scrollTopImg+'" alt="Top"></a>';


			jQuery('body').append(createScrollTopBtn);

			// animating awesome scroll top
			jQuery(function () {
				jQuery(window).scroll(function () {
					if (jQuery(this).scrollTop() > <?php echo get_option('ss-ast-btn-scroll-offset'); ?>) {
						jQuery('#awesome-scroll-top').css('bottom','15px');
					} else {
						jQuery('#awesome-scroll-top').css('bottom','-120px');
					}
				});

				// scroll body to 0px on click
				jQuery('#awesome-scroll-top').click(function (e) {
					e.preventDefault();
					jQuery('body,html').animate({
						scrollTop: 0
					}, <?php echo get_option('ss-ast-btn-scroll-top-time'); ?>);
				});
			});

		});

	</script>

<?php 
endif;
}
add_action('wp_head', 'ss_ast_active');