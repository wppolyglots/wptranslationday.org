<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h1><?php esc_html_e( TGGR_NAME ); ?> Settings</h1>

	<p>In order to use <?php echo esc_html( TGGR_NAME ); ?> you'll need to obtain access keys for each service that you would like to pull into your stream.</p>

	<p>Each service has a <em>Highlighted Accounts</em> field where you can enter a comma-separated list of account names. Any items posted from those accounts will be given the <code>tggr-highlighted-account</code> CSS class, so that they can be styled differently from other items.</p>

	<p>Each service also has a <em>Banned Accounts</em> field, where you can enter a comma-separated list of account names. Any future items posted from those accounts will be ignored, instead of being imported. Note that adding an account here will not retroactively delete any items that have already been imported.</p>

	<form method="post" action="options.php">
		<?php settings_fields( Tagregator::PREFIX . 'settings' ); ?>
		<?php do_settings_sections( Tagregator::PREFIX . 'settings' ); ?>

		<?php submit_button(); ?>
	</form>
</div> <!-- .wrap -->
