<?php
if ( ! class_exists( 'Sql_Logger' ) ){
	class Sql_Logger extends Base_Logger{

		protected function get_log_settings(){
			return array(
				'hooks' => array( 'query' ),
			);
		}
	}

}
