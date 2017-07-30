<?php if ( in_array( $setting, $textarea_settings, true ) ) : ?>

	<textarea
		id="<?php echo esc_attr( $class::SETTINGS_PREFIX . $setting ); ?>"
		name="<?php echo esc_attr( Tagregator::PREFIX ); ?>settings[<?php echo esc_attr( $class ); ?>][<?php echo esc_attr( $setting ); ?>] ); ?>"
		class="large-text"
	><?php
		echo esc_textarea( TGGRSettings::get_instance()->settings[ $class ][ $setting ] );
	?></textarea>

<?php elseif ( in_array( $setting, $checkbox_settings, true ) ) : ?>
	    <input type="hidden" name="<?php echo esc_attr( Tagregator::PREFIX ); ?>settings[<?php echo esc_attr( $class ); ?>][<?php echo esc_attr( $setting ); ?>] ); ?>" value="0" />
	    <input
	            type="checkbox"
	            id="<?php echo esc_attr( $class::SETTINGS_PREFIX . $setting ); ?>"
	            name="<?php echo esc_attr( Tagregator::PREFIX ); ?>settings[<?php echo esc_attr( $class ); ?>][<?php echo esc_attr( $setting ); ?>] ); ?>"
	            class="regular-text"
	            <?php checked( '1',  esc_attr( TGGRSettings::get_instance()->settings[ $class ][ $setting ] )); ?>
            value="1"/>
		<?php _e( 'Enabled' ); ?>

<?php else: ?>

	<input
		type="text"
		id="<?php echo esc_attr( $class::SETTINGS_PREFIX . $setting ); ?>"
		name="<?php echo esc_attr( Tagregator::PREFIX ); ?>settings[<?php echo esc_attr( $class ); ?>][<?php echo esc_attr( $setting ); ?>] ); ?>"
		class="regular-text"
		value="<?php echo esc_attr( TGGRSettings::get_instance()->settings[ $class ][ $setting ] ); ?>"
	/>

<?php endif; ?>
