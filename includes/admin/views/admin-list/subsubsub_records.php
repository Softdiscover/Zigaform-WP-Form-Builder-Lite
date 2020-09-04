<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
?>
	<ul class="subsubsub">
		<li class="all"><a href="<?php echo admin_url('?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=records&zgfm_action=list_records'); ?>" 
		class="<?php echo (intval($subcurrent)===1)?'current':''; ?>" 
		aria-current="page"><?php echo __( 'All', 'FRocket_admin' );?> <span class="count">(<?php echo $all;?>)</span></a> |</li>
		<li class="trash"><a href="<?php echo admin_url('?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=records&zgfm_action=list_trash_records'); ?>" 
		class="<?php echo (intval($subcurrent)===2)?'current':''; ?>"  > <?php echo __( 'Trash', 'FRocket_admin' );?> <span class="count">(<?php echo $trash;?>)</span></a> </li>
	</ul>

