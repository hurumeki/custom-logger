<?php
if ( ! class_exists( 'Login_Logger' ) ){
	class Login_Logger extends Base_Logger{

		protected function get_log_settings(){
			return array(
				'hooks' => array( 'wp_login' ),
			);
		}

		protected function recode( $args ){

			$user = $args[1];
			return $user->ID . ',' . $user->user_login;

		}

	}

}
