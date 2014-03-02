<?php
/*
Plugin Name: Custom Logger
Plugin URI:
Description: ログ出力を行うプラグイン
Author: hurumeki
Version: 1.0
Author URI:
*/

if ( ! class_exists( 'Custom_Logger' ) ){
	class Custom_Logger{

		const VERSION = '1.0';
		const SETTINGS_NAME = '_custom_logger_settings';
		const SETTINGS_PAGE_SLUG = 'custom_logger_settings';

		private static $instance = null;

		private static $default_settings = null;

		private static $settings = null;

		private static $loggers = null;

		public static function get_instance(){
			if ( is_null( self::$instance ) )
				self::$instance = new self;
			return self::$instance;
		}

		private function __construct(){
			$this->include_file();
			if( is_admin() ) {
				// Administrative only actions
				add_action('admin_init', array( &self::$instance, 'register_settings' ) );
				add_action('admin_menu', array( &self::$instance, 'add_settings_page' ) );
			}
			$this->get_settings();
			$this->set_loggers();
		}

		public function register_settings(){
			register_setting( self::SETTINGS_NAME, self::SETTINGS_NAME, array( &self::$instance, 'sanitize_settings') );
		}

		public function sanitize_settings( $settings ){
			$defaults = self::$instance->get_settings();
			$settings = wp_parse_args( $settings, $this->get_settings_default() );
			$settings = shortcode_atts( $defaults, $settings );
			return $settings;
		}

		private function get_settings_default() {
			if( is_null( self::$default_settings ) ) {
				self::$default_settings = array(
					// default settings
					'active_loggers' => array(),
					'dirpath' => '',
					'filename' => '',
					'file_format' => '',
					'file_date_format' => '',
					'recode_format' => '',
					'recode_datetime_format' => '',
				);
			}
			return self::$default_settings;
		}

		private function get_settings(){
			if ( is_null( self::$settings ) ) {
				$defaults = $this->get_settings_default();
				$settings = get_option( self::SETTINGS_NAME );
				self::$settings = shortcode_atts( $defaults, $settings );
			}
			return self::$settings;
		}

		private function get_log_settings( $class ){

			$settings_names = array(
					'dirpath',
					'filename',
					'file_format',
					'file_date_format',
					'recode_format',
					'recode_datetime_format'
				);

			$log_settings = array();
			foreach ($settings_names as $name) {
				if ( isset( self::$settings[$name][$class] ) ){
					$log_settings[$name] = self::$settings[$name][$class];
				}
			}

			return $log_settings;
		}

		public static function get_default_log_settings( $key, $class ){

			$default_log_settings = array(
					'dirpath' => dirname( __FILE__ ) . '/logs',
					'filename' => $class,
					'file_format' => '%file-%date.log',
					'file_date_format' => 'Ymd',
					'recode_format' => '[%datetime],%recode',
					'recode_datetime_format' => 'Y/m/d H:i:s'
				);

			return isset( $default_log_settings[$key] ) ? $default_log_settings[$key] : '';
		}

		public static function add_settings_page(){
			$settings_page_hook_suffix = add_options_page( __( 'Custom Logger - Settings' ), __( 'Custom Logger' ), 'manage_options', self::SETTINGS_PAGE_SLUG, array( &self::$instance, 'display_settings_page' ));
	 		if($settings_page_hook_suffix) {
	 			add_action( 'load-{$settings_page_hook_suffix}', array( &self::$instance, 'load_settings_page' ) );
	 		}
		}

		public static function load_settings_page(){
		}

		public static function display_settings_page(){
			$settings = self::$instance->get_settings();
			$type = ( isset( $_REQUEST['type'] ) && in_array( $type, array( 'loggers' ) ) ) ? $_REQUEST['type'] : 'loggers';
			$elements = array_map( array( self::$instance, 'convert_file_to_classname' ), self::$instance->get_files($type) );
			include( 'admin/settings.php' );
		}

		private function include_file(){
			include_once( 'core/base-logger.php' );
			$files = $this->get_files( 'loggers' );
			foreach ( $files as $file ){
				include_once( dirname( __FILE__ ) . '/loggers/' . $file . '.php' );
			}
		}

		private function set_loggers(){
			if ( is_null( self::$loggers ) ) {
				self::$loggers = array();
				$active_loggers = self::$settings['active_loggers'];
				foreach ( $active_loggers as $key => $value ){
					self::$loggers[$key] = new $value( $this->get_log_settings( $value ) );
				}
			}
		}

		public static function settings_id($key, $echo = true) {
			$settings_name = self::SETTINGS_NAME;

			$id = "{$settings_name}-{$key}";
			if($echo) {
				echo $id;
			}

			return $id;
		}

		public static function settings_name($key, $echo = true, $type = 'scala') {
			$settings_name = self::SETTINGS_NAME;

			$name = "{$settings_name}[{$key}]";
			if ( 'array' == $type ) {
				$name .= "[]";
			}
			if( $echo ) {
				echo $name;
			}

			return $name;
		}

		private function convert_file_to_classname( $filename ) {
			
			if ( substr($filename, -4) == '.php' )
				$filename = basename( $file, '.php');

			return implode( '_', array_map( 'ucwords', explode( '-', $filename) ) );
		}

		private function get_files( $type ) {
			$folder = dirname( __FILE__ ) . '/' . $type;
			$dir = @opendir( $folder );
			$files = array();
			if ( $dir ) {
				while (($file = readdir( $dir ) ) !== false ) {
					if ( substr($file, 0, 1) == '.' )
						continue;
					if ( substr($file, -4) == '.php' )
						$files[] = basename( $file, '.php');
				}
				closedir( $dir );
			}
			return $files;
		}

	}

	Custom_Logger::get_instance();

}
