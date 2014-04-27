<?php
class admin_base_controller extends base_controller
{
	public $all_admin_privileges = array();

	public function beforeRun($resource, $action, $module = '')
	{
		parent::beforeRun($resource, $action, $module);
		$this->check_login();
		$this->check_admins();
		//$this->init(); //@hulupiao...
		$this->init_privilages();
		$this->data['title'] = '后台管理-';
		$this->check_privileges();
	}

	private function is_founder()
	{
		return $this->username == cg::config()->config['system_founder'];
	}

	public function check_admins()
	{
		if ($this->is_founder())
		{
			return;
		}
		if (in_array($this->username, funcs::explode($this->setting['admins_admins'])))
		{
			return;
		}
		$this->showmessage('没有权限');
	}

	public function init_privilages()
	{
		$all_admin_privileges = cg::config()->config['admin_privileges'];
		$data = array();
		foreach ($all_admin_privileges as $key => $menu)
		{
			$data[$key]['name'] = $menu['name'];
			$data[$key]['child'] = $this->parse_privileges($menu['child']);
		}
		unset($all_admin_privileges);
		$this->data['all_admin_privileges'] = $data;
	}

	private function parse_privileges($child)
	{
		$data = array();
		foreach ($child as $key => $line)
		{
			$arr_line = explode('|', $line);
			$data[$key]['controller'] = $arr_line[0];
			$data[$key]['action'] = $arr_line[1];
			$data[$key]['name'] = $arr_line[2];
			$data[$key]['is_menu'] = $arr_line[3];
		}
		return $data;
	}

	private function check_privileges()
	{
		$user_menu = array();
		foreach ($this->data['all_admin_privileges'] as $key => $menu)
		{
			$have_child = false;
			foreach ($menu['child'] as $key2 => $privilege)
			{
				$p = $privilege['controller'] . '/' . $privilege['action'];
				if ($this->user['admin_privileges'][$p])
				{
					$have_child = true;
				}
			}
			if ($have_child)
			{
				$user_menu[$key]['name'] = $menu['name'];
			}

			foreach ($menu['child'] as $key2 => $privilege)
			{
				$p = $privilege['controller'] . '/' . $privilege['action'];
				if ($this->user['admin_privileges'][$p])
				{
					if ($this->controller_name == 'admin_' . $privilege['controller'] . '_controller')
					{
						$this->data['current_ctr'] = $privilege['controller'];
						$this->data['ctr_id'] = $key;
					}

					if ($this->controller_name == 'admin_' . $privilege['controller'] . '_controller' && $this->action_name == $privilege['action'] . '_action')
					{
						$this->data['act_id'] = $key2;
					}
				}

				if ($this->user['admin_privileges'][$p] && $privilege['is_menu'] && $have_child)
				{
					$user_menu[$key]['child'][$key2] = $privilege;
				}
			}
		}

		$this->data['user_menu'] = $user_menu;

		//判断如果访问没权限的模块
		if (!isset($this->data['ctr_id']))
		{
			$this->error('您无权限访问此功能');
		}
	}

	/**
	 * ajax 响应接口
	 * @param unknown_type $rs
	 * @param unknown_type $error
	 * @param unknown_type $error_msg
	 */
	public function json_response($rs = '', $error = 0, $error_msg = '')
	{
		$result = array(
			'error' => $error,
			'error_msg' => $error_msg,
			'rs' => $rs
		);
		echo json_encode($result);
		exit();
	}

	/**
	 * 错误信息页面
	 * @param  string $error_msg 错误信息
	 * @return NULL
	 */
	public function error($error_msg)
	{
		echo $error_msg;
		exit();
	}
}