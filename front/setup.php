<?php
/**
 * Author: Alin Marcu
 * Author URI: https://deconf.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();

if ( ! class_exists( 'GADWP_Frontend_Setup' ) ) {

	final class GADWP_Frontend_Setup {

		private $gadwp;

		public function __construct() {
			$this->gadwp = GADWP();

			// Styles & Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'load_styles_scripts' ) );
		}

		/**
		 * Styles & Scripts conditional loading
		 *
		 * @param
		 *            $hook
		 */
		public function load_styles_scripts() {

			/*
			 * GADWP main stylesheet
			 */
			wp_enqueue_style( 'ga_dash-front', GADWP_URL . 'front/css/item-reports.css', null, GADWP_CURRENT_VERSION );

			/*
			 * Item reports Styles & Scripts
			 */

			if ( GADWP_Tools::check_roles( $this->gadwp->config->options['ga_dash_access_front'] ) && $this->gadwp->config->options['frontend_item_reports'] ) {

				wp_enqueue_style( 'gadwp-nprogress', GADWP_URL . 'tools/nprogress/nprogress.css', null, GADWP_CURRENT_VERSION );

				wp_enqueue_style( 'gadwp_frontend_item_reports', GADWP_URL . 'front/css/item-reports.css', null, GADWP_CURRENT_VERSION );

				$country_codes = GADWP_Tools::get_countrycodes();
				if ( $this->gadwp->config->options['ga_target_geomap'] && isset( $country_codes[$this->gadwp->config->options['ga_target_geomap']] ) ) {
					$region = $this->gadwp->config->options['ga_target_geomap'];
				} else {
					$region = false;
				}

				wp_enqueue_style( "wp-jquery-ui-dialog" );

				if ( ! wp_script_is( 'googlejsapi' ) ) {
					wp_register_script( 'googlejsapi', 'https://www.google.com/jsapi' );
				}

				wp_enqueue_script( 'gadwp-nprogress', GADWP_URL . 'tools/nprogress/nprogress.js', array( 'jquery' ), GADWP_CURRENT_VERSION );

				wp_enqueue_script( 'gadwp_frontend_item_reports', GADWP_URL . 'tools/js/item-reports.js', array( 'gadwp-nprogress', 'googlejsapi', 'jquery', 'jquery-ui-dialog' ), GADWP_CURRENT_VERSION );

				/* @formatter:off */
				wp_localize_script( 'gadwp_frontend_item_reports', 'gadwp_item_data', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'gadwp_frontend_item_reports' ),
					'dateList' => array(
						'today' => __( "Today", 'ga-dash' ),
						'yesterday' => __( "Yesterday", 'ga-dash' ),
						'7daysAgo' => sprintf( __( "Last %d Days", 'ga-dash' ), 7 ),
						'14daysAgo' => sprintf( __( "Last %d Days", 'ga-dash' ), 14 ),
						'30daysAgo' =>  sprintf( __( "Last %d Days", 'ga-dash' ), 30 ),
						'90daysAgo' =>  sprintf( __( "Last %d Days", 'ga-dash' ), 90 ),
						'365daysAgo' =>  sprintf( _n( "%s Year", "%s Years", 1, 'ga-dash' ), __('One', 'ga-dash') ),
						'1095daysAgo' =>  sprintf( _n( "%s Year", "%s Years", 3, 'ga-dash' ), __('Three', 'ga-dash') ),
					),
					'reportList' => array(
						'uniquePageviews' => __( "Unique Views", 'ga-dash' ),
						'users' => __( "Users", 'ga-dash' ),
						'organicSearches' => __( "Organic", 'ga-dash' ),
						'pageviews' => __( "Page Views", 'ga-dash' ),
						'visitBounceRate' => __( "Bounce Rate", 'ga-dash' ),
						'locations' => __( "Location", 'ga-dash' ),
						'referrers' => __( "Referrers", 'ga-dash' ),
						'searches' => __( "Searches", 'ga-dash' ),
						'trafficdetails' => __( "Traffic Details", 'ga-dash' ),
					),
					'i18n' => array(
							__( "A JavaScript Error is blocking plugin resources!", 'ga-dash' ), //0
							__( "Traffic Mediums", 'ga-dash' ),
							__( "Visitor Type", 'ga-dash' ),
							__( "Social Networks", 'ga-dash' ),
							__( "Search Engines", 'ga-dash' ),
							__( "Unique Views", 'ga-dash' ),
							__( "Users", 'ga-dash' ),
							__( "Page Views", 'ga-dash' ),
							__( "Bounce Rate", 'ga-dash' ),
							__( "Organic Search", 'ga-dash' ),
							__( "Pages/Session", 'ga-dash' ),
							__( "Invalid response, more details in JavaScript Console (F12).", 'ga-dash' ),
							__( "Not enough data collected", 'ga-dash' ),
							__( "This report is unavailable", 'ga-dash' ),
							__( "report generated by", 'ga-dash' ), //14
							__( "This plugin needs an authorization:", 'ga-dash' ) . ' <strong>' . __( "authorize the plugin", 'ga-dash' ) . '</strong>!',
					),
					'colorVariations' => GADWP_Tools::variations( $this->gadwp->config->options['ga_dash_style'] ),
					'region' => $region,
					'filter' => $_SERVER["REQUEST_URI"],
					'scope' => 'front',
				 )
				);
				/* @formatter:on */
			}
		}
	}
}
