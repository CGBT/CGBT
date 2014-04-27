<?php

class oldtorrents_model extends base_model
{

	public function __construct()
	{
		$this->db_config_name = 'oldcgbt';
		$this->table = "torrents";
		$this->pk = "id";
	}

	public function get_count_by_main_cid($main_cid)
	{
		$sql = "select count(1) from $this->table where main_cid = '$main_cid'";
		return $this->db()->get_count($sql);
	}

	public function get_torrents_by_main_cid($main_cid, $start, $limit)
	{
		$sql = "select * from $this->table where main_cid = '$main_cid' and upgrade_imported = 0 order by id  desc limit $start,$limit";
		return $this->db()->get_rows($sql);
	}

	public function get_count_by_cid($cid)
	{
		$sql = "select count(1) from $this->table where category = '$cid'";
		return $this->db()->get_count($sql);
	}
	public function get_torrents_by_cid($cid, $start, $limit)
	{
		$sql = "select * from $this->table where category = '$cid' and upgrade_imported = 0 order by id  desc limit $start,$limit";
		return $this->db()->get_rows($sql);
	}

	public function update_imported_flag($pk_id)
	{
		$arr_fields = array();
		$arr_fields['upgrade_imported'] = 1;
		$this->update($arr_fields, $pk_id);
	}

	public function get_category_stat()
	{
		$sql = "select main_cid,count(1) as count from torrents group by main_cid";
		return $this->db()->get_rows($sql);
	}

}
