<?php

class oldusers_model extends base_model
{

	public function __construct()
	{
		$this->db_config_name = 'oldcgbt';
		$this->table = "users";
		$this->pk = "id";
	}

	public function get_all_count()
	{
		$sql = "select count(1) from $this->table";
		return $this->db()->get_count($sql);
	}

	public function get_users($start, $limit)
	{
		$sql = "select * from $this->table where upgrade_imported = 0 order by id limit $start,$limit";
		return $this->db()->get_rows($sql);
	}

	public function update_imported_flag($pk_id)
	{
		$arr_fields = array();
		$arr_fields['upgrade_imported'] = 1;
		$this->update($arr_fields, $pk_id);
	}


}
