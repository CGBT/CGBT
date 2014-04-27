<?php
class torrents_stat_model extends base_model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->table('torrents_stat');
	}

	/**
	 *
	 * @return torrents_stat_model
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

	public function update_stat()
	{
		$sql = "update $this->table a set
				a.seeder = (select count(1) from cgbt_peers b where b.is_seeder=1 and b.tid = a.id),
                a.leecher = (select count(1) from cgbt_peers b where b.is_seeder=0 and b.tid = a.id)";
		$this->db()->query($sql);
	}
}