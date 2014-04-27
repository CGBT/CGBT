<?php
class toolbox_controller extends base_controller
{
	private $price_min = 800;
	private $price_max = 2000;
	private $invite_model;
	private $start_date = '2012-02-26';

	public function index_action()
	{
		$this->check_login();
		$this->data['selected_nav'] = 'toolbox';
		$this->data['title'] = '工具箱-';
		$forums_user = $this->get_forums_user_data();
		$this->data['user_money'] = $forums_user[$this->setting['forums_money_field']];
		$this->data['user_extcredits1'] = $this->user['extcredits1'];
		$this->data['current_invite_price'] = $this->calc_current_invite_price();
		$this->show('toolbox.php');
	}

	public function get_forums_user_data()
	{
		cg::load_model('forums_discuzx_model');
		$forums_discuzx_model = forums_discuzx_model::get_instance();
		return $forums_discuzx_model->get_user_info($this->username);
	}


	/**
	 * 检查是否可以兑换上传流量
	 * 共享率低于标准才可以，
	 * 不启用共享率限制的话不可兑换，下载小于20G不可兑换
	 * /torrents/id/download/里面也有类似函数
	 * 差别在于下载小于20G的处理
	 */
	private function check_allow_touploaded()
	{
		if (!$this->setting['enable_ratio_limit'])
		{
			return false;
		}
		$downloaded = $this->user['downloaded'];
		$G = 1024 * 1024 * 1024;
		if ($downloaded < 20 * $G)
		{
			return false;
		}
		$dict_ratio_limit = array();
		$rows = funcs::explode($this->setting['ratio_limit']);
		foreach ($rows as $row)
		{
			list($key, $limit) = funcs::explode($row, ':');
			$dict_ratio_limit[$key] = $limit;
		}
		$ratio = $this->user['ratio'];
		$ratio_limit = 0;
		foreach ($dict_ratio_limit as $key => $value)
		{
			if ($downloaded > $key * $G)
			{
				$ratio_limit = $value;
				break;
			}
		}

		if ($ratio < $ratio_limit)
		{
			return true;
		}
		return false;
	}

	public function extcredits12uploaded_action()
	{
		$extcredits12uploaded_need_extcredits1 = intval($this->setting['extcredits12uploaded_need_extcredits1']);
		if ($extcredits12uploaded_need_extcredits1 <= 0)
		{
			$this->showmessage('保种积分兑换虚拟上传流量功能暂未开通', true);
		}
		if (!$this->check_allow_touploaded())
		{
			$this->showmessage('仅共享率过低的受限用户可以兑换虚拟上传流量', true);
		}

		$extcredits1 = isset($this->post['extcredits1']) ? intval($this->post['extcredits1']) : 0;
		if ($extcredits1 <= 0)
		{
			$this->showmessage('保种积分输入错误', true);
		}
		$user_extcredits1 = intval($this->user['extcredits1']);

		if ($extcredits1 < $extcredits12uploaded_need_extcredits1)
		{
			$this->showmessage("请输入正确的保种积分! 最少需要 $extcredits12uploaded_need_extcredits1 保种积分。");
		}
		elseif ($extcredits1 > $user_extcredits1)
		{
			$this->showmessage("您没有这么多保种积分!");
		}
		elseif ($extcredits1 > $this->setting['extcredits12uploaded_max'])
		{
			$this->showmessage("您每次可兑换的保种积分上限为 {$this->setting['extcredits12uploaded_max']} ");
		}

		//得到上一次和本次更新日期
		if ($this->setting['extcredits12uploaded_days_interval'] > 0)
		{
			cg::load_model('logs_credits_model');
			$logs_credits_model = logs_credits_model::get_instance();
			$last_time = intval($logs_credits_model->last_extcredits12uploaded_time($this->uid));
			if ($last_time > 0)
			{
				//比较两个时间
				$days_interval = intval(($this->timestamp - $last_time) / 86400);
				if ($days_interval < $this->setting['extcredits12uploaded_days_interval'])
				{
					$this->showmessage("您在 {$this->setting['extcredits12uploaded_days_interval']} 天内只能兑换1次。");
				}
			}
		}

		$uploaded = intval($extcredits1 / $extcredits12uploaded_need_extcredits1);
		$extcredits1 = $uploaded * $extcredits12uploaded_need_extcredits1;

		cg::load_module('users_module');
		$users_module = users_module::get_instance();

		$logs_fields = array(
			'count' => -1 * $extcredits1,
			'field' => 'extcredits1',
			'action' => 'extcredits12uploaded2'
		);
		$logs_fields = array_merge($logs_fields, $this->logs_credits_fields);
		$users_module->add_credits($this->uid, -1 * $extcredits1, 'extcredits1', $logs_fields);

		$logs_fields = array(
			'count' => $uploaded,
			'field' => 'uploaded2',
			'action' => 'extcredits12uploaded2'
		);
		$logs_fields = array_merge($logs_fields, $this->logs_credits_fields);
		$users_module->add_credits($this->uid, $uploaded, 'uploaded2', $logs_fields);

		$msg = "操作成功， $extcredits1 保种积分兑换为 $uploaded G 虚拟上传流量 !";
		$this->send_pm('', $this->username, '', $msg);
		$this->showmessage($msg, false);
	}

	public function money2uploaded_action()
	{
		$money2uploaded_need_money = intval($this->setting['money2uploaded_need_money']);
		if ($money2uploaded_need_money <= 0)
		{
			$this->showmessage('金币兑换虚拟上传流量功能暂未开通', true);
		}
		if (!$this->check_allow_touploaded())
		{
			$this->showmessage('仅共享率过低的受限用户可以兑换虚拟上传流量', true);
		}


		$forums_money_field = $this->setting['forums_money_field'];
		if (empty($forums_money_field))
		{
			$this->showmessage('金币兑换虚拟上传流量功能暂未开通', true);
		}

		$money = isset($this->post['money']) ? intval($this->post['money']) : 0;
		if ($money <= 0)
		{
			$this->showmessage('金币数量错误', true);
		}
		$forums_user = $this->get_forums_user_data();
		$user_money = intval($forums_user[$forums_money_field]);

		if ($money < $money2uploaded_need_money)
		{
			$this->showmessage("请输入正确的金币数量! 最少需要 $money2uploaded_need_money 个金币。");
		}
		elseif ($money > $user_money)
		{
			$this->showmessage("您没有这么多金币!");
		}
		elseif ($money > $this->setting['money2uploaded_max'])
		{
			$this->showmessage("您每次可兑换的金币上限为 {$this->setting['money2uploaded_max']} ");
		}

		//得到上一次和本次更新日期
		if ($this->setting['money2uploaded_days_interval'] > 0)
		{
			cg::load_model('logs_credits_model');
			$logs_credits_model = logs_credits_model::get_instance();
			$last_time = intval($logs_credits_model->last_money2uploaded_time($this->uid));
			if ($last_time > 0)
			{
				//比较两个时间
				$days_interval = intval(($this->timestamp - $last_time) / 86400);
				if ($days_interval < $this->setting['money2uploaded_days_interval'])
				{
					$this->showmessage("您在 {$this->setting['money2uploaded_days_interval']} 天内只能兑换1次。");
				}
			}
		}

		$uploaded = intval($money / $money2uploaded_need_money);
		$money = $uploaded * $money2uploaded_need_money;

		$forums_discuzx_model = forums_discuzx_model::get_instance();
		$forums_discuzx_model->add_credits($forums_money_field, -1 * $money, $this->user['forums_uid']);

		cg::load_module('users_module');
		$users_module = users_module::get_instance();

		$logs_fields = array(
			'count' => -1 * $money,
			'field' => 'money',
			'action' => 'money2uploaded2'
		);
		$logs_fields = array_merge($logs_fields, $this->logs_credits_fields);
		$users_module->add_credits($this->uid, -1 * $money, 'money', $logs_fields);

		$logs_fields = array(
			'count' => $uploaded,
			'field' => 'uploaded2',
			'action' => 'money2uploaded2'
		);
		$logs_fields = array_merge($logs_fields, $this->logs_credits_fields);
		$users_module->add_credits($this->uid, $uploaded, 'uploaded2', $logs_fields);


		$msg = "操作成功， $money 金币 兑换为 $uploaded G 虚拟上传流量 !";
		$this->send_pm('', $this->username, '', $msg);
		$this->showmessage($msg, false);
	}

	private function calc_current_invite_price()
	{
		cg::load_model('invite_model');
		$invite_model = invite_model::get_instance();
		$total_invite_count = $invite_model->count();
		$avg = intval($total_invite_count / (($this->timestamp - strtotime($this->start_date)) / 86400));
		$in24hours = $this->timestamp - 86400;
		$today_count = $invite_model->count("createtime > '$in24hours'");
		if ($avg == 0)
		{
			$avg = 1;
		}
		$current_price = intval($this->price_min + $today_count * ($this->price_max - $this->price_min) / $avg);
		$current_price = min($current_price, $this->price_max);
		$current_price = max($current_price, $this->price_min);

		if ($this->user['class'] == 12)
		{
			$current_price = 0;
		}
		return $current_price;
	}
}
