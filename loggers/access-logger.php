<?php
if ( ! class_exists( 'Access_Logger' ) ){
	class Access_Logger extends Base_Logger{

		protected function get_log_settings(){
			return array(
				'hooks' => array( 'shutdown' ),
			);
		}

		protected function recode( $args ){
			global $timestart, $timeend;
			$timetotal = timer_stop();
			$current_user = wp_get_current_user();
			return date( 'Y/m/d H:i:s', $timestart ) . ',' . date( 'Y/m/d H:i:s', $timeend ) . ',' . $timetotal . ',' . $current_user->ID . ','. $current_user->user_login;
		}
	}

}
