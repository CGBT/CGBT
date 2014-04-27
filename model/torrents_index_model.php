<?php
class torrents_index_model extends base_model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->table('torrents_index');
	}

	/**
	 *
	 * @return torrents_index_model
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

	public function get_same_size_tids($size)
	{
		$size = floatval($size);
		$sql = "select id from $this->table where size='$size' order by id desc";
		return $this->db()->get_cols($sql);
	}

	public function get_total_size($is_seeder = '1')
	{
		$sql = "select sum(size) from $this->table";
		if ($is_seeder == '1')
		{
			$sql .= " where seeder > 0";
		}
		return $this->db()->get_value($sql);
	}

	public function get_count_by_uid($uid)
	{
		$cache_key = 'torrents_count_' . $uid;
		return $this->get_cache_data('_' . __FUNCTION__, $cache_key, $uid, 60);
	}

	public function _get_count_by_uid($uid)
	{
		$uid = intval($uid);
		$sql = "select count(1) from $this->table where uid = '$uid'";
		return $this->db()->get_count($sql);
	}

	public function get_count($where)
	{
		$cache_key = 'torrents_index_count_' . md5($where);
		return $this->get_cache_data('_' . __FUNCTION__, $cache_key, $where, 120);
	}

	public function _get_count($where)
	{
		$sql = "select count(1) from $this->table where $where";
		return $this->db()->get_count($sql);
	}

	public function get_ids_by_sql($where, $orderby, $start, $limit)
	{
		$cache_key = 'torrents_index_ids' . md5($where . $orderby . $start . $limit);
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

	public function get_torrents_by_sql($where, $orderby, $start, $limit)
	{
		$cache_key = 'torrents_index_rows' . md5($where . $orderby . $start . $limit);
		$params = array(
			$where,
			$orderby,
			$start,
			$limit
		);
		$ids = $this->get_cache_data('_get_ids_by_sql', $cache_key, $params, 60);
		return $this->get_rows_by_ids($ids);
	}

	public function get_ids_by_imdb($imdb)
	{
		if (empty($imdb))
		{
			return array();
		}
		$sql = "select id from $this->table where imdb='$imdb'";
		return $this->db()->get_cols($sql);
	}

	public function all_upload_count()
	{
	    $cache_key = 'all_upload_count';
	    return $this->get_cache_data('_all_upload_count', $cache_key, '', 300);
	}

	public function _all_upload_count()
	{
	    $sql = "select category, count(1) c from $this->table group by category";
	    $rows = $this->db()->get_rows($sql);
	    $data = array();
	    foreach ($rows as $row)
	    {
	        $data[$row['category']] = $row['c'];
	    }
	    return $data;
	}

	public function today_upload_count()
	{
		$cache_key = 'today_upload_count';
		return $this->get_cache_data('_today_upload_count', $cache_key, '', 300);
	}

	public function _today_upload_count()
	{
		$starttime = strtotime(date("Y-m-d"));
		$sql = "select category, count(1) c from $this->table where createtime > '$starttime' group by category";
		$rows = $this->db()->get_rows($sql);
		$data = array();
		foreach ($rows as $row)
		{
			$data[$row['category']] = $row['c'];
		}
		return $data;
	}

	public function get_ids_range($where)
	{
		$sql = "select id from $this->table where $where";
		return $this->db()->get_cols($sql);
	}

	public function top_tids($type)
	{
		$sql = "select id from $this->table order by $type desc limit 100";
		return $this->db()->get_cols($sql);
	}
}