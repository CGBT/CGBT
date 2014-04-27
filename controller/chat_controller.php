<?php
class chat_controller extends base_controller
{


	/**
	 *
	 * /chat/?room=xxx  page
	 * /chat/say/       json
	 * /chat/ban/       json
	 * /chat/del/       json
	 *
	 * /chat/create/    page
	 * /chat/list/      page
	 * /chat/history/?room=xx
	 *
	 */
	private $room = '';
	private $max_record = 300; //读取的最多消息数量;
	private $all_ban_user, $is_ban_user;
	private $users_module, $chat_model;
	private $counter = 0;

	public function beforeRun($resource, $action, $module_name = '')
	{
		parent::beforeRun($resource, $action, $module_name);

		cg::load_module('users_module');
		$this->users_module = new users_module();
		cg::load_model('chat_model');
		$this->chat_model = chat_model::get_instance();

		$this->get_setting();
		$this->room = isset($this->get['room']) ? substr($this->get['room'], 0, 20) : 'all_';
		//require_once 'include/discuzcode.func.php';
		if (empty($this->user))
		{
			$this->user["username"] = "guest" . substr(str_replace(".", "", str_replace(":", "", $this->ip)), -6);
			$this->user["uid"] = 0;
			$this->user["class"] = 0;
			$this->user['title'] = $this->user['username'];
			$this->user['is_admin'] = false;
			$this->user['is_moderator'] = false;
			$this->data['user'] = $this->user;
		}

		$this->all_ban_user = $this->cache()->get("chat_ban_user");
		if (empty($this->all_ban_user))
		{
			$this->all_ban_user = array();
		}
		if (!empty($this->all_ban_user) && in_array($this->user["username"], $this->all_ban_user))
		{
			$this->is_ban_user = true;
		}
	}

	public function get_action()
	{
		$this->output();
	}

	private function output($action = '')
	{
		if ($this->is_ban_user)
		{
			$data["action"] = "ban";
			die(json_encode($data));
		}

		$html = "";
		if ($this->counter == 0)
		{
			$this->counter = intval($this->chat_model->max_id());
		}
		$start = isset($this->post['start']) ? intval($this->post['start']) : 0;
		if ($this->counter - $start > $this->max_record || $this->counter < $start || $action == 'refresh')
		{
			$start = $this->counter - $this->max_record;
		}
		$cache_keys = array();
		for($i = $start + 1; $i <= $this->counter; $i++)
		{
			$cache_keys[] = $this->room . '_' . $i;
		}
		if (empty($cache_keys))
		{
			$rows = array();
		}
		else
		{
			$rows = $this->cache()->get($cache_keys);
		}
		ksort($rows);
		foreach ($rows as $key => $row)
		{
			if (empty($row) || empty($row['txt']))
			{
				unset($rows[$key]);
			}
			$row["txt"] = funcs::ubb2html($row["txt"]);

			if (!empty($row["user_title"]))
			{
				$display_name = $row["user_title"];
			}
			else
			{
				$display_name = $row["username"];
			}
			$html .= "<span style='color:#ccc'>$row[createtime]</span> ";
			if ($this->user["is_moderator"] || $this->user['is_admin'])
			{
				$html .= "[<span style='cursor:pointer;color:blue;'><span onclick=\"del('$row[id]');\">x</span> <span onclick=\"ban('$row[username]');\">b</span> <a target='_blank' href='/user/$row[uid]/'>u</a></span>] ";
			}
			$html .= "<span onclick=\"reply_user('$display_name');\" style='cursor:pointer;'>$display_name: </span>&nbsp;&nbsp;&nbsp;&nbsp;$row[txt]<br />\n";
		}
		$result["txt"] = $html;
		$result["action"] = $action;
		$result["start"] = $this->counter;
		die(json_encode($result));
	}

	public function say_action()
	{
		$txt = isset($this->post['txt']) ? $this->post['txt'] : '';
		if ($this->is_ban_user)
		{
			$this->output();
			die();
		}
		if (!empty($txt))
		{
			//$txt = htmlspecialchars($txt, ENT_QUOTES);
			$arr_fields = array();
			$arr_fields["createtime"] = $this->timestamp;
			$arr_fields["user_title"] = $this->user["title"];
			$arr_fields["username"] = $this->user["username"];
			$arr_fields["uid"] = $this->user["uid"];
			$arr_fields["class"] = $this->user["class"];
			$arr_fields["ip"] = $this->ip;
			$arr_fields["txt"] = $txt;
			$arr_fields["room"] = $this->room;
			$this->counter = $this->chat_model->insert($arr_fields);
			$arr_fields["id"] = $this->counter;
			$arr_fields["createtime"] = date("H:i:s", $arr_fields["createtime"]);
			$this->cache()->set($this->room . "_" . $this->counter, $arr_fields, 86400);
		}
		$this->output();
	}

	public function unban_action()
	{
		if (!$this->user["is_moderator"] && !$this->user['is_admin'])
		{
			return;
		}
		$ban_user = isset($this->get['ban_user']) ? $this->get['ban_user'] : '';
		if (empty($ban_user))
		{
			return;
		}

		$index = array_search($ban_user, $this->all_ban_user);
		if ($index !== false)
		{
			unset($this->all_ban_user[$index]);
			$this->cache()->set("chat_ban_user", $this->all_ban_user, 86400);
			die("unban user: $ban_user done!");
		}
	}

	public function ban_action()
	{
		if (!$this->user["is_moderator"] && !$this->user['is_admin'])
		{
			return;
		}

		$ban_user = isset($this->post['ban_user']) ? $this->post['ban_user'] : '';
		if (empty($ban_user))
		{
			return;
		}

		if (in_array($ban_user, $this->all_ban_user))
		{
			return;
		}

		$user = $this->users_module->get_by_username($ban_user);
		if (!empty($user) && ($user['is_moderator'] || $user['is_admin']))
		{
			die("can't ban $ban_user !");
		}
		else
		{
			$this->all_ban_user[] = $ban_user;
			$this->cache()->set("chat_ban_user", $this->all_ban_user, 86400);

			$txt = "[color=red]" . $ban_user . '已被封禁发言!' . "[/color]";
			$arr_fields = array();
			$arr_fields["createtime"] = $this->timestamp;
			$arr_fields["user_title"] = '系统消息';
			$arr_fields["username"] = $this->username;
			$arr_fields["uid"] = $this->uid;
			$arr_fields["class"] = '100';
			$arr_fields["ip"] = $this->ip;
			$arr_fields["txt"] = $txt;
			$arr_fields["room"] = $this->room;
			$this->counter = $this->chat_model->insert($arr_fields);
			$arr_fields["id"] = $this->counter;
			$arr_fields["createtime"] = date("H:i:s", $arr_fields["createtime"]);
			$this->cache()->set($this->room . "_" . $this->counter, $arr_fields, 86400);
			die("ban user: $ban_user done!");
		}
		$this->output();
	}

	public function del_action()
	{
		if (!$this->user["is_moderator"] && !$this->user['is_admin'])
		{
			return;
		}
		$id = isset($this->post['id']) ? intval($this->post['id']) : 0;
		if ($id <= 0)
		{
			return;
		}
		$this->cache()->delete($this->room . "_" . $id);
		$arr_fields = array(
			'status' => '-1'
		);
		$this->chat_model->update($arr_fields, $id);
		$this->output('refresh');
	}

	public function index_action()
	{
		$this->show('chat.php');
	}

	private function get_smilies()
	{
		global $smilies, $smilies_dir;
		$table = "<table style='margin:0 auto;'>";
		$count = count($smilies);
		for($i = 0; $i < $count; $i++)
		{
			$table .= "<tr>";
			for($j = 0; $j < 9; $j++)
			{
				$i++;
				if ($i < $count)
				{
					$table .= "<td><img src='$smilies_dir/" . $smilies[$i][2] . "' style='width:30px;height:30px;cursor:pointer' onclick=\"insert_smilies(' " . $smilies[$i][1] .
					 "')\"></td>";
				}
			}
			$table .= "</tr>\n";
		}
		$table .= "</table>";
		return $table;
	}

	private function convert_smilies($txt)
	{
		global $smilies, $smilies_dir;
		$smilies_searcharray = array();
		$smilies_replacearray = array();

		foreach ($smilies as $smiley)
		{
			$smilies_searcharray[$smiley[0]] = "/" . $smiley[1] . "/i";
			$smilies_replacearray[$smiley[0]] = '<img style="width:30px;height:30px;" src="' . $smilies_dir . '/' . $smiley[2] . '" />';
		}
		return preg_replace($smilies_searcharray, $smilies_replacearray, $txt, 3);
	}
}
