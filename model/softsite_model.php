<?php
class softsite_model extends base_model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->table('softsite');
	}

	/**
	 *
	 * @return softsite_model
	 */
	public static function get_instance()
	{
		static $instance;
		$name = __CLASS__;
		if (!isset($instance[$name]))
		{
			$instance[$name] = new $name();
		}
		return $instance[$name];
	}

	public function get_count($where)
	{
		$cache_key = 'softsite_count_' . md5($where);
		return $this->get_cache_data('_' . __FUNCTION__, $cache_key, $where, 120);
	}

	public function _get_count($where)
	{
		$sql = "select count(1) from $this->table";
		if (!empty($where))
		{
			$sql .= " where $where";
		}
		return $this->db()->get_count($sql);
	}

	public function get_ids_by_sql($where, $orderby, $start, $limit)
	{
		$cache_key = 'softsite_ids' . md5($where . $orderby . $start . $limit);
		$params = array(
			$where,
			$orderby,
			$start,
			$limit
		);
		return $this->get_cache_data('_' . __FUNCTION__, $cache_key, $params, 60);
	}

	protected function _get_ids_by_sql($params)
	{
		list($where, $orderby, $start, $limit) = $params;
		if (!empty($where))
		{
			$where = 'where ' . $where;
		}
		if (!empty($orderby))
		{
			$orderby = 'order by ' . $orderby;
		}
		$sql = 'select id from ' . $this->table . " $where $orderby limit $start, $limit";
		//echo $sql;
		$rows = $this->db()->get_rows($sql);

		$ids = array();
		foreach ($rows as $row)
		{
			$ids[] = $row['id'];
		}
		return $ids;
	}
}