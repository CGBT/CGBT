<?php
class users_stat_model extends base_model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->table('users_stat');
		$this->pk = 'uid';
	}

	/**
	 *
	 * @return users_stat_model
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

	public function get_user_group_count()
	{
		$function_name = '_' . __FUNCTION__;
		$cache_key = "user_group_count";
		return $this->get_cache_data($function_name, $cache_key, '', 7200);
	}

	public function _get_user_group_count()
	{
		$sql = "select class,count(1) as count from $this->table group by class";
		return $this->db()->get_rows($sql);
	}

	public function get_staff_uids()
	{
		$function_name = '_' . __FUNCTION__;
		$cache_key = "staff_uids";
		return $this->get_cache_data($function_name, $cache_key, '', 7200);
	}

	public function _get_staff_uids()
	{
		$sql = "select uid from $this->table where class > 10 order by class desc, uid";
		return $this->db()->get_cols($sql);
	}

	public function add_credits($uid, $count, $field)
	{
		$sql = "update $this->table set $field = $field + $count where uid = '$uid'";
		$this->db()->query($sql);

		$this->push_cache_status();
		$this->db()->push_slave_status();
		$this->find($uid);
		$this->db()->pop_slave_status();
		$this->pop_cache_status();
	}

	public function fail_newbie_task_uids($starttime, $endtime, $uploaded, $extcredits1)
	{
		$sql = "select uid from $this->table where createtime > '$starttime' and createtime < '$endtime' and extcredits1 < '$extcredits1' and uploaded < '$uploaded'";
		return $this->db()->get_cols($sql);
	}

	public function get_pass_kaohe_users($uids, $uploaded, $downloaded, $extcredits1)
	{
		$G = 1024 * 1024 * 1024;
		$uploaded = $uploaded * $G;
		$downloaded = $downloaded * $G;
		$sql = "select uid,username,createtime, case when uploaded > '$uploaded' and downloaded > '$downloaded' and extcredits1 > '$extcredits1'
		          then 1 else 0 end as all_pass from $this->table where uid in ($uids)";
		return $this->db()->get_rows($sql);
	}

	public function tracker_update($set, $uid)
	{
		$sql = "update $this->table set $set where uid='$uid'";
		$this->db()->query($sql);

		$this->push_cache_status();
		$this->db()->push_slave_status();
		$this->find($uid);
		$this->db()->pop_slave_status();
		$this->pop_cache_status();
	}

	public function top_uids($type)
	{
		$sql = "select uid from $this->table order by $type desc limit 100";
		return $this->db()->get_cols($sql);
	}
}