<?php
if (! defined('ABSPATH')) {
    exit('No direct script access allowed');
}
?>
<div class="uiform-editing-header">
   <nav class="sfdc-navbar sfdc-navbar-default" role="navigation">
  <div class="sfdc-navbar-inner">
<div class="sfdc-navbar-header">
          <button data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
            <span class="sr-only"><?php echo __('Toggle navigation', 'FRocket_admin'); ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="javascript:location.reload();" class="navbar-brand"><img title="Zigaform Form" src="<?php echo UIFORM_FORMS_URL; ?>/assets/backend/image/rockfm-logo-header.png"></a>
        </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="sfdc-nav sfdc-navbar-nav">
          <li class="divider-menu"></li>
        <li class="dropdown">
          <a href="#" class="sfdc-dropdown-toggle" data-toggle="sfdc-dropdown"> <span class="fa fa-file"></span> <?php echo __('Forms', 'FRocket_admin'); ?><span class="caret"></span></a>
          <ul class="sfdc-dropdown-menu" role="menu">
            <li><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=choose_mode'; ?>"><?php echo __('New', 'FRocket_admin'); ?></a></li>
             
            <li class="divider"></li>
            <li><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=list_uiforms'; ?>"><?php echo __('List forms', 'FRocket_admin'); ?></a></li>
          </ul>
        </li>
        <?php if (isset($_GET['zgfm_action']) && Uiform_Form_Helper::sanitizeInput($_GET['zgfm_action']) === 'create_uiform') { ?>
       
        <li class="divider-menu"></li>
        <li class="dropdown">
          <a href="#" class="sfdc-dropdown-toggle" 
             data-toggle="sfdc-dropdown"><span class="fa fa-desktop"></span> <?php echo __('Preview', 'FRocket_admin'); ?> <span class="caret"></span></a>
          <ul class="sfdc-dropdown-menu" role="menu">
            <li><a onclick="javascript:rocketform.previewform_showForm(1);" 
                   href="javascript:void(0);">
                       <?php echo __('desktop', 'FRocket_admin'); ?></a></li>
            <li><a onclick="javascript:rocketform.previewform_showForm(2);" 
                   href="javascript:void(0);">
                       <?php echo __('Tablet', 'FRocket_admin'); ?></a></li>
            <li><a onclick="javascript:rocketform.previewform_showForm(3);" 
                   href="javascript:void(0);">
                       <?php echo __('smartphone', 'FRocket_admin'); ?></a></li>
          </ul>
        </li>
        <?php } ?>
        <li class="divider-menu"></li>
        <li class="dropdown">
          <a href="#" class="sfdc-dropdown-toggle" data-toggle="sfdc-dropdown">
              <span class="fa fa-question-circle"></span> <?php echo __('Records', 'FRocket_admin'); ?><span class="caret"></span></a>
          <ul class="sfdc-dropdown-menu" role="menu">
            <li><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=records&zgfm_action=list_records'; ?>">
                <?php echo __('List all Forms', 'FRocket_admin'); ?>
                </a>
            </li>
            
             <?php if (ZIGAFORM_F_LITE === 1) { ?>
            <li><a onclick="javascript:rocketform.showFeatureLocked(this);"
                  data-blocked-feature="Filter record"
                   href="javascript:void(0);">
                    <?php echo __('Filter records', 'FRocket_admin'); ?> <span class="rkfm-express-lock-wrap" 
                         ><i class="fa fa-lock"></i></span></a>
               </li>
             <?php } else { ?>
                <li><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=records&zgfm_action=info_records_byforms'; ?>">
                   <?php echo __('Filter records', 'FRocket_admin'); ?></a>
               </li>
             <?php } ?>
            
           <?php if (ZIGAFORM_F_LITE === 1) { ?>
               <li><a onclick="javascript:rocketform.showFeatureLocked(this);"
                  data-blocked-feature="Custom report"
                   href="javascript:void(0);">
                <?php echo __('Custom Report', 'FRocket_admin'); ?> <span class="rkfm-express-lock-wrap" 
                         ><i class="fa fa-lock"></i></span></a>
                </li>
           <?php } else { ?>
            <li><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=records&zgfm_action=custom_report'; ?>">
                <?php echo __('Custom Report', 'FRocket_admin'); ?></a>
            </li>
           <?php } ?>
            
          </ul>
        </li>
        <li class="divider-menu"></li>
       
             <li >
            <a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=import_form'; ?>">
                <i class="fa fa-reply"></i> <?php echo __('Import', 'FRocket_admin'); ?></a></li>
        
                
        <li class="divider-menu"></li>
      
            <li>
                <a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=forms&zgfm_action=export_form'; ?>">
                <i class="fa fa-share"></i> <?php echo __('Export', 'FRocket_admin'); ?></a>
            </li>
        
          
        <li class="divider-menu"></li>
        <li ><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=records&zgfm_action=view_charts'; ?>">
            <span class="fa fa-area-chart"></span> <?php echo __('Charts', 'FRocket_admin'); ?> 
            </a>
        </li>
        <li class="divider-menu"></li>
        <li ><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=settings&zgfm_action=view_settings'; ?>">
            <i class="fa fa-cog"></i> <?php echo __('Settings', 'FRocket_admin'); ?>
            </a>
        </li>
        <li class="divider-menu"></li>
        <li ><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=settings&zgfm_action=backup_settings'; ?>">
            <i class="fa fa-cloud-download"></i> <?php echo __('Backup', 'FRocket_admin'); ?>
            </a>
        </li>
        <li class="divider-menu"></li>
        <li ><a href="javascript:void(0);" onclick="javascript:rocketform.guidedtour_load();">
            <span class="fa fa-question-circle"></span> <?php echo __('Guided tour', 'FRocket_admin'); ?>
            </a>
        </li>
        <li class="divider-menu"></li> 
        <li ><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=formbuilder&zgfm_contr=settings&zgfm_action=system_check'; ?>">
            <i class="fa fa-cog"></i> <?php echo __('System Check', 'FRocket_admin'); ?>
            </a>
        </li>
        <li class="divider-menu"></li>
        <li class="dropdown">
          <a href="#" class="sfdc-dropdown-toggle" data-toggle="sfdc-dropdown">
              <span class="fa fa-life-ring"></span> <?php echo __('Help', 'FRocket_admin'); ?><span class="caret"></span></a>
          <ul class="sfdc-dropdown-menu" role="menu">
            <li><a href="https://wordpress-form-builder.zigaform.com/docs/" target="_blank">
                <?php echo __('Documentation', 'FRocket_admin'); ?>
                </a>
            </li>
             <li><a href="https://github.com/Softdiscover/Zigaform-WP-Form-Builder-Lite/issues" target="_blank">
                   <?php echo __('Forum', 'FRocket_admin'); ?></a>
             </li>
             <li><a href="https://www.fb.com/groups/zigaform" target="_blank">
                   <?php echo __('Zigaform Community', 'FRocket_admin'); ?></a>
             </li>
          </ul>
        </li>
           <li class="divider-menu"></li>
        <li ><a href="<?php echo admin_url() . '?page=zgfm_form_builder&zgfm_mod=addon&zgfm_contr=backend&zgfm_action=list_extensions'; ?>">
            <span class="fa fa-plug"></span> <?php echo __('Extensions', 'FRocket_admin'); ?>
            </a>
        </li>
      </ul>
      <div id="uifm-loading-box" style="display:none;">
          <div class="uifm-alert"></div>
      </div>
       
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</div>

