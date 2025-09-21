<?php
namespace Elementor; // Custom widgets must be defined in the Elementor namespace
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly (security measure)

/**
 * Widget Name: Projects Carousel
 */
class THSN_PortfolioElement extends Widget_Base{

 	// The get_name() method is a simple one, you just need to return a widget name that will be used in the code.
	public function get_name() {
		return 'thsn_portfolio_element';
	}

	// The get_title() method, which again, is a very simple one, you need to return the widget title that will be displayed as the widget label.
	public function get_title() {
		return esc_attr__( 'ITinc Portfolio Element', 'itinc' );
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
				'default'		=> '',
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
				'prefix_class' => 'thsn-align-',
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
				'label' => esc_attr__( 'Data Options', 'itinc' ),
			]
		);
		$this->add_control(
			'offset',
			[
				'label'			=> esc_attr__( 'Skip Post (offset)', 'itinc' ),
				'description'	=> esc_attr__( 'How many Post you like to skip.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'label_block'	=> true,
				'default'		=> '',
				'options'		=> [
					''				=> esc_attr__( 'Show All Post (No skip)', 'itinc' ),
					'1'				=> esc_attr__( 'Skip first Post', 'itinc' ),
					'2'				=> esc_attr__( 'Skip two Posts', 'itinc' ),
					'3'				=> esc_attr__( 'Skip three Posts', 'itinc' ),
					'4'				=> esc_attr__( 'Skip four Posts', 'itinc' ),
					'5'				=> esc_attr__( 'Skip five Posts', 'itinc' ),
				],
				'condition'		=> [
					'pagination!'	=> 'yes',
				]
			]
		);
		$this->add_control(
			'from_category',
			[
				'label' => esc_attr__( 'Show Post from selected Portfolio Category?', 'itinc' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $this->select_category(),
				'multiple' => true,
				'label_block'	=> true,
				'placeholder' => esc_attr__( 'All Categories', 'itinc' ),
			]
		);
		$this->add_control(
			'show',
			[
				'label' => esc_attr__( 'Post to show', 'itinc' ),
				'description' => esc_attr__( 'How many Post you want to show.', 'itinc' ),
				'separator' => 'before',
				'type' => Controls_Manager::NUMBER,
				'default' => '3',
			]
		);
		$this->add_control(
			'sortable',
			[
				'label' => esc_attr__( 'Show Sortable Portfolio Category ?', 'itinc' ),
				'description' => esc_attr__( 'Select YES to show sortable Portfolio Category.', 'itinc' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_attr__( 'Yes', 'itinc' ),
				'label_off' => esc_attr__( 'No', 'itinc' ),
				'return_value' => 'yes',
				'default' => '',
				'condition'		=> [
					'view-type'		=> 'row-column',
					'pagination!'	=> 'yes',
				]
			]
		);
		$this->add_control(
			'pagination',
			[
				'label' => esc_attr__( 'Show Pagination ?', 'itinc' ),
				'description' => esc_attr__( 'Select YES to Show pagination links below Portfolio boxes.', 'itinc' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_attr__( 'Yes', 'itinc' ),
				'label_off' => esc_attr__( 'No', 'itinc' ),
				'return_value' => 'yes',
				'default' => '',
				'condition'		=> [
					'view-type'		=> 'row-column',
					'sortable!'     => 'yes',
				]
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_attr__( 'Order', 'itinc' ),
				'description' => esc_attr__( 'Designates the ascending or descending order.', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'default' => 'DESC',
				'options' => [
					'ASC'		=> esc_attr__( 'Ascending order (1, 2, 3; a, b, c)', 'itinc' ),
					'DESC'		=> esc_attr__( 'Descending order (3, 2, 1; c, b, a)', 'itinc' ),
				]
			]
		);
		$this->add_control(
			'orderby',
			[
				'label' => esc_attr__( 'Order By', 'itinc' ),
				'description' => esc_attr__( ' Sort retrieved posts by parameter.', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'none'		=> esc_attr__( 'No order', 'itinc' ),
					'ID'		=> esc_attr__( 'ID', 'itinc' ),
					'title'		=> esc_attr__( 'Title', 'itinc' ),
					'date'		=> esc_attr__( 'Date', 'itinc' ),
					'rand'		=> esc_attr__( 'Random', 'itinc' ),
				]
			]
		);
		$this->add_control(
			'gap',
			[
				'label' => esc_attr__( 'Box Gap', 'itinc' ),
				'description' => esc_attr__( 'Gap between each Post box.', 'itinc' ),
				'type' => Controls_Manager::SELECT,
				'default' => '15px',
				'options' => [
					'0px'		=> esc_attr__( 'No Gap (0px)', 'itinc' ),
					'5px'		=> esc_attr__( '5px', 'itinc' ),
					'10px'		=> esc_attr__( '10px', 'itinc' ),
					'15px'		=> esc_attr__( '15px', 'itinc' ),
					'20px'		=> esc_attr__( '20px', 'itinc' ),
					'25px'		=> esc_attr__( '25px', 'itinc' ),
					'30px'		=> esc_attr__( '30px', 'itinc' ),
					'35px'		=> esc_attr__( '35px', 'itinc' ),
					'40px'		=> esc_attr__( '40px', 'itinc' ),
					'45px'		=> esc_attr__( '45px', 'itinc' ),
					'50px'		=> esc_attr__( '50px', 'itinc' ),
				]
			]
		);
		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_attr__( 'Select Style', 'itinc' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'style',
			[
				'label'			=> esc_attr__( 'Select Portfolio View Style', 'itinc' ),
				'description'	=> esc_attr__( 'Slect Portfolio View style.', 'itinc' ),
				'type'			=> 'thsn_imgselect',
				'label_block'	=> true,
				'thumb_width'	=> '110px',
				'default'		=> '1',
				'options'		=> thsn_element_template_list( 'portfolio', true ),
			]
		);
		$this->end_controls_section();

		// Appearance
		$this->start_controls_section(
			'appearance_section',
			[
				'label' => esc_attr__( 'Column and Carousel Options', 'itinc' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'view-type',
			[
				'label'			=> esc_attr__( 'How you like to view each Post box?', 'itinc' ),
				'description'	=> esc_attr__( 'Show as carousel view or simple row-column view.', 'itinc' ),
				'type'			=> 'thsn_imgselect',
				'label_block'	=> true,
				'thumb_width'	=> '110px',
				'default'		=> 'row-column',
				'options'		=> [
					'row-column'	=> esc_url( get_template_directory_uri() . '/includes/images/row-column.png' ),
					'carousel'		=> esc_url( get_template_directory_uri() . '/includes/images/carousel.png' ),
				]
			]
		);

		// Row Column: Heading
		$this->add_control(
			'row_col_options',
			[
				'label' => esc_attr__( 'Row-Column Options', 'itinc' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'view-type' => 'row-column',
				]
			]
		);

		// Carousel: Heading
		$this->add_control(
			'carousel_options',
			[
				'label' => esc_attr__( 'Carousel Options', 'itinc' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'view-type' => 'carousel',
				]
			]
		);
		// Carousel : Loop
		$this->add_control(
			'carousel-loop',
			[
				'label'			=> esc_attr__( 'Carousel: Loop', 'itinc' ),
				'description'	=> esc_attr__( 'Infinity loop. Duplicate last and first items to get loop illusion.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Autoplay
		$this->add_control(
			'carousel-autoplay',
			[
				'label'			=> esc_attr__( 'Carousel: Autoplay', 'itinc' ),
				'description'	=> esc_attr__( 'Autoplay of carousel.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Center
		$this->add_control(
			'carousel-center',
			[
				'label'			=> esc_attr__( 'Carousel: Center', 'itinc' ),
				'description'	=> esc_attr__( 'Center item. Works well with even an odd number of items.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Nav
		$this->add_control(
			'carousel-nav',
			[
				'label'			=> esc_attr__( 'Carousel: Nav', 'itinc' ),
				'description'	=> esc_attr__( 'Show next/prev buttons.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : Dots
		$this->add_control(
			'carousel-dots',
			[
				'label'			=> esc_attr__( 'Carousel: Dots', 'itinc' ),
				'description'	=> esc_attr__( 'Show dots navigation.', 'itinc' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> '0',
				'options'		=> [
					'1'				=> esc_attr__( 'Yes', 'itinc' ),
					'0'				=> esc_attr__( 'No', 'itinc' ),
				],
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);
		// Carousel : autoplaySpeed
		$this->add_control(
			'carousel-autoplayspeed',
			[
				'label'			=> esc_attr__( 'Carousel: autoplaySpeed', 'itinc' ),
				'description'	=> esc_attr__( 'autoplay speed.', 'itinc' ),
				'type'			=> Controls_Manager::TEXT,
				'default'		=> '1000',
				'condition'		=> [
					'view-type'		=> 'carousel',
				]
			]
		);

		// Columns
		$this->add_control(
			'columns',
			[
				'label'			=> esc_attr__( 'View in Column', 'itinc' ),
				'description'	=> esc_attr__( 'Select how many column to show.', 'itinc' ),
				'type'			=> 'thsn_imgselect',
				'label_block'	=> true,
				'thumb_width'	=> '110px',
				'default'		=> '3',
				'options'		=> [
					'1'				=> esc_url( get_template_directory_uri() . '/includes/images/column-1.png' ),
					'2'				=> esc_url( get_template_directory_uri() . '/includes/images/column-2.png' ),
					'3'				=> esc_url( get_template_directory_uri() . '/includes/images/column-3.png' ),
					'4'				=> esc_url( get_template_directory_uri() . '/includes/images/column-4.png' ),
					'5'				=> esc_url( get_template_directory_uri() . '/includes/images/column-5.png' ),
					'6'				=> esc_url( get_template_directory_uri() . '/includes/images/column-6.png' ),
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings	= $this->get_settings_for_display();
		extract($settings);
		?>

		<?php
		// Starting container
		$start_div = themesion_element_container( array(
			'position'	=> 'start',
			'cpt'		=> 'portfolio',
			'data'		=> $settings
		) );
		echo thsn_esc_kses($start_div);
		?>

		<?php
		// Preparing $args
		if(is_front_page()) {
			$paged = (get_query_var('page')) ? get_query_var('page') : 1;
		}else {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}
		$args = array(
			'post_type'				=> 'thsn-portfolio',
			'posts_per_page'		=> $show,
			'ignore_sticky_posts'	=> true,
			'paged' 				=> $paged,
		);
		if( !empty($offset) ){
			$args['offset'] = $offset;
		}
		if( !empty($orderby) && $orderby!='none' ){
			$args['orderby'] = $orderby;
			if( !empty($order) ){
				$args['order'] = $order;
			}
		}
		// From selected category/group
		if( !empty($from_category) ){
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'thsn-portfolio-category',
					'field'    => 'slug',
					'terms'    => $from_category,
				),
			);
		};

		// Wp query to fetch posts
		$posts = new \WP_Query( $args );

		if ( $posts->have_posts() ) {
			?>

			<div class="thsn-ele-header-area">
				<?php thsn_heading_subheading($settings, true); ?>

				<?php
				/* Sortable Category  */
				if( function_exists('thsn_sortable_category') ){
					$sortable_html = thsn_sortable_category( $settings, 'thsn-portfolio-category' );
					echo  thsn_esc_kses($sortable_html);
				}
				?>

			</div>

			<div class="thsn-element-posts-wrapper row multi-columns-row">

			<?php
			$odd_even = 'odd';
			while ( $posts->have_posts() ) {
				$return = '';
				$posts->the_post();

				// Template
				if( file_exists( locate_template( '/theme-parts/portfolio/portfolio-style-'.esc_attr($style).'.php', false, false ) ) ){

					$return .= thsn_element_block_container( array(
						'position'	=> 'start',
						'column'	=> $columns,
						'cpt'		=> 'portfolio',
						'taxonomy'	=> 'thsn-portfolio-category',
						'style'		=> $style,
					) );

					ob_start();
					$r = include( locate_template( '/theme-parts/portfolio/portfolio-style-'.esc_attr($style).'.php', false, false ) );
					$return .= ob_get_contents();
					ob_end_clean();

					$return .= thsn_element_block_container( array(
						'position'	=> 'end',
					) );

				}

				echo thsn_esc_kses($return);

				// odd or even
				if( $odd_even == 'odd' ){ $odd_even = 'even'; } else { $odd_even = 'odd'; }

			}
			?>

			</div>

			<?php
		}

		?>

		<?php wp_reset_postdata(); ?>

		<?php
		// Pagination
		if( isset($settings['pagination']) && $settings['pagination']=='yes' && $settings['view-type']!='carousel' ){
			$return .= thsn_pagination( $posts );
		}

		// Ending wrapper of the whole arear
		$end_div = themesion_element_container( array(
			'position'	=> 'end',
			'cpt'		=> 'portfolio',
			'data'		=> $settings
		) );
		echo thsn_esc_kses($end_div);
		?>

	    <?php
	}

	protected function content_template() {}

	protected function select_category() {
		$category = get_terms( array( 'taxonomy' => 'thsn-portfolio-category', 'hide_empty' => false ) );
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
Plugin::instance()->widgets_manager->register( new THSN_PortfolioElement() );