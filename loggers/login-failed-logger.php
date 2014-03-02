<?php
if ( ! class_exists( 'Login_Failed_Logger' ) ){
	class Login_Failed_Logger extends Base_Logger{

		protected function get_log_settings(){
			return array(
				'hooks' => array( 'wp_login_failed' ),
			);
		}

		protected function recode( $args ){

			$username = $args[0];
			$password = isset( $_POST['pwd'] ) ? $_POST['pwd'] : '';
			return $username . ',' . $password;

		}

	}

}
