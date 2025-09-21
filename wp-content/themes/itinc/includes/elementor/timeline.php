<?php
namespace Elementor; // Custom widgets must be defined in the Elementor namespace
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly (security measure)

/**
 * Widget Name: Projects Carousel
 */
class THSN_TimelineElement extends Widget_Base{

 	// The get_name() method is a simple one, you just need to return a widget name that will be used in the code.
	public function get_name() {
		return 'thsn_timeline_element';
	}

	// The get_title() method, which again, is a very simple one, you need to return the widget title that will be displayed as the widget label.
	public function get_title() {
		return esc_attr__( 'ITinc Timeline Element', 'itinc' );
	}

	// The get_icon() method, is an optional but recommended method, it lets you set the widget icon. you can use any of the eicon or font-awesome icons, simply return the class name as a string.
	public function get_icon() {
		return 'fas fa-th-large';
	}

	// The get_categories method, lets you set the category of the widget, return the category name as a string.
	public function get_categories() {
		return [ 'itinc_category' ];
	}

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		wp_enqueue_script( 'owl-carousel' );
		wp_enqueue_style( 'owl-carousel' );
		wp_enqueue_style( 'owl-carousel-theme' );
	}

	protected function register_controls() {

		// Heading and Subheading
		$this->start_controls_section(
			'heading_section',
			[
				'label' => esc_attr__( 'Heading and Subheading', 'itinc' ),
			]
		);
		$this->add_control(
			'title',
			[
				'label' => esc_attr__( 'Title', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( 'Welcome to our site', 'itinc' ),
				'placeholder' => esc_attr__( 'Enter your title', 'itinc' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'title_link',
			[
				'label' => esc_attr__( 'Title Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$this->add_control(
			'subtitle',
			[
				'label' => esc_attr__( 'Subtitle', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_attr__( 'This is Subtitle', 'itinc' ),
				'placeholder' => esc_attr__( 'Enter your subtitle', 'itinc' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'subtitle_link',
			[
				'label' => esc_attr__( 'Subtitle Link', 'itinc' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);
		$this->add_control(
			'desc',
			[
				'label' => esc_attr__( 'Description', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_attr__( 'Type your description here', 'itinc' ),
			]
		);
		$this->add_control(
			'reverse_title',
			[
				'label' => esc_attr__( 'Reverse Title', 'itinc' ),
				'description' => esc_attr__( 'Show sub-title before title', 'itinc' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_attr__( 'Yes', 'itinc' ),
				'label_off' => esc_attr__( 'No', 'itinc' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label' => esc_attr__( 'Alignment', 'itinc' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_attr__( 'Left', 'itinc' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_attr__( 'Center', 'itinc' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_attr__( 'Right', 'itinc' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .thsn-ele-header-area' => 'text-align: {{VALUE}};',
				],
				'dynamic' => [
					'active' => true,
				],
				'default' => 'left',
			]
		);

		// Tags
		$this->add_control(
			'tag_options',
			[
				'label'			=> esc_attr__( 'Tags for SEO', 'itinc' ),
				'type'			=> Controls_Manager::HEADING,
				'separator'		=> 'before',
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label' => esc_attr__( 'Title Tag', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1'	=> esc_attr( 'H1' ),
					'h2'	=> esc_attr( 'H2' ),
					'h3'	=> esc_attr( 'H3' ),
					'h4'	=> esc_attr( 'H4' ),
					'h5'	=> esc_attr( 'H5' ),
					'h6'	=> esc_attr( 'H6' ),
					'div'	=> esc_attr( 'DIV' ),
				],
				'default' => esc_attr( 'h2' ),
			]
		);
		$this->add_control(
			'subtitle_tag',
			[
				'label' => esc_attr__( 'SubTitle Tag', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1'	=> esc_attr( 'H1' ),
					'h2'	=> esc_attr( 'H2' ),
					'h3'	=> esc_attr( 'H3' ),
					'h4'	=> esc_attr( 'H4' ),
					'h5'	=> esc_attr( 'H5' ),
					'h6'	=> esc_attr( 'H6' ),
					'div'	=> esc_attr( 'DIV' ),
				],
				'default' => esc_attr( 'h4' ),
			]
		);
		$this->end_controls_section();

		//Content
		$this->start_controls_section(
			'data_section',
			[
				'label' => esc_attr__( 'Content Options', 'itinc' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'itinc' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_control(
			'small_text',
			[
				'label' => __( 'Small Text', 'itinc' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Small text like year.', 'itinc' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'title_text',
			[
				'label' => __( 'Title', 'itinc' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Title Text.', 'itinc' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'desc_text',
			[
				'label' => __( 'Description', 'itinc' ),
				'type' => Controls_Manager::TEXTAREA,
				'description' => __( 'Description Text.', 'itinc' ),
				'show_label' => true,
			]
		);
        $this->add_control(
			'values',
			[
				'label'			=> esc_attr__( 'Values', 'itinc' ),
				'description'	=> esc_attr__( 'Enter values for graph - value, title and color.', 'itinc' ),
				'type'			=> Controls_Manager::REPEATER,
				'fields'		=> $repeater->get_controls(),
				'default'		=> [
					[
						'image'			=> get_template_directory_uri() . '/images/placeholder.png',
						'small_text'	=> esc_attr__( '2010', 'itinc' ),
						'title_text'	=> esc_attr__( 'Our new branch', 'itinc' ),
						'desc_text'		=> esc_attr__( 'Our 1st branch in USA', 'itinc' ),
					],
					[
						'image'			=> get_template_directory_uri() . '/images/placeholder.png',
						'small_text'	=> esc_attr__( '2012', 'itinc' ),
						'title_text'	=> esc_attr__( 'Our new branch', 'itinc' ),
						'desc_text'		=> esc_attr__( 'Our 5th branch in USA', 'itinc' ),
					],
					[
						'image'			=> get_template_directory_uri() . '/images/placeholder.png',
						'small_text'	=> esc_attr__( '2014', 'itinc' ),
						'title_text'	=> esc_attr__( 'Our new branch', 'itinc' ),
						'desc_text'		=> esc_attr__( 'Our 7th branch in USA', 'itinc' ),
					],
				],
				'title_field' => '{{{ small_text }}}',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings	= $this->get_settings_for_display();
		extract($settings);

		?>

		<div class="thsn-timeline">

			<div class="thsn-ele-header-area">
				<?php thsn_heading_subheading($settings, true); ?>
			</div>

			<?php if( !empty($settings['values']) && count($settings['values'])>0 ) {
				foreach($settings['values'] as $value){
					$small_text	= ( !empty($value['small_text']) ) ? $value['small_text'] : '' ;
					$title_text	= ( !empty($value['title_text']) ) ? $value['title_text'] : '' ;
					$desc_text	= ( !empty($value['desc_text']) ) ? $value['desc_text'] : '' ;
					$image		= ( !empty($value['image']['url']) ) ? '<img src="'.esc_url($value['image']['url']).'" alt="'.esc_attr($title_text).'" />' : '' ;
					?>

					<div class="thsn-timeline-inner">
						<div class=" col-sm-12 thsn-ourhistory thsn-ourhistory-type2 ">
							<div class="row thsn-ourhistory-row">

								<div class="col-md-2 thsn-ourhistory-left">
									<span class="label"><?php echo esc_html($small_text); ?></span>
								</div>
								<div class="col-md-10 col-sm-10 col-xs-10 thsn-ourhistory-right">
									<span class="thsn-timeline-image"><?php echo thsn_esc_kses($image); ?></span>
									<span class="label"><?php echo esc_html($small_text); ?></span>
									<div class="content">
										<h4><?php echo esc_html($title_text); ?></h4>
										<div class="simple-text">
											<p><?php echo thsn_esc_kses($desc_text); ?></p>
										</div>

										<?php echo thsn_esc_kses($image); ?>

									</div>
								</div>
							</div> 
						</div>
					</div>

				<?php
				}
			}
			?>

		</div>

	    <?php
	}

	protected function content_template() {}

	protected function select_category() {
		$category = get_terms( array( 'taxonomy' => 'thsn-timeline-category', 'hide_empty' => false ) );
	  	$cat = array();
	  	foreach( $category as $item ) {
			$cat_count = get_category( $item );

	     	if( $item ) {
	        	$cat[$item->slug] = $item->name . ' ('.$cat_count->count.')';
	     	}
	  	}
	  	return $cat;
	}
}
// After the Schedule class is defined, I must register the new widget class with Elementor:
Plugin::instance()->widgets_manager->register( new THSN_TimelineElement() );