<?php
//error_reporting( 0 );
/*---------------------------------------------------------
Plugin Name: WP Notification Which Website Uses Cookies
Plugin URI: https://wordpress.org/plugins/wp-notification-website-cookies/
Author: carlosramosweb
Author URI: https://www.criacaocriativa.com
Donate link: https://donate.criacaocriativa.com
Description: Display a notification message that website uses cookies on your website.
Text Domain: wp-notification-website-cookies
Domain Path: /languages/
Version: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
------------------------------------------------------------*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WP_Notification_Website_Cookies' ) ) {		
	class WP_Notification_Website_Cookies {
	    
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'init_functions' ) );
			register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );
		}

		public function init_functions() {
			if ( ! is_admin() ) {
				add_action( 'wp_footer', array( $this, 'display_notification' ), 50 );
				add_action( 'wp_footer', array( $this, 'footer_styles' ), 60 );
				add_action( 'wp_footer', array( $this, 'footer_scripts' ), 70 );				
			}
		}

		public function display_notification() { ?>
			<!---notification which website uses cookies-->
			<div class="cc-banner cc-type-opt-in cc-theme-classic cc-bottom" id="cc-window">
			    <p class="cc-message">
			        Ao navegar neste site, você aceita os cookies que usamos para melhorar sua experiência.
			    </p>
			    <button class="cc-btn cc-allow" id="init-cookies">Entendi</button>
			</div>
			<!---end-->
			<?php
		}

		public function footer_scripts() { 
			$remote_addr = sanitize_key( $_SERVER['REMOTE_ADDR'] ); 
			$remote_addr = (int)str_replace( ".", "", $remote_addr ); 
			?>
			<!---javascript-->
			<script type="text/javascript">
				const buttonInitCookies = document.getElementById( 'init-cookies' );

				buttonInitCookies.addEventListener( 'click', (e) => {
				  e.preventDefault();  
				    setCookie( "userip", "<?php echo $remote_addr; ?>", 7 );
				    var ccWindow = document.getElementsByClassName( 'cc-window' );
				    ccWindow.slideToggle( "slow" );
				    ccWindow.attr( "style", "bottom: -500px;" );
				});	

				function setCookie( cname, cvalue, exdays ) {
				    var d = new Date();
				    d.setTime( d.getTime() + ( exdays * 24 * 60 * 60 * 1000 ) );
				    var expires 	= "expires=" + d.toUTCString();
				    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
				}

				function getCookie( cname ) {
				    var name 			= cname + "=";
				    var decodedCookie 	= decodeURIComponent( document.cookie );
				    var ca = decodedCookie.split( ';' );
				    for( var i = 0; i < ca.length; i++ ) {
				        var c = ca[i];
				        while ( c.charAt( 0 ) == ' ' ) {
				            c = c.substring( 1 );
				        }
				        if ( c.indexOf( name ) == 0 ) {
				            return c.substring( name.length, c.length );
				        }
				    }
				    return "";
				}

				function checkCookie() {
					var ccWindow = document.getElementsByClassName( 'cc-window' );
				    var cname = getCookie( "userip" );
				    if ( cname != "<?php echo $remote_addr; ?>" ) {
				        ccWindow.attr( "style", "bottom: 0;" );
				    } else {
				        ccWindow.attr( "style", "bottom: -500px;" );
				    }
				}
				checkCookie();
			</script>
			<!---end-->
			<?php
		}


		public function footer_styles() { ?>
			<!---styles-->
			<style type="text/css">
			#cc-window {
				opacity: 1;
				transition: opacity 1s ease;
			}
			#cc-window.cc-banner {
				padding: 20px;
				width: 100%;
				flex-direction: row;
				align-items: center;
			}
			#cc-window.cc-banner.cc-bottom {
				left: 0;
				right: 0;
				bottom: 0;
			}
			#cc-window .cc-message {
				display: inline-block;
				margin-right: 1em;
			}
			#cc-window .cc-btn {
				display: inline-block;
				width: initial;
				background: #2271b1;
    			border-color: #2271b1;
    			color: #fff;
				padding: .4em .8em;
				font-size: .9em;
				font-weight: 700;
				border-width: 2px;
				border-style: solid;
				text-align: center;
				white-space: nowrap;
    			cursor: pointer;
    			-webkit-appearance: button;
			}
			#cc-window .cc-highlight .cc-btn:first-child {
				background-color: transparent;
				border-color: transparent
			}
			#cc-window .cc-highlight .cc-btn:first-child:focus,
			#cc-window .cc-highlight .cc-btn:first-child:hover {
				background-color: transparent;
				text-decoration: underline;
			}
			@media ( max-width: 1024px ){
				#cc-window .cc-message {
					display: inline-block;
					width: 100%;
					text-align: center;
				}
				#cc-window .cc-btn, 
				#cc-window .cc-btn:active, 
				#cc-window .cc-btn:focus {
					display: block;
					width: 100%;
				}
			}
			</style>
			<!---end-->
			<?php
		}

	}
	new WP_Notification_Website_Cookies();
	//=>
}