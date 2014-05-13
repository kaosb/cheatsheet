<?php
/*
Plugin Name: Digital music catalog
Plugin URI: github.com/covelop
Description: Manage digital music catalog.
Version: 0.1
Author: Felipe I. González G.
Author URI: github.com/kaosb
*/

class digital_music_catalog{

	/**
	* Atributos
	**/
	private static $instance;

	private function __construct(){
		// global $wpdb;
		// $wpdb->rsvp_participant = $wpdb->prefix . $this->rsvp_participant;
		// $wpdb->rsvp_person = $wpdb->prefix . $this->rsvp_person;
		// $this->actions_manager();
	}

	// private function actions_manager(){
	// 	add_action('admin_init', array($this, 'saveEvent'));
	// 	add_action('init', array($this, 'saveParticipant'));
	// 	// Functions
	// 	//add_action('admin_init', array($this, 'nuevoFondo'));
	// 	// Script uploader
	// 	//add_action('admin_print_scripts', array($this, 'cargascripts'));
	// }

	public static function get_instance(){
		if(!isset(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	public function __clone(){
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	/**
	* Function responsible for creating and initializing the data model needed to operate the plugin function. 
	**/
	public static function digital_music_catalog_install(){
		global $wpdb;
		$table_name_1 = $wpdb->prefix."mp3_music_catalog";
		$table_name_2 = $wpdb->prefix."artist_music_catalog";
		$table_name_3 = $wpdb->prefix."genre_music_catalog";
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		$sql_3 = "CREATE TABLE IF NOT EXISTS $table_name_3(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name tinytext NOT NULL,
			description text NOT NULL,
			updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
			UNIQUE KEY id (id));";
		dbDelta($sql_3);
		$sql_2 = "CREATE TABLE IF NOT EXISTS $table_name_2(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name tinytext NOT NULL,
			description text NOT NULL,
			url VARCHAR(55) DEFAULT '' NOT NULL,
			updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
			UNIQUE KEY id (id));";
		dbDelta($sql_2);
		$sql_1 = "CREATE TABLE IF NOT EXISTS $table_name_1(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name tinytext NOT NULL,
			year mediumint(9) NOT NULL,
			artist mediumint(9) NOT NULL,
			genre mediumint(9) NOT NULL,
			editor tinytext NOT NULL,
			filename tinytext NOT NULL,
			available mediumint(9) DEFAULT 0 NOT NULL,
			bpm mediumint(9) NOT NULL,
			updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
			note text NOT NULL,
			UNIQUE KEY id (id),
			FOREIGN KEY (artist) REFERENCES $table_name_2(id),
			FOREIGN KEY (genre) REFERENCES $table_name_3(id)
			);";
		dbDelta($sql_1);
	}

	/**
	* Funcion responsible for initializing the table with test data.
	**/
	public static function digital_music_catalog_install_data(){
		global $wpdb;
		$table_name_1 = $wpdb->prefix."mp3_music_catalog";
		$table_name_2 = $wpdb->prefix."artist_music_catalog";
		$table_name_3 = $wpdb->prefix."genre_music_catalog";
		$wpdb->insert($table_name_3, array('name' => 'lorem ble', 'description' => 'lorem bla'));
		$wpdb->insert($table_name_3, array('name' => 'lorem ble', 'description' => 'lorem bla'));
		$wpdb->insert($table_name_3, array('name' => 'lorem ble', 'description' => 'lorem bla'));
		$wpdb->insert($table_name_3, array('name' => 'lorem ble', 'description' => 'lorem bla'));
		$wpdb->insert($table_name_3, array('name' => 'lorem ble', 'description' => 'lorem bla'));
		$wpdb->insert($table_name_2, array('name' => 'lorem ble', 'description' => 'lorem bla', 'url' => 'lorem.ble'));
		$wpdb->insert($table_name_2, array('name' => 'lorem ble', 'description' => 'lorem bla', 'url' => 'lorem.ble'));
		$wpdb->insert($table_name_2, array('name' => 'lorem ble', 'description' => 'lorem bla', 'url' => 'lorem.ble'));
		$wpdb->insert($table_name_2, array('name' => 'lorem ble', 'description' => 'lorem bla', 'url' => 'lorem.ble'));
		$wpdb->insert($table_name_2, array('name' => 'lorem ble', 'description' => 'lorem bla', 'url' => 'lorem.ble'));
		$wpdb->insert($table_name_1, array('name' => 'lorem ipsum', 'year' => 2014, 'artist' => 2, 'genre' => 3, 'editor' => 'ipsum lorem', 'filename' => '00001', 'bpm' => 256, 'updated_at' => current_time('mysql'), 'note' => 'bla ble bli blo blu'));
		$wpdb->insert($table_name_1, array('name' => 'lorem ipsum', 'year' => 2014, 'artist' => 2, 'genre' => 3, 'editor' => 'ipsum lorem', 'filename' => '00001', 'bpm' => 256, 'updated_at' => current_time('mysql'), 'note' => 'bla ble bli blo blu'));
		$wpdb->insert($table_name_1, array('name' => 'lorem ipsum', 'year' => 2014, 'artist' => 2, 'genre' => 3, 'editor' => 'ipsum lorem', 'filename' => '00001', 'bpm' => 256, 'updated_at' => current_time('mysql'), 'note' => 'bla ble bli blo blu'));
		$wpdb->insert($table_name_1, array('name' => 'lorem ipsum', 'year' => 2014, 'artist' => 2, 'genre' => 3, 'editor' => 'ipsum lorem', 'filename' => '00001', 'bpm' => 256, 'updated_at' => current_time('mysql'), 'note' => 'bla ble bli blo blu'));
		$wpdb->insert($table_name_1, array('name' => 'lorem ipsum', 'year' => 2014, 'artist' => 2, 'genre' => 3, 'editor' => 'ipsum lorem', 'filename' => '00001', 'bpm' => 256, 'updated_at' => current_time('mysql'), 'note' => 'bla ble bli blo blu'));
	}

	/**
	* Add the menu to the panel
	**/
	public function loadMenu(){
		add_action('admin_menu', array( $this, 'addMenuAdmin'));
	}

	/**
	* Add submenu to the menu in the panel
	**/
	public function addMenuAdmin(){
		add_menu_page('Digital catalog', 'Music catalog', 10, 'manage_music_catalog', array( $this, 'music_catalog'), bloginfo('wpurl')."/wp-content/plugins/digital_music_catalog/img/cd_music_w_20.png", 9);
		add_submenu_page( 'manage_music_catalog', 'Manage artist', 'Manage artist', 10, 'manage_music_catalog_artist', array( $this, 'music_catalog'));
		add_submenu_page( 'manage_music_catalog', 'Manage genre', 'Manage genre', 10, 'manage_music_catalog_genre', array( $this, 'music_catalog'));
	}

	/**
	* Funcion que gatilla el crud de Music catalog segun corresponda.
	**/
	public function music_catalog(){
		if(isset($_GET['action'])){
			$action = $_GET['action'];
		}else{
			$action = "show";
		}
		switch($action){
			case "show":
			$this->show_music_catalog();
			break;
			case "add":
			$this->add_music();
			break;
			case "edit":
			$this->edit_music();
			break;
			default:
			$this->show_music_catalog();
			end;
		}
	}

	/**
	* Funcion que despliega los primeros 100 elementos y su respectivo paginador.
	**/
	public function show_music_catalog(){
		global $wpdb;
		$results = $wpdb->get_results('select * from '.$wpdb->prefix .'mp3_music_catalog order by created_at ASC', ARRAY_A);  
		?>
		<div class="wrap">
			<h2>Music Catalog <a href="admin.php?page=manage_music_catalog&action=add" class="add-new-h2">Añadir nueva</a></h2>
			<ul class="subsubsub">
				<li class="enable"><a href="admin.php?page=manage_music_catalog" class="current">Enabled <span class="count">1</span></a> |</li>
				<li class="disable"><a href="admin.php?page=manage_music_catalog">Disables <span class="count">0</span></a></li>
			</ul>
			<div class='tablenav-pages'>
				<table class="widefat">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Artist</th>
							<th>Genre</th>
							<th>bpm</th>
							<th>Available</th>
							<th>-</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Artist</th>
							<th>Genre</th>
							<th>bpm</th>
							<th>Available</th>
							<th>-</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
						foreach($results as $song){
							$urlEdit = "admin.php?page=manage_music_catalog&action=edit&id=".$song[id];
							echo "<tr>";
							echo "<td>".$song[id]."</td>";
							echo "<td>".$song[name]."</td>";
							echo "<td>".$song[artist]."</td>";
							echo "<td>".$song[genre]."</td>";
							echo "<td>".$song[bpm]."kbps</td>";
							echo "<td>".$song[availible]."</td>";
							echo "<td><a href='".$urlEdit."' target='_self'>manage</a></td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	/**
	* Funcion que permite agregar un nuevo tema al catalogo.
	**/
	public function add_music(){
		?>
		<h1>Add</h1>
		<?php
	}

	/**
	* Funcion que editar un tema del catalogo.
	**/
	public function edit_music(){
		?>
		<h1>Manage</h1>
		<?php
	}
}

// Instantiate the class object
$digital_music_catalog = digital_music_catalog::get_instance();
// Register activation
register_activation_hook(__FILE__, array($digital_music_catalog, 'digital_music_catalog_install'));
register_activation_hook(__FILE__, array($digital_music_catalog, 'digital_music_catalog_install_data'));
add_action( 'init', array( $digital_music_catalog, 'loadMenu' ) );

?>