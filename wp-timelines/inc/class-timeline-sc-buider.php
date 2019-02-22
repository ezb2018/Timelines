<?php
class WPEX_TL_SCPosttype {
	public function __construct()
    {
        add_action( 'init', array( &$this, 'register_post_type' ) );
		add_filter( 'exc_mb_meta_boxes', array($this,'register_metadata') );
		add_action( 'save_post', array($this,'save_shortcode'),1 );
		add_shortcode( 'extlsc', array($this,'timeline_scbd') );
		add_filter( 'the_content', array($this,'preview_timeline'), 99 );
    }
	function preview_timeline($content){
		if ( is_singular('wptl_scbd') ){
			$sc = get_post_meta( get_the_ID(), '_tlsc', true );
			return do_shortcode($sc);
		}
		return $content;
	}

	function timeline_scbd($atts, $content){
		$id = isset($atts['id']) ? $atts['id'] : '';
		$sc = get_post_meta( $id, '_tlsc', true );
		if($id=='' || $sc==''){ return;}
		return do_shortcode($sc);
	}
	function sname($data,$key){
		if($key=='posttype' && isset($data[$key]['exc_mb-field-0'])){
			$pt = $data[$key]['exc_mb-field-0'];
			$pt = array_unique($pt);
			$pt = implode(",",$pt);
			return $pt;
		}
		if(!isset($data[$key]) || !isset($data[$key]['exc_mb-field-0'])){
			return;
		}
		return $data[$key]['exc_mb-field-0'];
	}
	function save_shortcode($post_id){
		if('wptl_scbd' != get_post_type()){ return;}
		if(isset($_POST['_tlsc_layout'])){
			$pt = $this->sname($_POST,'posttype');
			if($_POST['_tlsc_layout']['exc_mb-field-0'] == 'hoz'){
				$sc = '[wpex_timeline_horizontal style="'.$this->sname($_POST,'style').'" layout="'.$this->sname($_POST,'layout').'" posttype="'.$pt.'" cat="'.$this->sname($_POST,'cat').'" tag="'.$this->sname($_POST,'tag').'" taxonomy="'.$this->sname($_POST,'qr_cttaxo').'" ids="'.$this->sname($_POST,'ids').'" count="'.$this->sname($_POST,'count').'" order="'.$this->sname($_POST,'order').'" orderby="'.$this->sname($_POST,'orderby').'" meta_key="'.$this->sname($_POST,'meta_key').'" autoplay="'.$this->sname($_POST,'autoplay').'" show_media="'.$this->sname($_POST,'show_media').'" show_label="'.$this->sname($_POST,'show_label').'" full_content="'.$this->sname($_POST,'full_content').'" hide_thumb="'.$this->sname($_POST,'hide_thumb').'" arrow_position="'.$this->sname($_POST,'arrow_position').'" show_all="'.$this->sname($_POST,'show_all').'" header_align="'.$this->sname($_POST,'header_align').'" content_align="'.$this->sname($_POST,'content_align').'" toolbar_position="'.$this->sname($_POST,'toolbar_position').'" autoplayspeed="'.$this->sname($_POST,'autoplayspeed').'" start_on="'.$this->sname($_POST,'start_on').'" slidesshow="'.$this->sname($_POST,'slidesshow').'" loading_effect="'.$this->sname($_POST,'loading_effect').'" enable_back="'.$this->sname($_POST,'enable_back').'"]';
			}elseif($_POST['_tlsc_layout']['exc_mb-field-0'] == 'hoz-multi'){
				$sc = '[timeline_horizontal_multi style="'.$this->sname($_POST,'style').'" posttype="'.$pt.'" cat="'.$this->sname($_POST,'cat').'" tag="'.$this->sname($_POST,'tag').'" taxonomy="'.$this->sname($_POST,'qr_cttaxo').'" ids="'.$this->sname($_POST,'ids').'" count="'.$this->sname($_POST,'count').'" order="'.$this->sname($_POST,'order').'" orderby="'.$this->sname($_POST,'orderby').'" meta_key="'.$this->sname($_POST,'meta_key').'" autoplay="'.$this->sname($_POST,'autoplay').'" show_media="'.$this->sname($_POST,'show_media').'" show_label="'.$this->sname($_POST,'show_label').'" full_content="'.$this->sname($_POST,'full_content').'" hide_thumb="'.$this->sname($_POST,'hide_thumb').'" arrow_position="'.$this->sname($_POST,'arrow_position').'" autoplayspeed="'.$this->sname($_POST,'autoplayspeed').'" start_on="'.$this->sname($_POST,'start_on').'" slidesshow="'.$this->sname($_POST,'slidesshow').'" loading_effect="'.$this->sname($_POST,'loading_effect').'" enable_back="'.$this->sname($_POST,'enable_back').'"]';
			}else{
				$sc = '[wpex_timeline style="'.$this->sname($_POST,'style').'" posttype="'.$pt.'" cat="'.$this->sname($_POST,'cat').'" tag="'.$this->sname($_POST,'tag').'" taxonomy="'.$this->sname($_POST,'qr_cttaxo').'" ids="'.$this->sname($_POST,'ids').'" count="'.$this->sname($_POST,'count').'" posts_per_page="'.$this->sname($_POST,'posts_per_page').'" order="'.$this->sname($_POST,'order').'" orderby="'.$this->sname($_POST,'orderby').'" meta_key="'.$this->sname($_POST,'meta_key').'" alignment="'.$this->sname($_POST,'alignment').'" show_media="'.$this->sname($_POST,'show_media').'" show_history="'.$this->sname($_POST,'show_history').'" feature_label="'.$this->sname($_POST,'feature_label').'" full_content="'.$this->sname($_POST,'full_content').'" hide_thumb="'.$this->sname($_POST,'hide_thumb').'" hide_title="'.$this->sname($_POST,'hide_title').'"  img_size="'.$this->sname($_POST,'img_size').'" lightbox="'.$this->sname($_POST,'lightbox').'" page_navi="'.$this->sname($_POST,'page_navi').'" enable_back="'.$this->sname($_POST,'enable_back').'" start_label="'.$this->sname($_POST,'start_label').'" end_label="'.$this->sname($_POST,'end_label').'" animations="'.$this->sname($_POST,'animations').'" filter_cat="'.$this->sname($_POST,'filter_cat').'"]';
			}
			if($sc!=''){
				update_post_meta( $post_id, '_tlsc', $sc );
			}
			update_post_meta( $post_id, '_shortcode', '[extlsc id="'.$post_id.'"]' );
		}
	}
	function register_post_type(){
		$labels = array(
			'name'               => esc_html__('Shortcodes','wp-timeline'),
			'singular_name'      => esc_html__('Shortcodes','wp-timeline'),
			'add_new'            => esc_html__('Add New Shortcodes','wp-timeline'),
			'add_new_item'       => esc_html__('Add New Shortcodes','wp-timeline'),
			'edit_item'          => esc_html__('Edit Shortcodes','wp-timeline'),
			'new_item'           => esc_html__('New Shortcode','wp-timeline'),
			'all_items'          => esc_html__('Shortcodes builder','wp-timeline'),
			'view_item'          => esc_html__('View Shortcodes','wp-timeline'),
			'search_items'       => esc_html__('Search Shortcodes','wp-timeline'),
			'not_found'          => esc_html__('No Shortcode found','wp-timeline'),
			'not_found_in_trash' => esc_html__('No Shortcode found in Trash','wp-timeline'),
			'parent_item_colon'  => '',
			'menu_name'          => esc_html__('Shortcodes','wp-timeline')
		);
		
		$rewrite = false;
		$args = array(  
			'labels' => $labels,  
			'menu_position' => 8, 
			'supports' => array('title','custom-fields'),
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=wp-timeline',
			'menu_icon' =>  'dashicons-editor-ul',
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'rewrite' => $rewrite,
		);  
		register_post_type('wptl_scbd',$args);  
	}
	function register_metadata(array $meta_boxes){
		// register timeline meta
		$sc = array(
			array( 'id' => '_tlsc_layout', 'name' => esc_html__('Shortcode', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false ,
				'options' => array( 
					'list' => esc_html__('Vertival Listing', 'wp-timeline'), 
					'hoz' => esc_html__('Hozizontal', 'wp-timeline'),
					'hoz-multi' => esc_html__('Hozizontal Multi items', 'wp-timeline'),
				)
			),
			
		);
		if(isset($_GET['post']) && is_numeric($_GET['post'])){
			array_unshift($sc, array( 'id' => '_shortcode', 'name' => '', 'default'=> '' , 'type' => 'text','desc' => esc_html__('Copy this shortcode and paste it into your post, page, or text widget content:', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 12,'readonly' => true, )); 
		}
		$args = array(
		   'public'   => true,
		);
		$output = 'objects';
		$post_types = get_post_types( $args, $output );
		$listpt = array();
		foreach ( $post_types  as $post_type ) {
			if($post_type->name!='attachment' && $post_type->name!='elementor_library'){
				$listpt[$post_type->name] = $post_type->label;
			}
		}
		$vertical_inf = array(
			array( 'id' => 'style', 'name' => 'Style', 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4,
				'options' => array( 
					'' => esc_html__('Classic', 'wp-timeline'),
					'modern'=> esc_html__('Modern', 'wp-timeline'),
					'wide_img'=> esc_html__('Wide image', 'wp-timeline'),
					'bg'=> esc_html__('Background', 'wp-timeline'),
					'box-color'=> esc_html__('Box color', 'wp-timeline'),
					'simple'=> esc_html__('Simple', 'wp-timeline'),
					'simple-bod'=> esc_html__('Simple bod', 'wp-timeline'),
					'simple-cent'=> esc_html__('Simple cent', 'wp-timeline'),
					'clean'=> esc_html__('Clean', 'wp-timeline'),
				)
			),
			array( 'id' => 'alignment', 'name' => esc_html__('alignment', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4,
				'options' => array( 
					'' => esc_html__('Center', 'wp-timeline'),
					'left' => esc_html__('Left', 'wp-timeline'),
					'sidebyside' => esc_html__('Side by side', 'wp-timeline'),
				)
			),
			array( 'id' => 'posttype', 'name' => esc_html__('Post types', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'multiple' => true, 'default' => 'wp-timeline','cols' => 4,
				'options' => $listpt
			),
			array( 'id' => 'ids', 'name' => esc_html__('IDs', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Specify post IDs to retrieve', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 12, ),
			array( 'id' => 'count', 'name' => esc_html__('Count', 'wp-timeline'), 'default'=> '9', 'type' => 'text','desc' => esc_html__('Number of posts', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 6, ),
			array( 'id' => 'posts_per_page', 'name' => esc_html__('Posts per page', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Number item per page', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 6, ),
			
			array( 'id' => 'cat', 'name' => esc_html__('Category', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('List of cat ID (or slug), separated by a comma', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'tag', 'name' => esc_html__('Tags', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('List of tags, separated by a comma', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'qr_cttaxo', 'name' => esc_html__('Custom Taxonomy', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Name of custom taxonomy', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'order', 'name' => esc_html__('Order', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
					'DESC' => esc_html__('DESC', 'wp-timeline'),
					'ASC'=> esc_html__('ASC', 'wp-timeline')
				)
			),
			array( 'id' => 'orderby', 'name' => esc_html__('Order by', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
					'date' => esc_html__('Publish Date', 'wp-timeline'),
					'timeline_date' => esc_html__('Timeline Date', 'wp-timeline'),
					'ID' => esc_html__('ID', 'wp-timeline'),
					'author' => esc_html__('Author', 'wp-timeline'),
					'title' => esc_html__('Title', 'wp-timeline'),
					'name' => esc_html__('Name', 'wp-timeline'),
					'modified' => esc_html__('Modified', 'wp-timeline'),
					'parent' => esc_html__('Parent', 'wp-timeline'),
					'rand' => esc_html__('Random', 'wp-timeline'),
					'comment_count' => esc_html__('Comment count', 'wp-timeline'),
					'menu_order' => esc_html__('Menu order', 'wp-timeline'),
					'meta_value' => esc_html__('Meta value', 'wp-timeline'),
					'meta_value_num' => esc_html__('Meta value num', 'wp-timeline'),
					'post__in' => esc_html__('Post__in', 'wp-timeline'),
					'none' => esc_html__('None', 'wp-timeline'),
				)
			),
			array( 'id' => 'meta_key', 'name' => esc_html__('Meta key', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Enter meta key to query', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 12, ),
			array( 'id' => 'start_label', 'name' => esc_html__('Start label', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Enter text', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 6, ),
			array( 'id' => 'end_label', 'name' => esc_html__('End label', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Enter text', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 6, ),
			
			array( 'id' => 'show_media', 'name' => esc_html__('Show media', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Show Audio or video on timeline', 'wp-timeline'),
				'options' => array( 
					'1' => esc_html__('Yes', 'wp-timeline'),
					'0'=> esc_html__('No', 'wp-timeline')
				)
			),
			array( 'id' => 'show_history', 'name' => esc_html__('Show history bar', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Show label instead of date on timeline bar', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			
			array( 'id' => 'full_content', 'name' => esc_html__('Show full Content', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Show full Content instead of Excerpt', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'filter_cat', 'name' => esc_html__('Show Filter by category', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'feature_label', 'name' => esc_html__('Show Feature label', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'hide_title', 'name' => esc_html__('Hide timeline Title', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'hide_img', 'name' => esc_html__('Hide thubnails', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			
			array( 'id' => 'animations', 'name' => esc_html__('Animations', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => '',
				'options' => array( 
						'' => esc_html__('None', 'wp-timeline'),
						'bounce'=> esc_html__('bounce', 'wp-timeline'),
						'flash'=> esc_html__('flash', 'wp-timeline'),
						'pulse'=> esc_html__('pulse', 'wp-timeline'),
						'rubberBand'=> esc_html__('rubberBand', 'wp-timeline'),
						'shake'=> esc_html__('shake', 'wp-timeline'),
						'headShake'=> esc_html__('headShake', 'wp-timeline'),
						'swing'=> esc_html__('swing', 'wp-timeline'),
						'tada'=> esc_html__('tada', 'wp-timeline'),
						'wobble'=> esc_html__('wobble', 'wp-timeline'),
						'jello'=> esc_html__('jello', 'wp-timeline'),
						'bounceIn'=> esc_html__('bounceIn', 'wp-timeline'),
						'bounceInLeft'=> esc_html__('bounceInLeft', 'wp-timeline'),
						'bounceInRight'=> esc_html__('bounceInRight', 'wp-timeline'),
						'bounceInUp'=> esc_html__('bounceInUp', 'wp-timeline'),
						'fadeIn'=> esc_html__('fadeIn', 'wp-timeline'),
						'fadeInDown'=> esc_html__('fadeInDown', 'wp-timeline'),
						'fadeInDownBig'=> esc_html__('fadeInDownBig', 'wp-timeline'),
						'fadeInLeft'=> esc_html__('fadeInLeft', 'wp-timeline'),
						'fadeInLeftBig'=> esc_html__('fadeInLeftBig', 'wp-timeline'),
						'fadeInRight'=> esc_html__('fadeInRight', 'wp-timeline'),
						'fadeInRightBig'=> esc_html__('fadeInRightBig', 'wp-timeline'),
						'fadeInUp'=> esc_html__('fadeInUp', 'wp-timeline'),
						'fadeInUpBig'=> esc_html__('fadeInUpBig', 'wp-timeline'),
						'flipInX'=> esc_html__('flipInX', 'wp-timeline'),
						'flipInY'=> esc_html__('flipInY', 'wp-timeline'),
						'lightSpeedIn'=> esc_html__('lightSpeedIn', 'wp-timeline'),
						'rotateIn'=> esc_html__('rotateIn', 'wp-timeline'),
						'rotateInDownLeft'=> esc_html__('rotateInDownLeft', 'wp-timeline'),
						'rotateInDownRight'=> esc_html__('rotateInDownRight', 'wp-timeline'),
						'rotateInUpLeft'=> esc_html__('rotateInUpLeft', 'wp-timeline'),
						'rotateInUpRight'=> esc_html__('rotateInUpRight', 'wp-timeline'),
						'bounceInRight'=> esc_html__('bounceInRight', 'wp-timeline'),
						'rollIn'=> esc_html__('rollIn', 'wp-timeline'),
						'zoomIn'=> esc_html__('zoomIn', 'wp-timeline'),
						'zoomInDown'=> esc_html__('zoomInDown', 'wp-timeline'),
						'zoomInLeft'=> esc_html__('zoomInLeft', 'wp-timeline'),
						'zoomInRight'=> esc_html__('zoomInRight', 'wp-timeline'),
						'zoomInUp'=> esc_html__('zoomInUp', 'wp-timeline'),
						'slideIn'=> esc_html__('slideIn', 'wp-timeline'),
						'slideInDown'=> esc_html__('slideInDown', 'wp-timeline'),
						'slideInLeft'=> esc_html__('slideInLeft', 'wp-timeline'),
						'slideInRight'=> esc_html__('slideInRight', 'wp-timeline'),
						'bounceInRight'=> esc_html__('bounceInRight', 'wp-timeline'),
				)
			),
			array( 'id' => 'img_size', 'name' => esc_html__('Image size', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Enter custom image size', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 12, ),
			array( 'id' => 'lightbox', 'name' => esc_html__('Enable image lightbox', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'page_navi', 'name' => esc_html__('Page navigation', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('Load more', 'wp-timeline'),
						'inf'=> esc_html__('Infinite Scroll', 'wp-timeline'),
						'pag'=> esc_html__('Page links', 'wp-timeline')
				)
			),
			array( 'id' => 'enable_back', 'name' => esc_html__('Enable Back to timeline page', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Only work with timeline post type', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'yes'=> esc_html__('Yes', 'wp-timeline')
				)
			),
		);
		
		$hoz_inf = array(
			array( 'id' => 'style', 'name' => 'Style', 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4,
				'options' => array( 
						'' => esc_html__('Left side', 'wp-timeline'),
						'full-width'=> esc_html__('Full Width', 'wp-timeline'),
				)
			),
			array( 'id' => 'layout', 'name' => esc_html__('Layout', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4,
				'options' => array( 
						'horizontal' => esc_html__('Horizontal', 'wp-timeline'),
						'hozsteps' => esc_html__('Horizontal Step', 'wp-timeline'),
				)
			),
			array( 'id' => 'posttype', 'name' => esc_html__('Post types', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'multiple' => true, 'default' => 'wp-timeline','cols' => 4,
				'options' => $listpt
			),
			array( 'id' => 'ids', 'name' => esc_html__('IDs', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Specify post IDs to retrieve', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'count', 'name' => esc_html__('Count', 'wp-timeline'), 'default'=> '9', 'type' => 'text','desc' => esc_html__('Number of posts', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'slidesshow', 'name' => esc_html__('Number item visible', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Number item visible on timeline bar', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			
			array( 'id' => 'cat', 'name' => esc_html__('Category', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('List of cat ID (or slug), separated by a comma', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'tag', 'name' => esc_html__('Tags', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('List of tags, separated by a comma', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'qr_cttaxo', 'name' => esc_html__('Custom Taxonomy', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Name of custom taxonomy', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'order', 'name' => esc_html__('Order', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
					'DESC' => esc_html__('DESC', 'wp-timeline'),
					'ASC'=> esc_html__('ASC', 'wp-timeline')
				)
			),
			array( 'id' => 'orderby', 'name' => esc_html__('Order by', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
					'date' => esc_html__('Publish Date', 'wp-timeline'),
					'timeline_date' => esc_html__('Timeline Date', 'wp-timeline'),
					'ID' => esc_html__('ID', 'wp-timeline'),
					'author' => esc_html__('Author', 'wp-timeline'),
					'title' => esc_html__('Title', 'wp-timeline'),
					'name' => esc_html__('Name', 'wp-timeline'),
					'modified' => esc_html__('Modified', 'wp-timeline'),
					'parent' => esc_html__('Parent', 'wp-timeline'),
					'rand' => esc_html__('Random', 'wp-timeline'),
					'comment_count' => esc_html__('Comment count', 'wp-timeline'),
					'menu_order' => esc_html__('Menu order', 'wp-timeline'),
					'meta_value' => esc_html__('Meta value', 'wp-timeline'),
					'meta_value_num' => esc_html__('Meta value num', 'wp-timeline'),
					'post__in' => esc_html__('Post__in', 'wp-timeline'),
					'none' => esc_html__('None', 'wp-timeline'),
				)
			),
			array( 'id' => 'meta_key', 'name' => esc_html__('Meta key', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Enter meta key to query', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 6, ),
			array( 'id' => 'start_on', 'name' => esc_html__('Slide to start on', 'wp-timeline'), 'default'=> '', 'type' => 'number','desc' => esc_html__('Enter number, Default:0', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 6, ),
			
			array( 'id' => 'header_align', 'name' => esc_html__('Header alignment', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
						'' => esc_html__('Default', 'wp-timeline'),
						'center'=> esc_html__('Center', 'wp-timeline'),
						'left'=> esc_html__('Left', 'wp-timeline')
				)
			),
			array( 'id' => 'content_align', 'name' => esc_html__('Content alignment', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => esc_html__('Show label instead of date on timeline bar', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('Center', 'wp-timeline'),
						'left'=> esc_html__('Left', 'wp-timeline')
				)
			),
			
			array( 'id' => 'arrow_position', 'name' => esc_html__('Arrow buttons position', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => esc_html__('Select position of arrow', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('Center', 'wp-timeline'),
						'top'=> esc_html__('In timeline bar', 'wp-timeline')
				)
			),
			array( 'id' => 'toolbar_position', 'name' => esc_html__('Timeline bar position', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,  'desc' => esc_html__('Select position of timeline bar', 'wp-timeline'),
				'options' => array( 
						'top' => esc_html__('Top', 'wp-timeline'),
						'bottom'=> esc_html__('Bottom', 'wp-timeline')
				)
			),
			array( 'id' => 'show_media', 'name' => esc_html__('Show media', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'1' => esc_html__('Yes', 'wp-timeline'),
						'0'=> esc_html__('No', 'wp-timeline')
				)
			),
			array( 'id' => 'show_label', 'name' => esc_html__('Show label', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Show label instead of date on timeline bar', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'full_content', 'name' => esc_html__('Show full Content', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Show full Content instead of Excerpt', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			
			array( 'id' => 'show_all', 'name' => esc_html__('Show all items', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' =>  esc_html__('Show all items on timeline bar', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'hide_thumb', 'name' => esc_html__('Hide thubnails', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'autoplay', 'name' => esc_html__('Autoplay', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'autoplayspeed', 'name' => esc_html__('Autoplay Speed', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Autoplay Speed in milliseconds. Default:3000', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 12, ),
			
			array( 'id' => 'loading_effect', 'name' => esc_html__('Enable Loading effect', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			
			array( 'id' => 'enable_back', 'name' => esc_html__('Enable Back to timeline page', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => esc_html__('Only work with timeline post type', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'yes'=> esc_html__('Yes', 'wp-timeline')
				)
			),
		);

		$hoz_multi_inf = array(
			array( 'id' => 'style', 'name' => 'Style', 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
						'' => esc_html__('Style 1', 'wp-timeline'),
						'2' => esc_html__('Style 2', 'wp-timeline'),
						'3' => esc_html__('Style 3', 'wp-timeline'),
						'4' => esc_html__('Style 4', 'wp-timeline'),
						'5' => esc_html__('Style 5', 'wp-timeline'),
				)
			),
			array( 'id' => 'posttype', 'name' => esc_html__('Post types', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'multiple' => true, 'default' => 'wp-timeline','cols' => 6,
				'options' => $listpt
			),
			array( 'id' => 'ids', 'name' => esc_html__('IDs', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Specify post IDs to retrieve', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'count', 'name' => esc_html__('Count', 'wp-timeline'), 'default'=> '9', 'type' => 'text','desc' => esc_html__('Number of posts', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'slidesshow', 'name' => esc_html__('Number item visible', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Number item visible on timeline bar', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			
			array( 'id' => 'cat', 'name' => esc_html__('Category', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('List of cat ID (or slug), separated by a comma', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'tag', 'name' => esc_html__('Tags', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('List of tags, separated by a comma', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'qr_cttaxo', 'name' => esc_html__('Custom Taxonomy', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Name of custom taxonomy', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'order', 'name' => esc_html__('Order', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
					'DESC' => esc_html__('DESC', 'wp-timeline'),
					'ASC'=> esc_html__('ASC', 'wp-timeline')
				)
			),
			array( 'id' => 'orderby', 'name' => esc_html__('Order by', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6,
				'options' => array( 
					'date' => esc_html__('Publish Date', 'wp-timeline'),
					'timeline_date' => esc_html__('Timeline Date', 'wp-timeline'),
					'ID' => esc_html__('ID', 'wp-timeline'),
					'author' => esc_html__('Author', 'wp-timeline'),
					'title' => esc_html__('Title', 'wp-timeline'),
					'name' => esc_html__('Name', 'wp-timeline'),
					'modified' => esc_html__('Modified', 'wp-timeline'),
					'parent' => esc_html__('Parent', 'wp-timeline'),
					'rand' => esc_html__('Random', 'wp-timeline'),
					'comment_count' => esc_html__('Comment count', 'wp-timeline'),
					'menu_order' => esc_html__('Menu order', 'wp-timeline'),
					'meta_value' => esc_html__('Meta value', 'wp-timeline'),
					'meta_value_num' => esc_html__('Meta value num', 'wp-timeline'),
					'post__in' => esc_html__('Post__in', 'wp-timeline'),
					'none' => esc_html__('None', 'wp-timeline'),
				)
			),
			array( 'id' => 'meta_key', 'name' => esc_html__('Meta key', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Enter meta key to query', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			array( 'id' => 'start_on', 'name' => esc_html__('Slide to start on', 'wp-timeline'), 'default'=> '', 'type' => 'number','desc' => esc_html__('Enter number, Default:0', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			
			array( 'id' => 'number_excerpt', 'name' => esc_html__('Number of Excerpt', 'wp-timeline'), 'default'=> '', 'type' => 'number','desc' => esc_html__('Enter number, Default:0', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 4, ),
			
			
			array( 'id' => 'arrow_position', 'name' => esc_html__('Arrow buttons position', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Select position of arrow', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('Center', 'wp-timeline'),
						'top'=> esc_html__('In timeline bar', 'wp-timeline')
				)
			),
			array( 'id' => 'show_media', 'name' => esc_html__('Show media', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'1' => esc_html__('Yes', 'wp-timeline'),
						'0'=> esc_html__('No', 'wp-timeline')
				)
			),
			array( 'id' => 'show_label', 'name' => esc_html__('Show label', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Show label instead of date on timeline bar', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'full_content', 'name' => esc_html__('Show full Content', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => esc_html__('Show full Content instead of Excerpt', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			
			array( 'id' => 'hide_thumb', 'name' => esc_html__('Hide thubnails', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'autoplay', 'name' => esc_html__('Autoplay', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 4, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			array( 'id' => 'autoplayspeed', 'name' => esc_html__('Autoplay Speed', 'wp-timeline'), 'default'=> '', 'type' => 'text','desc' => esc_html__('Autoplay Speed in milliseconds. Default:3000', 'wp-timeline') , 'repeatable' => false, 'multiple' => false, 'cols' => 12, ),
			
			array( 'id' => 'loading_effect', 'name' => esc_html__('Enable Loading effect', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => '',
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'1'=> esc_html__('Yes', 'wp-timeline')
				)
			),
			
			array( 'id' => 'enable_back', 'name' => esc_html__('Enable Back to timeline page', 'wp-timeline'), 'type' => 'select', 'allow_none' => false, 'sortable' => false,'repeatable' => false , 'cols' => 6, 'desc' => esc_html__('Only work with timeline post type', 'wp-timeline'),
				'options' => array( 
						'' => esc_html__('No', 'wp-timeline'),
						'yes'=> esc_html__('Yes', 'wp-timeline')
				)
			),
		);
		
		
		$mt_pt = array('wptl_scbd');	
		$meta_boxes[] = array(
			'title' => esc_html__('General','wp-timeline'),
			'pages' => $mt_pt,
			'fields' => $sc,
			'priority' => 'high'
		);
		$meta_boxes[] = array(
			'title' => esc_html__('Timeline Listing','wp-timeline'),
			'pages' => $mt_pt,
			'fields' => $vertical_inf,
			'priority' => 'high'
		);
		$meta_boxes[] = array(
			'title' => esc_html__('Timeline Hozizontal','wp-timeline'),
			'pages' => $mt_pt,
			'fields' => $hoz_inf,
			'priority' => 'high'
		);
		$meta_boxes[] = array(
			'title' => esc_html__('Timeline Hozizontal Multi','wp-timeline'),
			'pages' => $mt_pt,
			'fields' => $hoz_multi_inf,
			'priority' => 'high'
		);
		return $meta_boxes;
	}
	
}
$WPEX_TL_SCPosttype = new WPEX_TL_SCPosttype();