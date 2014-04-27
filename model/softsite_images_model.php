<?php
class softsite_images_model extends base_model
{

	public function __construct()
	{
		parent::__construct();
		$this->table = $this->table('softsite_images');
		$this->pk = 'aid';
	}

	public function update_softsiteid_by_guid($softsiteid, $guid)
	{
		$guid = $this->db()->real_escape_string($guid);
		if (strlen($guid) != 36)
		{
			return;
		}
		$sql = "update $this->table set softsiteid = '$softsiteid' where guid='$guid'";
		$this->db()->query($sql);
	}

	/**
	 *
	 * @return softsite_images_model
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

	public function delete_no_softsiteid_images()
	{
		$sql = "delete from  $this->table where softsiteid=0";
		$this->db()->query($sql);
	}
}