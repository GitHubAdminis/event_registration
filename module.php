<?php
/**
 * event_registration模块定义
 *
 * @author hc88888888
 * @url ww
 */
defined('IN_IA') or exit('Access Denied');

class Event_registrationModule extends WeModule {


	public function welcomeDisplay($menus = array()) {
		//这里来展示DIY管理界面
		include $this->template('welcome');
	}
}