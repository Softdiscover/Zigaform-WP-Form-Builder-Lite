<?php
if (!defined('ABSPATH')) {exit('No direct script access allowed');}
?>
    <div class="notice notice-info is-dismissible zgfm-f-notice-message">
            <p><?php echo __( 'Hey, I noticed you created a contact form with Zigaform - that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'FRocket_admin' ); ?></p>
            <p><strong>&#8594;<?php echo __( 'Laranginha<br>Zigaform team', 'FRocket_admin' ); ?></strong></p>
            <p>
                    <a href="https://wordpress.org/support/plugin/zigaform-form-builder-lite/reviews/?filter=5#new-post"
                       class="zgfm-notice-1-dismiss-opt zgfm-notice-1-rate-trigger"
                       target="_blank"
                       rel="noopener"
                        >
                           <?php echo __( 'Ok, you deserve it', 'FRocket_admin' ); ?></a><br>
                    <a href="#" 
                       class="zgfm-notice-1-dismiss-opt" 
                      rel="noopener"
                        ><?php echo __( 'Nope, maybe later', 'FRocket_admin' ); ?></a>
                           <br>
                    <a href="#" 
                       class="zgfm-notice-1-opt-rated" 
                     rel="noopener"
                        ><?php echo __( 'I already did', 'FRocket_admin' ); ?></a>
            </p>
    </div>
    <script type="text/javascript">
            jQuery(document).ready( function($) {
                    $(document).on('click', '.zgfm-notice-1-dismiss-opt, .zgfm-f-notice-message button', function( event ) {
                            
                             if ( ! $(this).hasClass('zgfm-notice-1-rate-trigger') ) {
						event.preventDefault();
					}
                             
                             
                            $.post( ajaxurl, {
                                    action: 'zgfm_f_notice_dismiss'
                            });
                            $('.zgfm-f-notice-message').remove();
                    });
            });
            
            jQuery(document).ready( function($) {
                    $(document).on('click', '.zgfm-notice-1-opt-rated', function( event ) {
                            
                             event.preventDefault();
                             
                             
                            $.post( ajaxurl, {
                                    action: 'zgfm_f_notice_rated'
                            });
                            $('.zgfm-f-notice-message').remove();
                    });
            });
    </script>