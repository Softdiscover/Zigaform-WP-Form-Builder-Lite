<div class="error">
	<p>Zigaform error: Your environment doesn't meet all of the system requirements listed below.</p>

	<ul class="ul-disc">
			<?php
			$zgfm_is_installed = UiformFormbuilderLite::another_zgfm_isInstalled();

			if ( $zgfm_is_installed['result'] ) {

				?>
			<li>
			<strong><?php echo $zgfm_is_installed['message']; ?></strong>
			<em><?php echo $zgfm_is_installed['message2']; ?></em>
		</li>
				<?php

			}


			?>
		<li>
			<strong>PHP <?php echo $this->php_version; ?>+</strong>
			<em>(You're running version <?php echo PHP_VERSION; ?>)</em>
		</li>

		<li>
			<strong>WordPress <?php echo $this->wp_version; ?>+</strong>
			<em>(You're running version <?php echo esc_html( $wp_version ); ?>)</em>
		</li>

	</ul>

	<p>If you need to upgrade your version of PHP you can ask your hosting company for assistance, and if you need help upgrading WordPress you can refer to <a href="http://codex.wordpress.org/Upgrading_WordPress">the Codex</a>.</p>
</div>
