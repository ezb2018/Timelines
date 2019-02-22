<?php
// edit column admin 
add_filter( 'manage_wp-timeline_posts_columns', 'wpex_edit_columns',99 );
function wpex_edit_columns( $columns ) {
	global $wpdb;
	unset($columns['date']);
	$columns['wpex_date'] = esc_html__( 'Timeline Date' , 'wp-timeline' );
	$columns['wpex_ctdate'] = esc_html__( 'Custom Date' , 'wp-timeline' );
	$columns['wpex_order'] = esc_html__( 'Order' , 'wp-timeline' );
	$columns['wpex_color'] = esc_html__( 'Color' , 'wp-timeline' );
	$columns['wpex_icon_adm'] = esc_html__( 'Icon' , 'wp-timeline' );
	$columns['date'] = esc_html__( 'Publish date' , 'wp-timeline' );		
	return $columns;
}
add_action( 'manage_wp-timeline_posts_custom_column', 'wpex_custom_columns',12);
function wpex_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'wpex_date':
			$wpex_date = wpex_safe_strtotime(get_post_meta( $post->ID, 'wpex_pkdate', true ),'');
			echo '<span>'.esc_attr($wpex_date).'</span>';
			break;
		case 'wpex_ctdate':
			$wpex_customdate = get_post_meta($post->ID, 'wpex_date', true);
			echo '<input type="text" data-id="' . $post->ID . '" name="wpex_timeline_date" value="'.esc_attr($wpex_customdate).'">';
			break;
		case 'wpex_order':
			$wpex_order = get_post_meta($post->ID, 'wpex_order', true);
			echo '<input type="text" data-id="' . $post->ID . '" name="wpex_timeline_sort" value="'.esc_attr($wpex_order).'">';
			break;
		case 'wpex_color':
			$we_eventcolor = get_post_meta($post->ID, 'we_eventcolor', true);
			echo '<span style=" background-color:'.esc_attr($we_eventcolor).'; width: 15px;
    height: 15px; border-radius: 50%; display: inline-block;"></span>';
			break;	
		case 'wpex_icon_adm':
			$wpex_icon = get_post_meta($post->ID, 'wpex_icon', true);
			$wpex_icon_img = get_post_meta( $post->ID, 'wpex_icon_img', true );
			if($wpex_icon_img !=''){
				echo '<span class="wpex-icon-img" style=" background-image:url('.esc_url(wp_get_attachment_thumb_url( $wpex_icon_img )).'); "></span>';
			}else if($wpex_icon!=''){
				if(get_option('wpex_fontawesome_ver')!='5'){
					wp_enqueue_style('wpex-font-awesome', WPEX_TIMELINE.'css/font-awesome/css/font-awesome.min.css');
				}else{
					wp_enqueue_style('wpex-font-awesome-5', WPEX_TIMELINE.'css/font-awesome-5/css/all.min.css');
					wp_enqueue_style('wpex-font-awesome-shims', WPEX_TIMELINE.'css/font-awesome-5/css/v4-v4-shims.min.css');
				}
				echo '<span class="wpex-icon-img"><i class="fa '.esc_attr($wpex_icon).'"></i></span>';
			}
			break;		
	}
}
add_action('wp_ajax_wpex_change_timeline_sort', 'wpex_change_timeline_func' );
function wpex_change_timeline_func(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if(isset($post_id) && $post_id != 0)
	{
		update_post_meta($post_id, 'wpex_order', esc_attr(str_replace(' ', '', $value)));
	}
	die;
}
add_action('wp_ajax_wpex_change_timeline_date', 'wpex_change_date_timeline_func' );
function wpex_change_date_timeline_func(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if(isset($post_id) && $post_id != 0)
	{
		update_post_meta($post_id, 'wpex_date', $value);
	}
	die;
}

add_action( 'init', 'wptl_update_order_new_update' );
if(!function_exists('wptl_update_order_new_update')){
	function wptl_update_order_new_update() {
		 if( isset( $_GET['update_order'] ) && $_GET['update_order'] == 1) {
			 if ( is_user_logged_in() && current_user_can( 'manage_options' )){
				$my_posts = get_posts( array('post_type' => 'wp-timeline', 'numberposts' => -1 ) );
				foreach ( $my_posts as $post ):
					$wpex_pkdate = get_post_meta($post->ID,'wpex_pkdate', true );
					$order_mtk = explode("/",$wpex_pkdate);
					update_post_meta( $post->ID, 'wptl_orderdate', $order_mtk[2].$order_mtk[0].$order_mtk[1] );
				endforeach;
			 }
		 }
	}
}
// Check if icon field not exists
if(!class_exists('EXC_MB_Text_Icon') && class_exists('EXC_MB_Field')){
	add_filter( 'exc_mb_field_types', 'wptl_add_ct_field',99 );
	function wptl_add_ct_field($field){
		$field['icon_awesome'] = 'EXC_MB_Text_Icon';
		return $field;
	}
	class EXC_MB_Text_Icon extends EXC_MB_Field {
		public function html() {
			$width = '250px';
			if(get_option('wpex_fontawesome_ver')!='5'){
				$width = '150px';
				wp_enqueue_style( 'wpex-icon', WPEX_TIMELINE . 'inc/admin/font-awesome-select/simple-iconpicker.css');
				wp_enqueue_style('wpex-font-awesome', WPEX_TIMELINE.'css/font-awesome/css/font-awesome.min.css');
				wp_enqueue_script( 'wpex-icon-js', WPEX_TIMELINE . 'inc/admin/font-awesome-select/simple-iconpicker.min.js', array( 'jquery' ) );
			}?>
			<script>
            jQuery(document).ready(function(){
				<?php if(get_option('wpex_fontawesome_ver')!='5'){?>
					jQuery('.wptl-icon-select').iconpicker(".wptl-icon-select");
				<?php }?>
				jQuery('.select-ico').on('click',function() {
					jQuery('.wptl-icon-select').val('');
				});
            });
            </script>
			<input type="text" class="wptl-icon-select" style=" width:<?php echo $width;?>" <?php $this->id_attr(); ?> <?php $this->boolean_attr(); ?> <?php $this->class_attr(); ?> <?php $this->name_attr(); ?> value="<?php echo esc_attr( $this->get_value() ); ?>" />
			<span class="button select-ico" aria-hidden="true" style=""><?php esc_html_e('Remove','exthemes');?></span>
	
		<?php }
	}
}

