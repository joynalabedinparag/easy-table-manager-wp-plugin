<?php
require_once( plugin_dir_path( __FILE__ ) . 'easy-table-manager-lib.php' );
Class EasyTableManagerManageOptions extends EasyTableManagerLib
{
	function __construct() {
		$this->etmo_hook_scripts();
		$this->etmo_setup_ajax_handlers();
	}

	public function etmo_hook_scripts () {
		add_action( 'wp_enqueue_scripts', array($this, 'etmo_enqueue_scripts' ));
		add_action('admin_enqueue_scripts', array($this, 'etmo_enqueue_scripts'));
	}

	public function etmo_enqueue_scripts() {
		$params = array (
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'nonce' ),
		);
		wp_register_script( "easy_table_manager_options", plugin_dir_url( __FILE__ ) . 'assets/js/easy-table-manager-options.js', array('jquery') );
		wp_localize_script( 'easy_table_manager_options', 'easy_table_manager_options', $params);
		wp_enqueue_script( 'easy_table_manager_options' );
	}

	public function etmo_setup_ajax_handlers() {
		add_action( 'wp_ajax_etmo_generateSchemaAttributesOptions', array( $this, 'etmo_generateSchemaAttributesOptions' ) );
		add_action( 'wp_ajax_nopriv_etmo_generateSchemaAttributesOptions', array( $this, 'etmo_generateSchemaAttributesOptions' ) );

		add_action( 'wp_ajax_etmo_saveRelatedFields', array( $this, 'etmo_saveRelatedFields' ) );
		add_action( 'wp_ajax_nopriv_etmo_saveRelatedFields', array( $this, 'etmo_saveRelatedFields' ) );
	}

	public function easy_table_manager_options() {
		echo "Additional Settings";
	}

	public function etm_related_fields() {
		$data = array();
		$data['this'] =  $this;
		$data['schemas'] =  $this->easy_table_manager_getAllSchemasFromCurrentDB();
		$data['relational_fields_options'] = json_decode(stripslashes(get_option('etm_relational_fields_setting')), true);;
		$data['content'] = $this->easy_table_manager_load('options_edit', $data);
		echo $this->easy_table_manager_load('options_layout', $data);
	}

	public function etmo_generateSchemaAttributesOptions($schema = null, $selected_attr = null) {
		$schema = isset($_GET['schema']) ? $_GET['schema'] : $schema;
		$attributes = $this->getSchemaAttributes($schema);
		$options = "<option value=''>Select Column</option>";
		foreach($attributes as $column_name => $column_details) :
			$selected = isset($selected_attr) && $selected_attr == $column_name ? 'selected' : '';
			$options .= "<option value='$column_name' $selected>$column_name</option>";
		endforeach;
		echo $options;
		return;
	}

	public function etmo_saveRelatedFields() {
		$related_fields = isset($_GET['related_fields']) ? $_GET['related_fields'] : '111';
		update_option("etm_relational_fields_setting", $related_fields);
		exit;
	}
}