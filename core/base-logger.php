<?php
if ( ! class_exists( 'Base_Logger' ) ){
	class Base_Logger{

		private $settings = array();

		public function __construct( $args = array() ){
			$this->settings = wp_parse_args( $args, $this->get_default_settings() );
			$this->sethooks();
		}

		protected function get_default_settings(){
			$settings =  array(
				'dirpath' => plugin_dir_path( __FILE__ ) . '../logs',
				'filename' => get_class( $this ),
				'hooks' => array(),
				'file_format' => '%file-%date.log',
				'file_date_format' => 'Ymd',
				'recode_format' => '%datetime,%recode',
				'recode_datetime_format' => '[Ymd H:i:s]'
			);
			return wp_parse_args( $this->get_log_settings(), $settings );
		}

		protected function get_log_settings(){
			return  array();
		}

		protected function sethooks(){
			if ( is_array( $this->settings['hooks'] ) ){
				foreach ( $this->settings['hooks'] as $hook ){
					if ( method_exists( $this, 'log_' . $hook ) ){
						add_filter( $hook, array( $this, 'log_' . $hook ) , 99, 10 );
					} else {
						add_filter( $hook, array( $this, 'log' ), 99, 10 );
					}
				}
			}

		}

		protected function recodeformat( $args, $recode_format = '',  $recode_datetime_format = '' ){

			$recode = $recode_format;
			$recode = str_replace( '%recode', $this->recode( $args ), $recode );
			$recode = str_replace( '%datetime', date( $recode_datetime_format ), $recode );
			$recode = str_replace( '%ip', $_SERVER['REMOTE_ADDR'], $recode );
			$recode = str_replace( '%schema', is_ssl() ? 'https' : 'http', $recode );
			$recode = str_replace( '%host', $_SERVER['HTTP_HOST'], $recode );
			$recode = str_replace( '%url', $_SERVER['REQUEST_URI'], $recode );
			return $recode;

		}

		protected function fileformat( $fileformat = '%file', $filename = 'filename', $dateformat = '' ){

			$file = $fileformat;
			$file = str_replace( '%date', date( $dateformat ), $file );
			$file = str_replace( '%file', $filename, $file );

			return $file;

		}

		public function log(){

			$args_num = func_num_args();
			$args = func_get_args();

			$recode = $this->recodeformat( $args, $this->get_settings('recode_format'), $this->get_settings('recode_datetime_format') );
			$this->write( $recode, $this->get_filepath() );
			if ( 0 < $args_num ){
				return $args[0];
			}

		}

		public function get_settings( $key = null ){
			if ( !is_null( $key ) ){
				return isset( $this->settings[$key] ) ? $this->settings[$key] : '';
			} else {
				return $this->settings;
			}
		}

		public function get_filepath(){
			return trailingslashit( $this->settings['dirpath'] ) . $this->fileformat( $this->settings['file_format'], $this->settings['filename'],  $this->settings['file_date_format'] );
		}

		public function write( $recode, $filepath ){
			error_log( $recode . PHP_EOL, 3, $filepath );
		}

		protected function recode( $args ){

			return implode( ',', $args );

		}

	}

}
