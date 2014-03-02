<div class="wrap">
	<form action="options.php" method="post">
		<?php screen_icon(); ?>
		<h2><?php _e('Custom Loggers Settings'); ?></h2>

		<div class="wrap">
		<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'options-general.php?page=' . self::SETTINGS_PAGE_SLUG . '&type=loggers' ) ?>" class="nav-tab <?php echo ( 'loggers' == $type ) ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( __( 'Loggers' ) ); ?></a>
		</h2>

		<table class="form-table">
			<thead>
				<tr>
					<th><?php _e( 'Name' ) ?></th>
					<th><?php _e( 'Activate' ) ?></th>
					<th><?php _e( 'DirPath' ) ?></th>
					<th><?php _e( 'Filename' ) ?></th>
					<th><?php _e( 'File Format' ) ?></th>
					<th><?php _e( 'File Date Format' ) ?></th>
					<th><?php _e( 'Recode Format' ) ?></th>
					<th><?php _e( 'Recode Datetime format' ) ?></th>
				</tr>
			</thead>
			<tbody>
			<?php for ($i = 0; $i < count( $elements ); $i++) { ?>
				<tr valign="top">
					<th scope="row">
						<label for="<?php self::settings_id( $elements[$i] ); ?>"><?php echo $elements[$i] ?></label>
					</th>
					<td>
						<label>
							<input type="checkbox"
								id="<?php self::settings_id( $elements[$i] ); ?>"
								name="<?php self::settings_name( 'active_' . $type, 'echo', 'array' ); ?>"
								value="<?php echo esc_html( $elements[$i] ) ?>"
								<?php checked( in_array( $elements[$i], $settings['active_' . $type] ) ); ?> />
						</label>
					</td>
					<td>
						<label>
							<input type="text"
								id="<?php self::settings_id( $elements[$i] ); ?>"
								name="<?php echo self::settings_name( 'dirpath', false ) . '[' .  $elements[$i] . ']' ?>"
								value="<?php echo esc_html( isset( $settings['dirpath'][$elements[$i]] ) ? $settings['dirpath'][$elements[$i]] : self::get_default_log_settings( 'dirpath', $elements[$i] ) ) ?>"
								/>
						</label>
					</td>
					<td>
						<label>
							<input type="text"
								id="<?php self::settings_id( $elements[$i] ); ?>"
								name="<?php echo self::settings_name( 'filename', false ) . '[' .  $elements[$i] . ']' ?>"
								value="<?php echo esc_html( isset( $settings['filename'][$elements[$i]] ) ? $settings['filename'][$elements[$i]] : self::get_default_log_settings( 'filename', $elements[$i] ) ) ?>"
								/>
						</label>
					</td>
					<td>
						<label>
							<input type="text"
								id="<?php self::settings_id( $elements[$i] ); ?>"
								name="<?php echo self::settings_name( 'file_format', false ) . '[' .  $elements[$i] . ']' ?>"
								value="<?php echo esc_html( isset( $settings['file_format'][$elements[$i]] ) ? $settings['file_format'][$elements[$i]] : self::get_default_log_settings( 'file_format', $elements[$i] ) ) ?>"
								/>
						</label>
					</td>
					<td>
						<label>
							<input type="text"
								id="<?php self::settings_id( $elements[$i] ); ?>"
								name="<?php echo self::settings_name( 'file_date_format', false ) . '[' .  $elements[$i] . ']' ?>"
								value="<?php echo esc_html( isset( $settings['file_date_format'][$elements[$i]] ) ? $settings['file_date_format'][$elements[$i]] : self::get_default_log_settings( 'file_date_format', $elements[$i] ) ) ?>"
								/>
						</label>
					</td>
					<td>
						<label>
							<input type="text"
								id="<?php self::settings_id( $elements[$i] ); ?>"
								name="<?php echo self::settings_name( 'recode_format', false ) . '[' .  $elements[$i] . ']' ?>"
								value="<?php echo esc_html( isset( $settings['recode_format'][$elements[$i]] ) ? $settings['recode_format'][$elements[$i]] : self::get_default_log_settings( 'recode_format', $elements[$i] )) ?>"
								/>
						</label>
					</td>
					<td>
						<label>
							<input type="text"
								id="<?php self::settings_id( $elements[$i] ); ?>"
								name="<?php echo self::settings_name( 'recode_datetime_format', false ) . '[' .  $elements[$i] . ']' ?>"
								value="<?php echo esc_html( isset( $settings['recode_datetime_format'][$elements[$i]] ) ? $settings['recode_datetime_format'][$elements[$i]] : self::get_default_log_settings( 'recode_datetime_format', $elements[$i] ) ) ?>"
								/>
						</label>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>

		<p class="submit">
			<?php settings_fields( self::SETTINGS_NAME ); ?>
			<input type="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>" />
		</p>
		</div>
	</form>
</div>
