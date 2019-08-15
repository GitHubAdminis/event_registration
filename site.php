<?php
/**
 * event_registration模块微站定义
 *
 * @author hc88888888
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Event_registrationModuleSite extends WeModuleSite {

    // 前台方法
    public function doMobileIndex() {
        // 这个操作被定义用来呈现 功能封面
        global $_W, $_GPC;
        echo "<a href='" . $this->createMobileUrl('index') . "'>clickme</a>";
        echo "<a href='google.com'>clickme</a>";
        echo '<h1>Hello World!</h1>';
        include $this->template('index');
    }

    public function doMobileWelcome() {
        // 这个操作被定义用来呈现 功能封面
        global $_W, $_GPC;
        echo $_W;
        include $this->template('welcome');
    }


    // 后台方法
    public function doWebIndex() {
        // 这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        include $this->template('index');
    }

    public function doWebList() {
        // 这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        include $this->template('list');
    }

    public function doWebDetails() {
        // 这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        include $this->template('details');
    }


}