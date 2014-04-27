<?php
class logs_browse_model extends base_model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->table('logs_browse');
		$this->pk = 'id';
	}


	/**
	 *
	 * @return logs_browse_model
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

	public function get_ids_range($where)
	{
		$sql = "select tid from $this->table where $where order by id desc limit 100";
		return $this->db()->get_cols($sql);
	}
}