<?php
if(!isset($_SESSION))
{
	session_start();
}
/*
Plugin Name: Easy Table Manager
Plugin URI:
Description: Generate Crud Functionality to any DB Table
Version: 1.0.0
Author: Md. Joynal Abedin Parag
Author URI: https://www.linkedin.com/in/abedin-joynal
License: GPL2
*/

require_once( plugin_dir_path( __FILE__ ) . 'easy-table-manager-options.php' );
Class EasyTableManager extends EasyTableManagerManageOptions
{
	private $nonce = 'easy-crud';
	private $primary_key = array('debt_loan_requests' => 'id',
								'debt_loan_country' => 'id'
							);
	public $max_index_column = 40;
	public $image_dir = "/public/images/catalog/products/";
	public $labels = array('name' => 'Name');
	public $ignore = array('id', 'created_at', 'updated_at');
	public $optional_fields = array('rate_high', 'rate_low');
	public $image_fields = array('bank_logo', 'card_logo', 'commodity_logo', 'metal_logo');
	public $checkbox_fields = array('test' => array('value' => 1));
	public $radio_fields = array (
		'is_featured' => array(1 => 'Yes', 0 => 'No'),
		'is_buyable' => array(1 => 'Yes', 0 => 'No'),
		'is_account_openable' => array(1 => 'Yes', 0 => 'No'),
		'is_bookable' => array(1 => 'Yes', 0 => 'No')
	);

	public $relational_fields;

	public $schema, $attributes, $relational_fields_data = array();

	function __construct() {
		parent::__construct();
		$this->easy_table_manager_activate();
		$this->easy_table_manager_deactivate();
		$this->easy_table_manager_setup_ajax_handlers();
		$this->easy_table_manager_defineActions ();
		$this->easy_table_manager_hook_scripts();
		$this->easy_table_manager_initialize_shortcodes();

		global $wpdb;
		$this->db = $wpdb;
		$this->db_prefix = $this->db->prefix;

		/* Retrieve Options from Option table */
		$this->relational_fields = json_decode(stripslashes(get_option('etm_relational_fields_setting')), true);
		/* Retrieve Options from Option table */

		if(isset($_SESSION['easy_table_manager_param']) && !empty($_SESSION['easy_table_manager_param'])) :
			$this->easy_table_manager_assign_vars($_SESSION['easy_table_manager_param']);
		endif;

		$this->easy_table_manager_prepareRelationalFieldData();
	}

	public function easy_table_manager_activate() {
		register_activation_hook( __FILE__, array( $this, 'easy_table_manager_install' ) );
	}

	public function easy_table_manager_deactivate () {
		register_deactivation_hook( __FILE__, array( $this, 'easy_table_manager_uninstall' ) );
	}

	function easy_table_manager_hook_scripts () {
		add_action( 'wp_enqueue_scripts', array($this, 'easy_table_manager_enqueue_scripts' ));
		add_action('admin_enqueue_scripts', array($this, 'easy_table_manager_enqueue_scripts'));
		add_action( 'admin_menu', array($this, 'easy_table_manager_addAdminMenu'));
	}

	function easy_table_manager_install() {
		add_option("etm_db_version", "1.0.0");
		add_option("etm_relational_fields_settings", "{}");
	}

	function easy_table_manager_uninstall() {
		delete_option("easy_table_manager_crud_db_version");
	}

	function easy_table_manager_enqueue_scripts() {
		$params = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( $this->nonce ),
		);
		wp_enqueue_style( "smart_crud_css", plugin_dir_url( __FILE__ ) . 'assets/css/smart-crud.css' );
		wp_enqueue_script( 'jquery' );

		wp_register_script( "smart_crud_script", plugin_dir_url( __FILE__ ) . 'assets/js/smart-crud.js', array('jquery') );
		wp_localize_script( 'smart_crud_script', 'smart_crud', $params);
		wp_enqueue_script( 'smart_crud_script' );

		wp_enqueue_style( "bootstrap_css", plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap-3.3.7/css/bootstrap.css' );
		wp_enqueue_script( "bootstrap_js", plugin_dir_url( __FILE__ ) . 'assets/js/bootstrap-3.3.7/js/bootstrap.js' );

		wp_enqueue_style( "jquery_ui_css", plugin_dir_url( __FILE__ ) . 'assets/css/jquery-ui.css' );
		wp_enqueue_script( "jquery_ui_js", plugin_dir_url( __FILE__ ) . 'assets/js/jquery-ui.js');

	}

	public function easy_table_manager_setup_ajax_handlers() {
		add_action( 'wp_ajax_generateCreatePage', array( $this, 'easy_table_manager_generateCreatePage' ) );
		add_action( 'wp_ajax_nopriv_generateCreatePage', array( $this, 'easy_table_manager_generateCreatePage' ) );

		add_action( 'wp_ajax_generateEditPage', array( $this, 'easy_table_manager_generateEditPage' ) );
		add_action( 'wp_ajax_nopriv_generateEditPage', array( $this, 'easy_table_manager_generateEditPage' ) );

		add_action( 'wp_ajax_generateListingPage', array( $this, 'easy_table_manager_generateListingPage' ) );
		add_action( 'wp_ajax_nopriv_generateListingPage', array( $this, 'easy_table_manager_generateListingPage' ) );

		add_action( 'wp_ajax_save', array( $this, 'easy_table_manager_save' ) );
		add_action( 'wp_ajax_nopriv_save', array( $this, 'easy_table_manager_save' ) );

		add_action( 'wp_ajax_update', array( $this, 'easy_table_manager_update' ) );
		add_action( 'wp_ajax_nopriv_update', array( $this, 'easy_table_manager_update' ) );

		add_action( 'wp_ajax_delete', array( $this, 'easy_table_manager_delete' ) );
		add_action( 'wp_ajax_nopriv_delete', array( $this, 'easy_table_manager_delete' ) );

	}

	public function easy_table_manager_defineActions () {
		add_action( 'admin_post_nopriv_exportExcel', array($this, 'easy_table_manager_exportExcel'));
		add_action( 'admin_post_exportExcel', array($this, 'easy_table_manager_exportExcel'));
	}

	public function easy_table_manager_initialize_shortcodes() {
		add_shortcode( 'smartCrud', array($this, 'easy_table_manager_smartCrud'));
	}

	public function easy_table_manager_addAdminMenu() {
		add_menu_page( 'Easy Table Manager', 'Easy Table Manager', 'manage_options', 'easy-table-manager', array($this, 'easy_table_manager_options'));
		add_submenu_page( 'easy-table-manager', 'Related Fields - ETM', 'Related Fields', 'manage_options', 'easy-table-manager-related-fields', array($this, 'etm_related_fields'));
	}

	public function easy_table_manager_smartCrud($attr) {
		$schema = isset($attr['schema']) ? $attr['schema'] : false;
		$params = isset($attr['params']) ? $attr['params'] : false;
		if($params) {
			$params = stripslashes( $params );
			$params = unserialize( $params );
			$_SESSION['easy_table_manager_param'] = $params;
			if(isset($_SESSION['easy_table_manager_param']) && !empty($_SESSION['easy_table_manager_param'])) :
				$this->easy_table_manager_assign_vars($_SESSION['easy_table_manager_param']);
			endif;
		}

		if($schema):
			$prefix = $this->db->prefix;
			$schema =  preg_match("/^$prefix.*$/", $schema) ? $schema : $this->db->prefix . $schema;
			$this->schema = $schema;
			return $this->easy_table_manager_initiateCrud();
		else:
			return "Invalid Arguments";
		endif;

	}

	private function easy_table_manager_assign_vars($params) {
		foreach($params as $var_name => $var_data) {
			if(isset($this->$var_name)) {
				$this->$var_name = $var_data;
			}
		}
	}

	public function easy_table_manager_validateForm($form_data) {
		$valid = true;
		$validation_errors = array();
		$this->easy_table_manager_getSchemaAttributes();
		foreach($this->attributes as $key => $field_details) :
			if(!in_array($key, $this->optional_fields) && !in_array($key, $this->ignore) && empty($form_data[$key])) :
				$valid = false;
				$validation_errors[] = ucwords( str_replace( "_", " ", $key ) ) . " is empty ";
			endif;
		endforeach;
		return array('status' => $valid, 'errors' => implode(', <br> ', $validation_errors));
	}

	public function easy_table_manager_initiateCrud() {
		$data = array();
		$data = array_merge($data, get_object_vars($this));
		$data['content'] = $this->easy_table_manager_generateListingPage(true);
		echo $this->easy_table_manager_load('layout', $data);
	}

	public function easy_table_manager_generateListingPage( $return = false ) {
		$this->schema = isset($_GET['schema']) ? $_GET['schema'] : $this->schema;
		$this->easy_table_manager_getSchemaAttributes();
		$data = array();
		$data['items'] = $this->easy_table_manager_getAllDataFromSchema();
		$data = array_merge($data, get_object_vars($this));
		$content = $this->easy_table_manager_load('listing', $data);
		if($return == true) {
			return $content;
		} else {
			echo $content;
		}
		exit;
	}

	public function easy_table_manager_generateEditPage() {
		$data = array();
		$id = isset($_GET['id']) ? $_GET['id'] : false;
		$this->schema = isset($_GET['schema']) ? $_GET['schema'] : false;
		if($this->schema && $id) :
			$this->easy_table_manager_getSchemaAttributes();
			$data['item'] = $this->getDataFromSchemaById($id, $this->schema);
			$data = array_merge($data, get_object_vars($this));
			echo $this->easy_table_manager_load('edit', $data);
		else :
			return "Invalid Arguments";
		endif;
		exit;
	}

	public function easy_table_manager_generateCreatePage () {
		$data = array();
		$this->schema = isset($_GET['schema']) ? $_GET['schema'] : false;
		if($this->schema) :
			$this->easy_table_manager_getSchemaAttributes();
			$data = array_merge($data, get_object_vars($this));
			echo $this->easy_table_manager_load('create', $data);
		else :
			return "Invalid Arguments";
		endif;
		exit;
	}

	public function easy_table_manager_save() {
		$response = array();
		$form_data = isset($_POST) ? $_POST : false;
		if($form_data) :
			$this->schema = $_POST['schema'];
			$validity = $this->easy_table_manager_validateForm($form_data);
			if ($validity['status'] == true) :
				unset($form_data['action']);
				unset($form_data['schema']);
				unset($form_data['security']);
				unset($form_data['date_from']);
				unset($form_data['date_to']);
				$this->db->insert($this->schema, $form_data);
				if ($this->db->last_error) :
					$response['status'] = "error";
					$response['msg'] = $this->db->last_error;
				else:
					$response['status'] = "success";
				endif;
			else:
				$response['status'] = "error";
				$response['msg'] = $validity['errors'];
			endif;
		else:
			$response['status'] = "error";
			$response['msg'] = "Invalid Arguments";
		endif;
		echo json_encode($response);
		exit;
	}

	public function easy_table_manager_update() {
		$response = array();
		$form_data = isset($_POST) ? $_POST : false;
		if($form_data) :
			$this->schema = $_POST['schema'];
			$validity = $this->easy_table_manager_validateForm($form_data);
			if ($validity['status'] == true) :
				unset($form_data['action']);
				unset($form_data['schema']);
				unset($form_data['security']);
				unset($form_data['date_from']);
				unset($form_data['date_to']);
				$primary_key = isset($this->primary_key[$this->schema]) ? $this->primary_key[$this->schema] : 'id';
				$this->db->update($this->schema, $form_data, array($primary_key => $form_data[$primary_key]));
				if ($this->db->last_error) :
					$response['status'] = "error";
					$response['msg'] = $this->db->last_error;
				else:
					$response['status'] = "success";
				endif;
			else:
				$response['status'] = "error";
				$response['msg'] = $validity['errors'];
			endif;
		else:
			$response['status'] = "error";
			$response['msg'] = "Invalid Arguments";
		endif;
		echo json_encode($response);
		exit;
	}

	public function easy_table_manager_delete () {
		$id = isset($_POST['id']) ? $_POST['id'] : false;
		$schema = isset($_POST['schema']) ? $_POST['schema'] : false;
		if($id) :
			$primary_key = isset($this->primary_key[$schema]) ? $this->primary_key[$schema] : 'id';
			$this->db->delete( $schema, array( $primary_key => $id ) );
		else:
			return "Invalid Arguments";
		endif;
	}

	function easy_table_manager_getAllDataFromSchema() {
		$rows = $this->db->get_results( "SELECT * FROM $this->schema ");
		if ($this->db->last_error):
			return $this->db->last_error;
		else:
			return $rows;
		endif;
	}

	function getDataFromSchemaById($id, $schema) {
		if($id) :
			$primary_key = isset($this->primary_key[$schema]) ? $this->primary_key[$schema] : 'id';
			$row = $this->db->get_results( "SELECT * FROM `$schema` where `".$primary_key."` = $id", ARRAY_A);
			if ($this->db->last_error):
				return $this->db->last_error;
			else:
				return $row[0];
			endif;
		else:
			return "Invalid Arguments";
		endif;
	}

	function easy_table_manager_getSchemaAttributes() {
		$query = "SHOW COLUMNS FROM `$this->schema`";
		$results = $this->db->get_results($query);
		$attr = array();
		foreach($results as $result) {
			$attr[$result->Field] = array('Type' => $result->Type, 'Null' => $result->Null, 'Default' => $result->Default);
		}
		$this->attributes = $attr;
	}

	function easy_table_manager_prepareRelationalFieldData() {
		if (!empty($this->relational_fields)) :
			foreach($this->relational_fields as $schema => $relation_details):
				foreach ($relation_details as $field_name => $relational_field) :
					$select1 = $relational_field[1];
					$select2 = $relational_field[2];
					$related_table = $relational_field[0];
					$condition = isset($relational_field[3]) ? $relational_field[3] : false;
					$where = "";
					if($condition && is_array($condition)) :
						$where = " where $condition[0] $condition[1] $condition[2]";
					endif;

					$prefix = $this->db->prefix;
					$related_table = preg_match("/^$prefix.*$/", $related_table) ? $related_table : $this->db->prefix . $related_table;
					$sql = " select $select1, $select2 from $related_table $where";
					$rows = $this->db->get_results( $sql, ARRAY_A );
					$rf_data = array();
					foreach($rows as $row):
						$index = $row[$select1];
						$rf_data[$index] = $row[$select2];
					endforeach;
					$this->relational_fields_data[$schema][$field_name] = $rf_data;
				endforeach;
			endforeach;
		endif;
	}

	function easy_table_manager_exportExcel() {
		$date_field = "created_at";
		$export_ignore = array('id', 'status');
		$this->schema = isset($_POST['schema']) ? $_POST['schema'] : false;
		$filename = "loanreport_".date('Y_m_d').".csv";
		$fp = fopen('php://output', 'w');

		$fields = array();
		$this->easy_table_manager_getSchemaAttributes();
		// print_r($columns);exit;
		foreach($this->attributes as $key => $field_details) {
			$fields[] = $key;
			if (!in_array($key, $export_ignore)) {
				$header[] = ucwords(str_replace("_", " ", $key));
			}
		}

		ob_clean();
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		fputcsv($fp, $header);

		$form_data = $_POST;
		$where =  " 1=1  ";
		if(strlen(trim($form_data['date_from']))) {
			$where .= " and $date_field >= '" . $form_data['date_from'] . " 00:00:00'";
		}

		if(strlen(trim($form_data['date_to']))) {
			$where .= " and $date_field <= '" . $form_data['date_to'] . "  23:59:59'";
		}

		$fetchable_fields = array_diff($fields, $export_ignore);
		$fetchable_fields = implode(',', $fetchable_fields);
		$sql = "SELECT $fetchable_fields FROM " . $this->schema . " WHERE " . $where;
		$rows = $this->db->get_results( $sql, ARRAY_N);
		$data = array();
		foreach ($rows as $row) {
			fputcsv( $fp, $row );
		}
		exit;
	}
}

$class = new EasyTableManager();
?>
