<?php
/**
 * event_registration模块微站定义
 *
 * @author hc88888888
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Event_registrationModuleSite extends WeModuleSite {

    public function doMobileIndex() {
        //这个操作被定义用来呈现 功能封面
        global $_W, $_GPC;
        include $this->template('index');
    }

    public function doWebIndex() {
        //这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        include $this->template('index');
    }

    public function doWebList() {
        //这个操作被定义用来呈现 管理中心导航菜单
    }

    public function doWebDetails() {
        //这个操作被定义用来呈现 管理中心导航菜单
    }


}