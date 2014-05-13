<?php
/*
Author: Felipe I. González G.
Author URI: github.com/kaosb
*/

use \GutenPress\Model as Model;
/**************************/
/**** - ARTIST - **********/
/**************************/

class ArtistPostType extends \GutenPress\Model\PostType{
	/**
	 * Set post_type value
	 * @return string
	 */
	protected function setPostType(){
		return 'artist';
	}

	/**
	 * Set post type object properties
	 * @return array
	 */
	protected function setPostTypeObject(){
		return array(
			'label' => _x('artists', 'artist', 'cpt_artist'),
			'labels' => array(
				'name' => _x('Artists', 'artist', 'cpt_artist'),
				'singular_name' => _x('artist', 'artist', 'cpt_artist'),
				'add_new' => _x('Add new artist', 'artist', 'cpt_artist'),
				'all_items' => _x('Artists', 'artist', 'cpt_artist'),
				'add_new_item' => _x('Add new artist', 'artist', 'cpt_artist'),
				'edit_item' => _x('Edit artist', 'artist', 'cpt_artist'),
				'new_item' => _x('New artist', 'artist', 'cpt_artist'),
				'view_item' => _x('Show artist', 'artist', 'cpt_artist'),
				'search_items' => _x('Search artists', 'artist', 'cpt_artist'),
				'not_found' => _x('No artists found', 'artist', 'cpt_artist'),
				'not_found_in_trash' => _x('No artists found in the trash', 'artist', 'cpt_artist'),
				'parent_item_colon' => _x('Artist', 'artist', 'cpt_artist'),
				'menu_name' => _x('Artists catalog', 'artist', 'cpt_artist')
			),
			'description' => _x('Artists for music catalog.', 'artist', 'cpt_artist'),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => 'edit.php?post_type=music',
			'show_in_admin_bar' => true,
			'menu_position' => null,
			'menu_icon' => null,
			'capability_type' => array( 'artist', 'artists' ),
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'has_archive' => true,
			'rewrite' => array( 'slug' => __('artists', 'cpt_artist'), 'with_front' => true, 'feeds' => true, 'pages' => true ),
			'query_var' => true,
			'can_export' => false
		);
	}
}

// register plugin activation hook: add capabilities for admin users
register_activation_hook( __FILE__, array('ArtistPostType', 'activatePlugin') );
// register post type
add_action('init', array('ArtistPostType', 'registerPostType'));

class ArtistQuery extends Model\PostQuery{
	protected function setPostType(){
		return 'artist';
	}
	protected function setDecorator(){
		return 'ArtistObject';
	}
}

class ArtistObject extends Model\PostObject{
	// controller methods
}

// Metabox Artist Data.
class ArtistData extends Model\PostMeta{
	protected function setId(){
		return 'artistdata';
	}
	protected function setDataModel(){
		return array(
			new Model\PostMetaData(
				'url',
				'Url',
				'\GutenPress\Forms\Element\InputUrl',
				array(
					'placeholder' => 'Add artist url.'
					)
				)
		);
	}
}

// Registramos el metabox
new Model\Metabox( 'ArtistData', 'Artist Info', 'artist', array('context' => 'side', 'priority' => 'high') );