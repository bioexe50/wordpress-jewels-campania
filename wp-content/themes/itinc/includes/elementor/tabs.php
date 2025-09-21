<?php
namespace Elementor; // Custom widgets must be defined in the Elementor namespace
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly (security measure)

/**
 * Widget Name: Section Heading 
 */
class THSN_TabsElement extends Widget_Base{

 	/**
	 * Get widget name.
	 *
	 * Retrieve tabs widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'thsn_tabs_element';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve tabs widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'ITinc Tabs Element', 'itinc' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve tabs widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fas fa-box';
	}

	// The get_categories method, lets you set the category of the widget, return the category name as a string.
	public function get_categories() {
		return [ 'itinc_category' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'tabs', 'accordion', 'toggle' ];
	}

	protected function register_controls() {

		//Content Service box
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Tabs', 'itinc' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_icon',
			[
				'label' => __( 'Icon', 'itinc' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
					'url' => '',
				],
			]
		);

		$repeater->add_control(
			'tab_title',
			[
				'label' => __( 'Title & Description', 'itinc' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Tab Title', 'itinc' ),
				'placeholder' => __( 'Tab Title', 'itinc' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label' => __( 'Content', 'itinc' ),
				'default' => __( 'Tab Content', 'itinc' ),
				'placeholder' => __( 'Tab Content', 'itinc' ),
				'type' => Controls_Manager::WYSIWYG,
				'show_label' => false,
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => __( 'Tabs Items', 'itinc' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'tab_title' => __( 'Tab #1', 'itinc' ),
						'tab_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
						'tab_content' => __( 'We help ambitious businesses like yours generate more profits by building awareness, driving web traffic, connecting with customers, and growing overall sales. Give us a call.', 'itinc' ),
					],
					[
						'tab_title' => __( 'Tab #2', 'itinc' ),
						'tab_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
						'tab_content' => __( 'We help ambitious businesses like yours generate more profits by building awareness, driving web traffic, connecting with customers, and growing overall sales. Give us a call.', 'itinc' ),
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

		<div class="thsn-tabs">
			<?php if ( $settings['tabs'] ) : ?>
			<ul class="thsn-tabs-heading">
				<?php $i = 1; foreach ( $settings['tabs'] as $tabs ) { ?>
					<?php $active_li_class = ( $i==1 ) ? 'thsn-tab-li-active' : '' ; ?>

					<?php
					// icon
					$icon_html = '';
					if( !empty($tabs['tab_icon']['value']) ){
						if($tabs['tab_icon']['library']=='svg'){
							ob_start();
							Icons_Manager::render_icon( $tabs['tab_icon'] , [ 'aria-hidden' => 'true' ] );
							$icon_html = ob_get_contents();
							ob_end_clean();
							$icon_html       = '<div class="thsn-tab-svg"><div class="thsn-tab-svg-wrapper">' . $icon_html . '</div></div>';
							$icon_type_class = 'tab_icon';
						} else {
							ob_start();
							Icons_Manager::render_icon( $tabs['tab_icon'] , [ 'aria-hidden' => 'true' ] );
							$icon_code = ob_get_contents();
							ob_end_clean();
							
							$icon_html = '<div class="thsn-tab-icon">' . thsn_esc_kses( $icon_code ) . '</div>';
							wp_enqueue_style( 'elementor-icons-'.$tabs['tab_icon']['library']);
						}
					}
					?>

				<li class="thsn-tab-link <?php echo esc_attr($active_li_class); ?>" data-thsn-tab="<?php echo esc_attr($i); ?>"><?php echo thsn_esc_kses($icon_html); ?><span><?php echo esc_html($tabs['tab_title']); ?></span></li>
				<?php $i++; } ?>
			</ul>

			<div class="thsn-tab-content-wrapper">
				<?php $j = 1; foreach ( $settings['tabs'] as $tabs ) { ?>
					<?php $active_class = ( $j==1 ) ? 'thsn-tab-active' : '' ; ?>
					<div class="thsn-tab-content thsn-tab-content-<?php echo esc_attr($j); ?> <?php echo esc_attr($active_class); ?>">
						<?php
						$icon_html = ''; // icon
						if( !empty($tabs['tab_icon']['value']) ){
							if($tabs['tab_icon']['library']=='svg'){
								ob_start();
								Icons_Manager::render_icon( $tabs['tab_icon'] , [ 'aria-hidden' => 'true' ] );
								$icon_html = ob_get_contents();
								ob_end_clean();
								$icon_html       = '<div class="thsn-tab-svg"><div class="thsn-tab-svg-wrapper">' . $icon_html . '</div></div>';
								$icon_type_class = 'tab_icon';
							} else {
								ob_start();
								Icons_Manager::render_icon( $tabs['tab_icon'] , [ 'aria-hidden' => 'true' ] );
								$icon_code = ob_get_contents();
								ob_end_clean();
								
								$icon_html = '<div class="thsn-tab-icon">' . thsn_esc_kses( $icon_code ) . '</div>';
								wp_enqueue_style( 'elementor-icons-'.$tabs['tab_icon']['library']);
							}
						}
						?>
						<div class="thsn-tab-content-title" data-thsn-tab="<?php echo esc_attr($j); ?>"><?php echo thsn_esc_kses($icon_html); ?><?php echo esc_html($tabs['tab_title']); ?></div>
						<div class="thsn-tab-content-inner">
							<?php echo thsn_esc_kses($tabs['tab_content']); ?>
						</div>
					</div>
				<?php $j++; } ?>
			</div>

			<?php endif; ?>
	    </div>

	    <?php
	}

	protected function content_template() {}
}
// After the Schedule class is defined, I must register the new widget class with Elementor:
Plugin::instance()->widgets_manager->register( new THSN_TabsElement() );