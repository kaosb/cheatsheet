<?php
/*
Plugin Name: Music Catalog
Plugin URI: github.com/covelop
Description: Manage digital music catalog.
Version: 0.1

Author: Felipe I. González G.
Author URI: github.com/kaosb
*/
use \GutenPress\Model as Model;

class MusicPostType extends \GutenPress\Model\PostType{
	/**
	 * Set post_type value
	 * @return string
	 */
	protected function setPostType(){
		return 'music';
	}

	/**
	 * Set post type object properties
	 * @return array
	 */
	protected function setPostTypeObject(){
		return array(
			'label' => _x('Music', 'music', 'cpt_music'),
			'labels' => array(
				'name' => _x('Music', 'music', 'cpt_music'),
				'singular_name' => _x('Music', 'music', 'cpt_music'),
				'add_new' => _x('Add new Track', 'music', 'cpt_music'),
				'all_items' => _x('Music', 'music', 'cpt_music'),
				'add_new_item' => _x('Add new Track', 'music', 'cpt_music'),
				'edit_item' => _x('Edit Track', 'music', 'cpt_music'),
				'new_item' => _x('New Track', 'music', 'cpt_music'),
				'view_item' => _x('View Track', 'music', 'cpt_music'),
				'search_items' => _x('Search Track', 'music', 'cpt_music'),
				'not_found' => _x('No Track found', 'music', 'cpt_music'),
				'not_found_in_trash' => _x('No Track found in the trash', 'music', 'cpt_music'),
				'parent_item_colon' => _x('Music', 'music', 'cpt_music'),
				'menu_name' => _x('Music catalog', 'music', 'cpt_music')
			),
			'description' => _x('Manage the music catalog', 'music', 'cpt_music'),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 1,
			'menu_icon' => plugins_url( 'img/cd_music_w_20.png' , __FILE__ ),
			'capability_type' => array( 'music', 'music' ),
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'has_archive' => true,
			'rewrite' => array( 'slug' => __('track', 'cpt_music'), 'with_front' => true, 'feeds' => true, 'pages' => true ),
			'query_var' => true,
			'can_export' => false
		);
	}
}

// register plugin activation hook: add capabilities for admin users
register_activation_hook( __FILE__, array('MusicPostType', 'activatePlugin') );
// register post type
add_action('init', array('MusicPostType', 'registerPostType'));

class MusicQuery extends Model\PostQuery{
	protected function setPostType(){
		return 'music';
	}
	protected function setDecorator(){
		return 'MusicObject';
	}
}

class MusicObject extends Model\PostObject{
	// controller methods
}

// Metabox Track Data.
class TrackData extends Model\PostMeta{
	protected function setId(){
		return 'trackdata';
	}
	protected function setDataModel(){
		return array(
			new Model\PostMetaData(
				'available',
				'is available?',
				'\GutenPress\Forms\Element\InputCheckbox',
				array(
					'id' => 'trackdata_available',
					'options' => array( 'available' => 'Yes is available' ),
					'description' => 'The item will appear as available for purchase.'
				)
			),
			new Model\PostMetaData(
				'year',
				'Year',
				'\GutenPress\Forms\Element\InputNumber',
				array(
					'placeholder' => 'Add year track.'
					)
				),
			new Model\PostMetaData(
				'artist',
				'Artist',
				'\GutenPress\Forms\Element\Select',
				array(
					'class' => 'widefat',
					'options' => $this->getPosttypeOptions('artist', get_post_meta($post->ID, 'trackdata_artist', false)),
					'description' => '<a href="'. esc_url(admin_url('edit.php?post_type=artist')) .'">Manage artists</a>'
					)
				),
			new Model\PostMetaData(
				'genre',
				'Genre',
				'\GutenPress\Forms\Element\Select',
				array(
					'class' => 'widefat',
					'options' => $this->getPosttypeOptions('genre', get_post_meta($post->ID, 'trackdata_genre', false)),
					'description' => '<a href="'. esc_url(admin_url('edit.php?post_type=genre')) .'">Manage genres</a>'
					)
				),
			new Model\PostMetaData(
				'bpm',
				'Track BPM',
				'\GutenPress\Forms\Element\InputNumber',
				array(
					'placeholder' => 'Add track bpm.'
					)
				),
			new Model\PostMetaData(
				'note',
				'Note',
				'\GutenPress\Forms\Element\Textarea',
				array(
					'cols'	=>	'25',
					'row'	=>	'3',
					'class'			=>	'regular-text'
					)
				)
		);
	}
	private function getPosttypeOptions($posttype, $selected_values){
		if(is_array($posttype)){
			$label = "post";
		}else{
			$label = $posttype;
		}
		$options = array('' => '(Select an '.$label.')');
		$issues  = new WP_Query(array(
			'order'          => 'DESC',
			'orderby'        => 'date',
			'post_type'      => $posttype,
			'posts_per_page' => 500,
			'post_status'    => array('publish', 'draft')
		));
		if ( ! empty($selected_values) ) {
			$selected = new WP_Query(array(
				'post__in' => $selected_values,
				'post_type' => 'any',
				'orderby' => 'post__in',
				'posts_per_page' => -1
			));
		}
		if ( count($selected->posts) > 0 ) {
			foreach ( $selected->posts as $issue ) $options[ $issue->ID ] = apply_filters('the_title', $issue->post_title);
		}
		if ( count($issues->posts) > 0 ) {
			foreach ( $issues->posts as $issue ) $options[ $issue->ID ] = apply_filters('the_title', $issue->post_title);
		}
		return $options;
	}
}

// Registramos el metabox
new Model\Metabox( 'TrackData', 'Track Info', 'music', array('context' => 'side', 'priority' => 'high') );


/**
* Function responsable de modificar el directorio de uploads para este post-type
**/
function custom_upload_directory( $args ) {
	$id = $_REQUEST['post_id'];
	$parent = get_post( $id )->post_parent;
	if( "music" == get_post_type( $id ) || "music" == get_post_type( $parent ) ) {
		$args['path'] = plugin_dir_path(__FILE__) . "uploads";
		$args['url']  = plugin_dir_url(__FILE__) . "uploads";
		$args['basedir'] = plugin_dir_path(__FILE__) . "uploads";
		$args['baseurl'] = plugin_dir_url(__FILE__) . "uploads";
	}
	return $args;
}
/**
* Filtro que gatilla la funcion anterior.
**/
add_filter( 'upload_dir', 'custom_upload_directory' );





