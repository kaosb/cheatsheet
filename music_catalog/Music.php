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

/**************************/
/**** - MUSIC - ***********/
/**************************/
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
			// 'menu_icon' => plugins_url( 'img/cd_music_w_20.png' , __FILE__ ),
			'menu_icon' => plugins_url().'/music_catalog/img/cd_music_w_20.png',
			'capability_type' => array( 'music', 'music' ),
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'has_archive' => true,
			'rewrite' => array( 'slug' => __('track', 'cpt_music'), 'with_front' => true, 'feeds' => true, 'pages' => true ),
			'query_var' => true,
			'can_export' => false
		);
	}

	// protected function setPostTypeCaps(){
	// 	// gets the author role
	// 	$role = get_role( 'author' );
	// 	// This only works, because it accesses the class instance.
	// 	// would allow the author to edit others' posts for current theme only
	// 	$role->add_cap( 'edit_others_posts' );
	// }

}

/*Codigo que define un filtro el filtro debe llevar el nombre de la clase*/
/*
// Instantiate the class object
$digital_music_catalog = digital_music_catalog::get_instance();
// Register activation
register_activation_hook(__FILE__, array($digital_music_catalog, 'digital_music_catalog_install'));
register_activation_hook(__FILE__, array($digital_music_catalog, 'digital_music_catalog_install_data'));
add_action( 'init', array( $digital_music_catalog, 'loadMenu' ) );
*/



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

// /**
// * Agregamos el rol y las capabilities.
// **/
// add_role( $role, $display_name, $capabilities );

/*class MusicCatalogShortcode extends \GutenPress\Model\Shortcode{
	public function setTag(){
		$this->tag = 'music_catalog';
	}
	public function setFriendlyName(){
		$this->friendly_name = _x('Music catalog', 'music catalog shortcode name', 'cpt_music_catalog_friendlyname');
	}
	public function setDescription(){
		$this->description = _x('Show music catalog', 'music catalog shortcode description', 'cpt_music_catalog_description');
	}
	public function display( $atts, $content ){
		global $editec;
		$regulations = new WP_Query(array(
			'post_type' => 'certificaciones',
			'paged' => max(get_query_var('paged'), 1)
		));
		ob_start();
?>
		<?php if ( $regulations->have_posts() ) : ?>
		<ul class="document-list stroke-list">
			<?php while ( $regulations->have_posts() ) : $regulations->the_post();
				try {
					$attachment = new GutenPress\Helpers\Attachment( get_post_meta(get_the_ID(), 'documentfile_documentDetailFile', true) );
				} catch ( \Exception $e ) {
					$attachment = null;
				}
			?>
			<li class="stroke-item">
				<div class="object-thumbnail-s object-thumbnail-right">
					<?php if ( ! is_null($attachment) ) : ?>
					<a href="<?php echo $attachment->url ?>" class="thumbnail file file-<?php echo $attachment->pathinfo->extension; ?>">
						<i class="icon icon-<?php echo $attachment->pathinfo->extension; ?>"></i>
						<span class="filetype"><?php echo strtoupper($attachment->pathinfo->extension); ?></span>
						<span class="filesize"><?php echo $attachment->filesize ?></span>
					</a>
					<?php endif; ?>
					<div class="wrap-content">
						<h3 class="entry-title document-title"><?php the_title(); ?></h3>
						<div class="entry-summary">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</li>
			<?php endwhile; wp_reset_query(); wp_reset_postdata(); ?>
		</ul>
		<?php $editec->getPager( $regulations ); ?>
		<?php endif; ?>
<?php
		return ob_get_clean();
	}
	public function configForm(){
		echo '<p class="description">No es necesario configurar este shortcode</p>';
	}
}
\GutenPress\Model\ShortcodeFactory::create('CertificationsShortcode');*/

/**************************/
/**** - GENRE - ***********/
/**************************/
class GenrePostType extends \GutenPress\Model\PostType{
	/**
	 * Set post_type value
	 * @return string
	 */
	protected function setPostType(){
		return 'genre';
	}

	/**
	 * Set post type object properties
	 * @return array
	 */
	protected function setPostTypeObject(){
		return array(
			'label' => _x('genres', 'genre', 'cpt_genre'),
			'labels' => array(
				'name' => _x('Genres', 'genre', 'cpt_genre'),
				'singular_name' => _x('genre', 'genre', 'cpt_genre'),
				'add_new' => _x('Add new genre', 'genre', 'cpt_genre'),
				'all_items' => _x('Genres', 'genre', 'cpt_genre'),
				'add_new_item' => _x('Add new genre', 'genre', 'cpt_genre'),
				'edit_item' => _x('Edit genre', 'genre', 'cpt_genre'),
				'new_item' => _x('New genre', 'genre', 'cpt_genre'),
				'view_item' => _x('Show genre', 'genre', 'cpt_genre'),
				'search_items' => _x('Search genres', 'genre', 'cpt_genre'),
				'not_found' => _x('No genres found', 'genre', 'cpt_genre'),
				'not_found_in_trash' => _x('No genres found in the trash', 'genre', 'cpt_genre'),
				'parent_item_colon' => _x('Genre', 'genre', 'cpt_genre'),
				'menu_name' => _x('Genres catalog', 'genre', 'cpt_genre')
			),
			'description' => _x('Genres for music catalog', 'genre', 'cpt_genre'),
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => 'edit.php?post_type=music',
			'show_in_admin_bar' => true,
			'menu_position' => null,
			'menu_icon' => null,
			'capability_type' => array( 'genre', 'genres' ),
			'hierarchical' => true,
			'supports' => array( 'title', 'editor' ),
			'has_archive' => true,
			'rewrite' => array( 'slug' => __('genres', 'cpt_genre'), 'with_front' => true, 'feeds' => true, 'pages' => true ),
			'query_var' => true,
			'can_export' => false
		);
	}
}

// register plugin activation hook: add capabilities for admin users
register_activation_hook( __FILE__, array('GenrePostType', 'activatePlugin') );
// register post type
add_action('init', array('GenrePostType', 'registerPostType'));

class GenreQuery extends Model\PostQuery{
	protected function setPostType(){
		return 'genre';
	}
	protected function setDecorator(){
		return 'GenreObject';
	}
}

class GenreObject extends Model\PostObject{
	// controller methods
}

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
