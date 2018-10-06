<?php
/**
 * The deactivate feedback model class for ThemeIsle SDK
 *
 * @package     ThemeIsleSDK
 * @subpackage  Feedback
 * @copyright   Copyright (c) 2017, Marius Cristea
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 * @since       1.0.0
 */ 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 
if ( ! class_exists( 'ThemeIsle_SDK_Feedback_Deactivate' ) ) :
	/**
	 * Deactivate feedback model for ThemeIsle SDK.
	 */

	class ThemeIsle_SDK_Feedback_Deactivate extends ThemeIsle_SDK_Feedback {

		/**
		 * @var array $options_plugin The main options list for plugins.
		 */
		private $options_plugin = array(
			'I found a better plugin'            => array(
				'id'          => 3,
				'type'        => 'text',
				'placeholder' => 'What\'s the plugin\'s name?',
			),
			'I could not get the plugin to work' => array(
				'id' => 4,
                                'type'        => 'text',
				'placeholder' => 'What happened?',
			),
			'I no longer need the plugin'        => array(
				'id'          => 5,
				'type'        => 'textarea',
				'placeholder' => 'If you could improve one thing about our product, what would it be?',
			),
			'It\'s a temporary deactivation. I\'m just debugging an issue.' => array(
				'id' => 6,
			),
		);

		/**
		 * @var array $options_theme The main options list for themes.
		 */
		private $options_theme = array(
			'I don\'t know how to make it look like demo' => array(
				'id' => 7,
			),
			'It lacks options'                            => array(
				'id' => 8,
			),
			'Is not working with a plugin that I need'    => array(
				'id'          => 9,
				'type'        => 'text',
				'placeholder' => 'What is the name of the plugin',
			),
			'I want to try a new design, I don\'t like {theme} style' => array(
				'id' => 10,
			),
		);

		/**
		 * @var array $other The other option
		 */
		private $other = array(
			'Other' => array(
				'id'          => 999,
				'type'        => 'textarea',
				'placeholder' => 'cmon cmon tell us',
			),
		);

		/**
		 * @var string $heading_plugin The heading of the modal
		 */
		private $heading_plugin = 'Quick Feedback <span>Because we care about our clients, please leave us a feedback.</span>';

		/**
		 * @var string $heading_theme The heading of the modal
		 */
		private $heading_theme = 'Looking to change {theme} <span> What does not work for you?</span>';

		/**
		 * @var string $button_submit_before The text of the deactivate button before an option is chosen
		 */
		private $button_submit_before = 'Skip &amp; Deactivate';

		/**
		 * @var string $button_submit The text of the deactivate button
		 */
		private $button_submit = 'Submit &amp; Deactivate';

		/**
		 * @var string $button_cancel The text of the cancel button
		 */
		private $button_cancel = 'Skip &amp; Deactivate';

		/**
		 * @var int how many seconds before the deactivation window is triggered for themes
		 */
		const AUTO_TRIGGER_DEACTIVATE_WINDOW_SECONDS = 3;

		/**
		 * @var int how many days before the deactivation window pops up again for the theme
		 */
		const PAUSE_DEACTIVATE_WINDOW_DAYS = 100;

		/**
		 * ThemeIsle_SDK_Feedback_Deactivate constructor.
		 *
		 * @param ThemeIsle_SDK_Product $product_object The product object.
		 */
		public function __construct( $product_object ) {
			parent::__construct( $product_object );
		}

		/**
		 * Registers the hooks
		 */
		public function setup_hooks_child() {
			global $pagenow;
                                                
			if ( ( $this->product->get_type() === 'plugin' && $pagenow === 'plugins.php' ) || 
                                ( $this->product->get_type() === 'theme' && $pagenow === 'theme-install.php' ) ) {
                             
				add_action( 'admin_head', array( $this, 'load_resources' ) );
			}
			add_action( 'wp_ajax_' . $this->product->get_key() . __CLASS__, array( $this, 'post_deactivate' ) );
		}

		/**
		 * Loads the additional resources
		 */
		function load_resources() {
			add_thickbox();

			$id = $this->product->get_key() . '_deactivate';

			$this->add_css( $this->product->get_type(), $this->product->get_key() );
			$this->add_js( $this->product->get_type(), $this->product->get_key(), '#TB_inline?' . apply_filters( $this->product->get_key() . '_feedback_deactivate_attributes', 'width=600&height=550' ) . '&inlineId=' . $id );

			echo '<div id="' . $id . '" style="display:none;" class="themeisle-deactivate-box">' . $this->get_html( $this->product->get_type(), $this->product->get_key() ) . '</div>';
		}

		/**
		 * Loads the css
		 *
		 * @param string $type The type of product.
		 * @param string $key The product key.
		 */
		function add_css( $type, $key ) {
			$suffix = 'theme' === $type ? 'theme-install-php' : 'plugins-php';
			?>
			<style type="text/css" id="<?php echo $key; ?>ti-deactivate-css">
				input[name="ti-deactivate-option"] ~ div {
					display: none;
				}

				input[name="ti-deactivate-option"]:checked ~ div {
					display: block;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container #TB_window.thickbox-loading:before {
					background: none !important;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container #TB_title {
					background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAC9VJREFUeNrMmwtwVOUVx//7fiZu3hii2VSTMoCKVB1fQxIrUEQRfFGnPBJBLahDsLQqijElYLXQgg86VspGUavgILZFVGqA6EytRE0qVCCDbCAkkJBk2U2y72y/7+69d+9md7N3716sZ+bLbnJ3v72/+z/nfOc7e6PA92z5+11W8mAV/MnRXZ7R8n19vuI8g1WQUc4CVoh42z4y7GTsp8/JhbD/sIG3n6Zgy8iYnZNjtKpU6U0/Ua+032BU7iRPN66+zGD/4QBvP03Vq+VU1OvVyMzUpT3tdLMKVq1CqH4dAd/3/wMeAcpZVpYeGo0qbeDqLDW0irhuLxlcIRHUwoLWjDyk1apgsejThqWgFHgU28CCO1KZVykBdhL5+XU8WM6d5bBcdVIt6Od/veobd8X5A95+uoqFtcY7TJOUXMCFalHOR89jL4GukR94+2kb+Wkb7SV6vUa2hG9WphRtfyTQNvmAw7BVyV4ml7rUMlLPeVVioJVywep0KqS77kbFsLS5kkIrk8DWiIENA8unLpelqVlUJ3GN6VX84vR8PPbZZZiqrhMDXZP6shReY/eKWtsUCuTmGphHOSyHqLs4uxM3el7A+MaPEdybidBgWBv3ChX+VNwoZprKeGu1YpR19ji9wGJmlquyopapcmKNei1u+egTBAjoSFPf6sALMz6FZzgz2VR0fS4ZuU4ncmmbWFg53Xl65kdoarkN0x4/EBeWWui4DgWaQ2Kms8RbVZQJXHl2SvGmVaYNuz7r13hp42oY3h394lHXLlAfFDvt7JGFSeyZnnPa4PVBvLqqtGKXuvDbivm47YlmDNuThwV9jV7pTOUjbImBNx2sgs9nhZNM2NcPeLzkkoZGnS2dTQKFfQfzMfm5Dj4pMSdl9UIztw/qSqcckWIlKlclUngZ/ywYBFwuoLePPA4AgUACd5YO/LTnd7jkWWcULIX0/7IXJ10mnC0JQb/+BBSm4XSha2OBNx2kvj4pNmiIwh4P0O8ID4Hq1JXVamnxu9y0EbP+/FkULAUL3ulA89KJOLb5IhyqL0XbzkImM8ugcsVIhRcmfRtVWaC6VhmS9OnXmT7HQ9veQqg7uvamruzp0iMwEElcrjYTVBOGIhuUCW6c8U+Q8rELRwKLz8ys6oo+BwzOQWhSSHI0bp9vewbBL8zxE1KmH2pzJHyyJzsRPGSMep0ndIEUYIZPLXBnS6oz6FVKaN1eZoSIe/v1WgR0GgS0Gub3eLZIb0PBG+T1cRYI6t7qDzNw7est6PqgAPoxHoyZcA7ep4siXjDRjXbvdVKALdStOd+pkDKDThC/CqI6B0/NaPRgythPcSJYjJP+InT4ilCk7cDCz3cQsMRrLS04FIcMuKjSRYoMNTxbLo463lNWkk4s88DlkoBV8TP01VnNWP9tPUy7w66pKPFCmefHqbH5MO5WIlnk09j2v5Md91hH6ZWkVpBG6xxGOQc8KV2FORur78S6L9dC/6EGQbBJiShGLY+ghiC9KlNdM4DWoXuSvs4bCo+h4RAGhyOPYYU3HbRIiV9dguVoXs5fYfgwrHxgWIFutwF9nnAFVZLpgkkTu57T7Cymyuq98QJ8OTSe+kAUGA9KwLxJ3EctVV1lgqRUdtbOwx7szcKgPxKvasEyds6nhWNMCD9+8hhA12KyBvteLEgIrsj3Y1vpUnQ4Q+nEMCRvc3Tq+PFb5v6Opix0DRqjYKmyOlUQ3UN6nCBVlDeowlV1B+F97kImZmnRoXusEx5BRhZa/x1G/NN5d9p1pmRgVQKFzf0e+AnwmaHY3nTzmRwGlFrW5HPIOBeCjy0+hBVXXHWvWEqyTvqFteQMEq+pOC7jCFFLzSjLgXE28m8/+vkp+G15URXU8PH47nzq/lxZ1E0LOJ5LZ6hdjHvS+ORdiFRNhTO7o16XP6UPGV1KXlXqzpq5vfBvy4l1QbKZeCFvjWy9MiVktHHmI0zS6WbdmcKan4suFZk4v78dgX9EFgbNPQSW/D6aW/8ggTM0LnicGj5ZqWuU2Kqez2wAOKNqq78y8HDUlRX5gZjamnN/WnmtProIJqVTNmC7XMA/cXzDq0vddsuFi5i4dh2NAJfM6uKrKMaV7+uB76WCqHnoXrjNEelpaTYZsMY/TybgpRNlA87wDvLZ+du5E3Gg/yq83zkL/ifMTFa+ZPFJqJtM0a5M4IWuzC1bMRXchj6Uat9GsyvAjNaBAL5zB/nR6R1GfyAE93BI1LLUIrUAEVrhsS6cCl6KvIUOrHM/Gc6wnkIsCfweN1R9gteKV8K9xBrtyjZzlBsLlR1ZX+eoTqLfH+l+dI928VUKGMigj2YysjVkJ6ZAi2zANEN3lGTD/OgAVhSsRGtvLgHwkhE+wS69ET5bbpQrC7d9TLdjqotsVXPYjUkwZi22e4tEn48rGGKG8KLolAoemN5EUiUFlAK5A8PoGtDh6oANw4oQe+mjT3hl6C0+MQ3O8WGP/3pMt7aRzb2BgfloXikmd0T6zRdoo5sKnbcb8ff+qWl5IKm193PBszOlbofbg95BL445PDjh9KJnyI8Nk+rgvvNarBr/l5i3TMn7Ctc3tvMbhSUFD+Pu1rW49adP4PCDZqy7bxreVNyMzl35vLr5ZD/N2dACL+4teh6OYGa6UbdTIWjivZewzUM7mF6ysaetHB975TMywoO7endFuhC6d/8V9fZXx6/FXasOM88/ftSKuV2/jfmIbRkrYXwmPHepxckAU+X/veRiPBCqR3sK7pzA9mFGVqVwHX4/BnJoKNywO9sbbtX6EveutrbPZB5fbJsbe6znZxia52HUfPDsb2KOz7fuQs4rrrAr6/wMLN37rls8HdM9DXLAUnuNyQVRf3rpm36ipIVpy/qSNOb0ZPnJDq+nFs0Aik1daHWUxn0pPUatffDC2EYTee/uzmXofT2L2T5OyutDcOEg7s1+HE09k4ExJIlp0/7uivlijSjsiK60eno2Mt86+HziYpm17dc/hkdK3+EBqGIc5KzCJhydcQcz6N9HGn3fuR3h0Cgr60Xrk1mYoNwShmUynFsOdTdS2Hil5Qb2aohIeV4+IdGx+r+LwjFavhSbr6rHFzcvYOC5C0FtQfEHMdNc6TjC9KHHTevClqXlmN7+Bzj8gjKTbQqmqe6G+LV03U0O5mqINRLnNCvT+KXueoWljRmc0pdbjkaUIiZ8zlkDbsWla07ivpt+hfr26jhtzGC60Ly6sTHMWW0j/TLcmmymKaXHsWfGCpTt3sHH5+ar6zG/eBcDN3X/y7wX0Avwt84p/PHFzU9hq32muFM2kH1ynkUKrJ3AlojZLVWLmW3V5TZeXU5VmrjoskRhaRyfuX0q/uMoY2CZjFy8K6F7JzSqcCAoBbha3Paw7qZ99Oeo6hYdwpSxh/jY5RKQMGapu1NYYUxSZan6K1prUjt111CqsHVE3X0x7aJR31LbSG9qqYh3aM+dT6Op8zKsPv4IuWxKRt0jt8zB/QdWMWpSdWlmpkpzsUtdO14ci+4pFebF7y0lKDKkNADmsBuLGHUvz7Xjxa9uCX+VyqordF2qLoUTwu4pf4hfrlI2uu0Tp3ILe94SOh7hrF05ErrpzebKBW/dbXd4TQwwhXi49G3evenvNFaFbivM5pLNNZgslluY8xVk5dRcOuLaNEXuZbeQDWQsh+C2ps23bUax+QyflWkmZuL1wFNR6gqzuWQzGYCcTEmw4nta0UrTZFaDyNcz9t2r361+oOlhBwdH1RUmM1nU5fu9bsS56UYUrHiFYxXvFwBXk12TnQzmrr2ZPfUN1kv0FS87V1hlV5czWluPyYlUhzOylp/vruUctvlnJ+o3wOWqZXZXJDvueuqNagIbqUAPNG64a8eSFtlgqfkCpGAccDDnkQKsdIUjcW1lld4Lo5E8s1Syf7Pxy0P44oi+jVGkMbf/48FxKd/tkv7doLWNFLCWrMVW1FZU4pXDwrL0SrapUCsTaAMLKrnTKu//Lb1ymGbx91jgmGwu0ezs5r0hHdDzAxwN7mCXsfcktWPCjcWdBFLWf9P7nwADAChI1HQj9RU9AAAAAElFTkSuQmCC') 40px 30px no-repeat;
					border: none;
					box-sizing: border-box;
					color: #373e40;
					font-size: 24px;
					font-weight: 700;
					height: 90px;
					padding: 40px 40px 0 120px;
					text-transform: uppercase;
					width: 100%;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container div.actions {
					box-sizing: border-box;
					padding: 30px 40px;
					background-color: #eaeaea;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button {
					background: #ec5d60;
					border: none;
					box-shadow: none;
					color: #ffffff;
					font-size: 15px;
					font-weight: 700;
					height: auto;
					line-height: 20px;
					padding: 10px 15px;
					text-transform: uppercase;
					-webkit-transition: 0.3s ease;
					-moz-transition: 0.3s ease;
					-ms-transition: 0.3s ease;
					-o-transition: 0.3s ease;
					transition: 0.3s ease;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button.button-primary {
					background: transparent;
					box-shadow: none;
					color: #8d9192;
					font-weight: 400;
					float: right;
					line-height: 40px;
					padding: 0;
					text-decoration: underline;
					text-shadow: none;
					text-transform: none;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button:hover {
					background: #e83f42;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button.button-primary:hover {
					background: transparent;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button:focus {
					box-shadow: none;
					outline: none;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button:active {
					box-shadow: none;
					transform: translateY(0);
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button:disabled {
					cursor: not-allowed;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container input.button.button-primary:hover {
					text-decoration: none;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container div.revive_network-container {
					background-color: #ffffff;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container ul.ti-list {
					margin: 0;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container ul.ti-list li {
					color: #373e40;
					font-size: 13px;
					margin-bottom: 5px;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container ul.ti-list li label {
					margin-left: 10px;
					line-height: 28px;
					font-size: 15px;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container ul.ti-list input[type=radio] {
					margin-top: 1px;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container #TB_ajaxContent {
					box-sizing: border-box;
					height: auto !important;
					padding: 20px 40px;
					width: 100% !important;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container li div textarea {
					padding: 10px 15px;
					width: 100%;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container ul.ti-list li div {
					margin: 10px 30px;
				}

				.<?php echo $key; ?>-container #TB_title #TB_ajaxWindowTitle {
					box-sizing: border-box;
					display: block;
					float: none;
					font-weight: 700;
					line-height: 1;
					padding: 0;
					text-align: left;
					width: 100%;
				}

				.<?php echo $key; ?>-container #TB_title #TB_ajaxWindowTitle span {
					color: #8d9192;
					display: block;
					font-size: 15px;
					font-weight: 400;
					margin-top: 5px;
					text-transform: none;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container .actions {
					width: 100%;
					display: block;
					position: absolute;
					left: 0;
					bottom: 0;
				}

				.theme-install-php .<?php echo $key; ?>-container #TB_closeWindowButton .tb-close-icon:before {
					font-size: 32px;
				}

				.<?php echo $key; ?>-container #TB_closeWindowButton .tb-close-icon {
					color: #eee;
				}

				.<?php echo $key; ?>-container #TB_closeWindowButton {
					left: auto;
					right: -5px;
					top: -35px;
					color: #eee;
				}

				.<?php echo $key; ?>-container #TB_closeWindowButton .tb-close-icon {
					text-align: right;
					line-height: 25px;
					width: 25px;
					height: 25px;
				}

				.<?php echo $key; ?>-container #TB_closeWindowButton:focus .tb-close-icon {
					box-shadow: none;
					outline: none;
				}

				.<?php echo $key; ?>-container #TB_closeWindowButton .tb-close-icon:before {
					font: normal 25px dashicons;
				}

				body.<?php echo $suffix; ?> .<?php echo $key; ?>-container {
					margin: auto !important;
					height: 530px !important;
					top: 0 !important;
					left: 0 !important;
					bottom: 0 !important;
					right: 0 !important;
					width: 600px !important;
				}
                                
                                #sfdc_wpfeedback_inp_email_wrap{
                                    padding-bottom: 20px;
                                    padding-top: 10px;
                                }
                                
                                #sfdc_wpfeedback_inp_email_wrap span{
                                    color:#ffa293;
                                }
                                #sfdc_wpfeedback_inp_email_wrap input{
                                    margin-left:10px;
                                    background-color: #fff;
                                    background-image: none;
                                    border: 1px solid #ccc;
                                    border-radius: 4px;
                                    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
                                    color: #555;
                                    
                                    font-size: 14px;
                                    height: 34px;
                                    line-height: 1.42857;
                                    padding: 6px 12px;
                                    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
                                    width: 326px;
                                    
                                }
                                #sfdc_wpfeedback_inp_email_wrap label{
                                    font-size: 15px;
                                    line-height: 0;
                                    vertical-align: baseline;
                                }
                                
                                #sfdc-thickbox-loader-wrap{
                                      position: absolute;
                                      background:white;
                                      top:0;
                                      left:0;
                                      width:100%;
                                      height:100%;
                                      display:block;
                                       /* IE 8 */
                                        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=70)";

                                        /* IE 5-7 */
                                        filter: alpha(opacity=70);

                                        /* Netscape */
                                        -moz-opacity: 0.7;

                                        /* Safari 1.x */
                                        -khtml-opacity: 0.7;

                                        /* Good browsers */
                                        opacity: 0.7;
                                      
                                }
                                .sfdc-thickbox-loading{
                                    display: block;
                                    position: absolute;
                                    left: 50%;
                                    top: 50%;
                                    margin: -25px 0 0 -46px;
                                    width: 80px;
                                    padding: 10px;
                                     
                                    box-shadow: inset 0 1px 0 rgba(255,255,255,0.3),
                                                0 1px 3px rgba(0,0,0,0.3);
                                    border: 1px solid #888;
                                    border-radius: 5px;
                                    text-align: center;
                                    opacity: 0.8;
                                    z-index: 1000;
                                    
                                    background: linear-gradient(to bottom, #555, #333);
                                    border-color: #444;
                                    border-bottom-color: #111;
                                }
                                .sfdc-thickbox-loading div{
                                     position: relative;
                                    top: 2px;
                                    text-transform: uppercase;
                                    font: 10px/1.21 'Helvetica Neue', helvetica, arial, sans-serif;
                                    letter-spacing: 0.04em;
                                    color: #888;
                                }
                                
                                .sfdc-thickbox-spinner{
                                   display: inline-block;
                                    width: 14px;
                                    height: 14px;
                                    border: 1px solid rgba(0,0,0,0.8);
                                     
                                    border-radius: 50%;
                                    -webkit-animation: rotate 600ms linear infinite;
                                    -moz-animation: rotate 600ms linear infinite;
                                    -ms-animation: rotate 600ms linear infinite;
                                    animation: rotate 600ms linear infinite;
                                    
                                    border-color: rgba(255,255,255,0.8);
        border-left-color: rgba(255,255,255,0.6);
        border-bottom-color: rgba(255,255,255,0.4);
        border-right-color: rgba(255,255,255,0.2);
                                }
                                
                                    @-webkit-keyframes rotate {
                                        0%    { transform: rotate(0deg); }
                                        100%  { transform: rotate(360deg); }
                                    }
                                    @-moz-keyframes rotate {
                                        0%    { transform: rotate(0deg); }
                                        100%  { transform: rotate(360deg); }
                                    }
                                    @keyframes rotate {
                                        0%    { transform: rotate(0deg); }
                                        100%  { transform: rotate(360deg); }
                                    }  
			</style>
			<?php
		}

		/**
		 * Loads the js
		 *
		 * @param string $type The type of product.
		 * @param string $key The product key.
		 * @param string $src The url that will hijack the deactivate button url.
		 */
		function add_js( $type, $key, $src ) {
			$heading = 'plugin' === $type ? $this->heading_plugin : str_replace( '{theme}', $this->product->get_name(), $this->heading_theme );
			$heading = apply_filters( $this->product->get_key() . '_feedback_deactivate_heading', $heading );
			?>
			<script type="text/javascript" id="ti-deactivate-js">
				(function ($) {
					$(document).ready(function () {
						var auto_trigger = false;
						var target_element = 'tr[data-plugin^="<?php echo $this->product->get_slug(); ?>/"] span.deactivate a';
						<?php
						if ( 'theme' === $type ) {
						?>
						auto_trigger = true;
						if ($('a.ti-auto-anchor').length == 0) {
							$('body').append($('<a class="ti-auto-anchor" href=""></a>'));
						}
						target_element = 'a.ti-auto-anchor';
						<?php
						}
						?>

						if (auto_trigger) {
							setTimeout(function () {
								$('a.ti-auto-anchor').trigger('click');
							}, <?php echo self::AUTO_TRIGGER_DEACTIVATE_WINDOW_SECONDS * 1000; ?> );
						}
						$(document).on('thickbox:removed', function () {
							$.ajax({
								url: ajaxurl,
								method: 'post',
								data: {
									'action': '<?php echo $key . __CLASS__; ?>',
									'nonce': '<?php echo wp_create_nonce( (string) __CLASS__ ); ?>',
									'type': '<?php echo $type; ?>',
									'key': '<?php echo $key; ?>'
								},
							});
						});
						var href = $(target_element).attr('href');
						$('#<?php echo $key; ?>ti-deactivate-no').attr('data-ti-action', href).on('click', function (e) {
							e.preventDefault();
							e.stopPropagation();

							$('body').unbind('thickbox:removed');
							tb_remove();
							var redirect = $(this).attr('data-ti-action');
							if (redirect != '') {
								location.href = redirect;
							}
						});

						$('#<?php echo $key; ?> ul.ti-list label, #<?php echo $key; ?> ul.ti-list input[name="ti-deactivate-option"]').on('click', function (e) {
                                                    
                                                    $('#sfdc_wpfeedback_inp_email_wrap').show();
                                                    
							$('#<?php echo $key; ?>ti-deactivate-yes').val($('#<?php echo $key; ?>ti-deactivate-yes').attr('data-after-text'));

							var radio = $(this).prop('tagName') === 'LABEL' ? $(this).parent() : $(this);
							if (radio.parent().find('textarea').length > 0 && radio.parent().find('textarea').val().length === 0) {
								$('#<?php echo $key; ?>ti-deactivate-yes').attr('disabled', 'disabled');
								radio.parent().find('textarea').on('keyup', function (ee) {
									if ($(this).val().length === 0) {
										$('#<?php echo $key; ?>ti-deactivate-yes').attr('disabled', 'disabled');
									} else {
										$('#<?php echo $key; ?>ti-deactivate-yes').removeAttr('disabled');
									}
								});
							} else {
								$('#<?php echo $key; ?>ti-deactivate-yes').removeAttr('disabled');
							}
						});

						$('#<?php echo $key; ?>ti-deactivate-yes').attr('data-ti-action', href).on('click', function (e) {
							e.preventDefault();
							e.stopPropagation();
                                                        
                                                     var tmp_this=$(this);
                                                        
                                                        
                                                        $("#sfdc-thickbox-loader-wrap").show();
                                                        
							$.ajax({
								url: ajaxurl,
								method: 'post',
                                                                async : false,
								data: {
									'action': '<?php echo $key . __CLASS__; ?>',
									'nonce': '<?php echo wp_create_nonce( (string) __CLASS__ ); ?>',
									'id': $('#<?php echo $key; ?> input[name="ti-deactivate-option"]:checked').parent().attr('ti-option-id'),
									'msg': $('#<?php echo $key; ?> input[name="ti-deactivate-option"]:checked').parent().find('textarea').val(),
									'type': '<?php echo $type; ?>',
									'key': '<?php echo $key; ?>',
                                                                        'email':$('#sfdc_wpfeedback_inp_email').val()
								},
                                                                success: function (dataCheck) {
                                                                    
                                                                    $("#sfdc-thickbox-loader-wrap").hide();
                                                                    
                                                                    var thickbox_shown = ($('#TB_window').is(':visible')) ? true : false;
                                                                    if(thickbox_shown){
                                                                      $('body').unbind('thickbox:removed');
                                                                                              tb_remove();
                                                                    }
                                                                    
                                                                    var redirect = tmp_this.attr('data-ti-action');
                                                                    if (redirect != '') {
                                                                            location.href = redirect;
                                                                    } else {
                                                                            $('body').unbind('thickbox:removed');
                                                                            tb_remove();
                                                                    }  
                                                  
                                                                    
                                                                },
                                                                error: function (jqXHR, exception) {
                                                                    $("#sfdc-thickbox-loader-wrap").hide();
                                                                    
                                                                    var thickbox_shown = ($('#TB_window').is(':visible')) ? true : false;
                                                                    if(thickbox_shown){
                                                                      $('body').unbind('thickbox:removed');
                                                                                              tb_remove();
                                                                    }
                                                                    
                                                                    var redirect = tmp_this.attr('data-ti-action');
                                                                    if (redirect != '') {
                                                                            location.href = redirect;
                                                                    } else {
                                                                            $('body').unbind('thickbox:removed');
                                                                            tb_remove();
                                                                    }  
                                                                }
							});
                                                  
                                                   
							
						});

						$(target_element).attr('name', '<?php echo wp_kses( $heading, array( 'span' => array() ) ); ?>').attr('href', '<?php echo $src; ?>').addClass('thickbox');
						var thicbox_timer;
						$(target_element).on('click', function () {
							tiBindThickbox();
						});

						function tiBindThickbox() {
							var thicbox_timer = setTimeout(function () {
								if ($("#<?php echo esc_html( $key ); ?>").is(":visible")) {
									$("body").trigger('thickbox:iframe:loaded');
									$("#TB_window").addClass("<?php echo $key; ?>-container");
									clearTimeout(thicbox_timer);
									$('body').unbind('thickbox:removed');
								} else {
									tiBindThickbox();
								}
							}, 100);
						}
					});
				})(jQuery);
			</script>
			<?php
		}

		/**
		 * Generates the HTML
		 *
		 * @param string $type The type of product.
		 * @param string $key The product key.
		 */
		function get_html( $type, $key ) {
			$options              = 'plugin' === $type ? $this->options_plugin : $this->options_theme;
			$button_submit_before = 'plugin' === $type ? $this->button_submit_before : 'Submit';
			$button_submit        = 'plugin' === $type ? $this->button_submit : 'Submit';
			$options              = $this->randomize_options( apply_filters( $this->product->get_key() . '_feedback_deactivate_options', $options ) );
			$button_submit_before = apply_filters( $this->product->get_key() . '_feedback_deactivate_button_submit_before', $button_submit_before );
			$button_submit        = apply_filters( $this->product->get_key() . '_feedback_deactivate_button_submit', $button_submit );
			$button_cancel        = apply_filters( $this->product->get_key() . '_feedback_deactivate_button_cancel', $this->button_cancel );

			$options += $this->other;

			$list = '';
			foreach ( $options as $title => $attributes ) {
				$id   = $attributes['id'];
				$list .= '<li ti-option-id="' . $id . '"><input type="radio" name="ti-deactivate-option" id="' . $key . $id . '"><label for="' . $key . $id . '">' . str_replace( '{theme}', $this->product->get_name(), $title ) . '</label>';
				if ( array_key_exists( 'type', $attributes ) ) {
					$list        .= '<div>';
					$placeholder = array_key_exists( 'placeholder', $attributes ) ? $attributes['placeholder'] : '';
					switch ( $attributes['type'] ) {
						case 'text':
							$list .= '<textarea style="width: 100%" rows="1" name="comments" placeholder="' . $placeholder . '"></textarea>';
							break;
						case 'textarea':
							$list .= '<textarea style="width: 100%" rows="2" name="comments" placeholder="' . $placeholder . '"></textarea>';
							break;
					}
					$list .= '</div>';
				}
				$list .= '</li>';
			}
                        
                            
                        
                       $part='<div id="' . $this->product->get_key() . '">'
				   . '<ul class="ti-list">' . $list . '</ul>';     
                       $part.='<div id="sfdc_wpfeedback_inp_email_wrap" style="display:none;">';
                        $part.='<div class="form-group">';
                        $part.='<label for="sfdc_wpfeedback_inp_email"> Email address</label>';
                        $part.='<input class="" placeholder="Here your email" id="sfdc_wpfeedback_inp_email" type="text"> <span>(optional)</span>';
                        $part.='</div>';  
                        $part.='</div>';  
                        
                        $loader='<div id="sfdc-thickbox-loader-wrap" style="display:none;">';
                        $loader.=' <div class="sfdc-thickbox-loading">';
                                $loader.='<span class="sfdc-thickbox-spinner"></span>';
                                $loader.='<div>loading</div>';
                        $loader.=' </div>';
                        $loader.='</div>';
                                                
                        
                        
			return $part  . '<div class="actions">'
				. get_submit_button(
					$button_submit, 'secondary', $this->product->get_key() . 'ti-deactivate-yes', false, array(
						'data-after-text' => $button_submit,
						'disabled'        => true,
					)
				)
				   . get_submit_button( $button_cancel, 'primary', $this->product->get_key() . 'ti-deactivate-no', false )
				   . '</div></div>'.$loader;
		}

		/**
		 * Called when the deactivate button is clicked
		 */
		function post_deactivate() {
			check_ajax_referer( (string) __CLASS__, 'nonce' );
                        
                        if(ZIGAFORM_F_LITE===1){
                            $tmp_prod_code='zgfm_wp_builder_lite';
                        }else{
                            $tmp_prod_code='zgfm_wp_builder_pro';
                        }
                        
                        
			if ( ! empty( $_POST['id'] ) ) {
				$this->call_api(
					array(
						'type'    => 'deactivate',
						'id'      => $_POST['id'],
                                                'email'      => $_POST['email'],
                                                'prod_code' => $tmp_prod_code,
                                                'version' => UIFORM_VERSION,
						'comment' => isset( $_POST['msg'] ) ? $_POST['msg'] : '',
					)
				);
			}

			$this->post_deactivate_or_cancel();
		}

		/**
		 * Called when the deactivate/cancel button is clicked
		 */
		private function post_deactivate_or_cancel() {
			if ( isset( $_POST['type'] ) && isset( $_POST['key'] ) && 'theme' === $_POST['type'] ) {
				set_transient( 'ti_sdk_pause_' . $_POST['key'], true, PAUSE_DEACTIVATE_WINDOW_DAYS * DAY_IN_SECONDS );
			}
		}
	}
endif;
