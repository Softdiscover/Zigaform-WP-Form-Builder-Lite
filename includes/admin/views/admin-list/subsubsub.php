<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
?>
	<ul class="subsubsub">
		<li class="all"><a href="<?php echo admin_url('?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=list_uiforms'); ?>"
		class="<?php echo (intval($subcurrent)===1)?'current':''; ?>" 
		aria-current="page"><?php echo __( 'All', 'FRocket_admin' );?> <span class="count">(<?php echo $all;?>)</span></a> |</li>
		<li class="trash"><a href="<?php echo admin_url('?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=list_trash'); ?>" 
		class="<?php echo (intval($subcurrent)===2)?'current':''; ?>"  ><?php echo __( 'Trash', 'FRocket_admin' );?> <span class="count">(<?php echo $trash;?>)</span></a> </li>
	</ul>

