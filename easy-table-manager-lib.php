<?php
Class EasyTableManagerLib
{
	public $view_file_location = "views/"; // note: include trailing slash

	public function easy_table_manager_load($filename, $param) {
		ob_start();
		extract($param);
		include($this->view_file_location.$filename.".php");
		return $output = ob_get_clean();
	}

	public function easy_table_manager_getAllSchemasFromCurrentDB() {
		$cur_db = DB_NAME;
		$query = "select table_name from information_schema.tables where TABLE_SCHEMA='$cur_db'";
		$results = $this->db->get_results($query, ARRAY_A);
		$schemas = array();
		foreach($results as $result) {
			$schemas[] = $result['table_name'];
		}
		return $schemas;
	}

	function getSchemaAttributes($schema) { 
		$query = "SHOW COLUMNS FROM `$schema`";
		$results = $this->db->get_results($query);
		$attr = array();
		foreach($results as $result) {
			$attr[$result->Field] = array('Type' => $result->Type, 'Null' => $result->Null, 'Default' => $result->Default);
		}
		return $attr;
	}

}