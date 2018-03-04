<?php

if (!defined('ABSPATH')) {
    exit('No direct script access allowed');
}
if (class_exists('Zigaform_b_notice')) {
   new Zigaform_b_notice();
    return;
}
 
class Zigaform_b_notice {
    
    private $tables = array();
    private $suffix = 'd-M-Y_H-i-s';
    
    /**
     * Constructor
     *
     * @mvc Controller
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        
        //admin notice
        add_action( 'admin_notices',                  array( $this, 'notice_add' ) );
	add_action( 'wp_ajax_zgfm_f_notice_dismiss', array( $this, 'notice_dismiss' ) );
        add_action( 'wp_ajax_zgfm_f_notice_rated', array( $this, 'notice_rated' ) );
        
        //footer
	add_filter( 'admin_footer_text', array( $this, 'notice_footer' ), 1, 2 );
       
    }
    
    
    /**
     * Adding admin notice
     *
     */
    public function notice_rated() {
        
            $data              = get_option( 'zigaform_f_notice_1', array() );
            $data['time'] 	 = time();
            $data['dismissed'] = true;
            $data['rated'] = true;

            update_option( 'zigaform_f_notice_1', $data );
            die();
    }
    
    
    /**
     * Adding admin notice
     *
     */
    public function notice_add() {
       
        //only for super admin
        if (!is_super_admin()) {
            return;
        }

        // Verify that we can do a check for reviews.
        $data = get_option('zigaform_f_notice_1');
        $time = time();
        $load = false;
        
        //if rated, not load
        if (( isset($data['rated']) && $data['rated'] ) ) {
                return;
        }
        
        
        if (!$data) {
            $data = array(
                'time' => $time,
                'dismissed' => false
            );
            $load = true;
        } else {
            // Check if it is dismissed
            $tmp_period= $data['time'] + DAY_IN_SECONDS;
            
            if (( isset($data['dismissed']) && !$data['dismissed'] ) 
                    && ( isset($data['time']) 
                    && ( $tmp_period <= $time ) )) {

                $load = true;
            }
        }

        // If not load, return early.
        if (!$load) {
            return;
        }
        
        // Update the notice option now.
        update_option('zigaform_f_notice_1', $data);

        // Fetch when plugin was initially installed
        $activated = get_option('zgfm_b_activated', array());

        $type = class_exists('UiformFormbuilder') ? 'pro' : 'lite';

        if (!empty($activated[$type])) {
            // continue if plugin is installed for at least 7 days
            $tmp_period= $activated[$type] + ( DAY_IN_SECONDS * 7 );
            if ($tmp_period > $time) {
                return;
            }
        } else {
            $activated[$type] = $time;
            update_option('zgfm_b_activated', $activated);
            return;
        } 
 
        // after 7 days, add the message
        $notice_url = 'https://wordpress.org/support/plugin/zigaform-form-builder-lite/reviews/?filter=5#new-post';
                $notice_heading = esc_html__( "Thanks for using Zigaform!", "FRocket_admin" );
                $notice_content = __( 'Hey, I noticed you created forms with Zigaform - that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'FRocket_admin' );
                $button_content1 = esc_html__( "Ok. you deserve it", "FRocket_admin" );
                $button_content2 = esc_html__( "Nope, maybe later", "FRocket_admin" );
                $button_content3 = esc_html__( "I already did", "FRocket_admin" );
            
        
        ?>
        <div class="notice zgfm-ext-notice" style="display: none;">
                <div class="zgfm-ext-notice-logo"><span></span></div>
                <div
                        class="zgfm-ext-notice-message">
                        <strong><?php echo $notice_heading; ?></strong>
                        <?php echo $notice_content; ?>
                </div>
                <div class="zgfm-ext-notice-cta">
                        <a href="<?php echo esc_url( $notice_url ); ?>" class="zgfm-ext-notice2-act button-primary" target="_blank">
                        <?php echo $button_content1; ?>
                        </a>
                        <button class="zgfm-ext-notice2-act2 button" 
                                data-msg="<?php esc_html_e( 'Saving', 'FRocket_admin'); ?>">
                                    <?php echo $button_content2; ?>
                        </button>
                        <button class="zgfm-ext-notice-dismiss zgfm-dismiss-rated" 
                                data-msg="<?php esc_html_e( 'Saving', 'FRocket_admin'); ?>">
                                    <?php echo $button_content3; ?>
                        </button>
                </div>
        </div><?php
        //Notice CSS
        wp_register_style( 'zgfm-style-global-css', UIFORM_FORMS_URL . '/assets/backend/css/global-ext.css', array(), UIFORM_VERSION );
        //Notice CSS
        wp_enqueue_style('zgfm-style-global-css');
        
        //Notice JS
        wp_register_script( 'zgfm-script-global-js', UIFORM_FORMS_URL . '/assets/backend/js/global-ext.js', array(
                'jquery'
        ), UIFORM_VERSION );
        
        //Notice JS
        wp_enqueue_script('zgfm-script-global-js', '', array(), '', true );
          
    }
    
   
    /**
     * Dismiss notice
     *
     */
    public function notice_dismiss() {

            $data              = get_option( 'zigaform_f_notice_1', array() );
            $data['time'] 	 = time();
            $data['dismissed'] = true;
            $data['rated'] = false;
            
            update_option( 'zigaform_f_notice_1', $data );
            die();
    }
    
    
    /**
     * When user is on zigaform admin page, display footer text that asks them to rate us.
     *
     */
    public function notice_footer( $text ) {

            global $current_screen;

            if ( (!empty( $current_screen->id ) && strpos( $current_screen->id, 'zgfm_form_builder' ) !== false )
                 || ( !empty( $current_screen->id ) && strpos( $current_screen->id, 'zigaform-builder-' ) !== false    )
                    ) {
                
                    if(ZIGAFORM_F_LITE){
                        $url  = 'https://wordpress.org/support/plugin/zigaform-form-builder-lite/reviews/?filter=5#new-post';
                        $text = sprintf( __( 'Please rate <strong>Zigaform</strong> <a href="%s" target="_blank" rel="noopener" >&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%s" target="_blank">WordPress.org</a> to help us spread the word. Thank you from the Zigaform team!', 'FRocket_admin' ), $url, $url );
                    }else{
                        $url  = 'https://codecanyon.net/item/zigaform-wordpress-form-builder/11057544';
                        $text = sprintf( __( 'Please rate <strong>Zigaform</strong> <a href="%s" target="_blank" rel="noopener" >&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%s" target="_blank">Codecanyon.net</a> to help us spread the word. Thank you from the Zigaform team!', 'FRocket_admin' ), $url, $url );
                    }
                
                    
            }
            return $text;
    }
    
}

new Zigaform_b_notice();
?>
