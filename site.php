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
        include $this->template('common/header');

        global $_W, $_GPC;
        echo "http://we7.think2009.com/app/" . $this->createMobileUrl('index') . '<br/>';
        echo "<a href='" . $this->createMobileUrl('index') . "'>MobileUrl</a>";
        echo "<a href='google.com'>google.com</a>";
        echo "<a href='" . $this->createWebUrl('details') . "'>WebUrl</a>";

        $name = 'PhpStorm';
        $arr = ['Vue', 'Angular', 'React'];
        echo '<h1>Hello World!</h1>';

        // 创建路由
        echo 'MODULE_URL' . MODULE_URL . '<br/>';
//        var_dump($_GPC) . '<br/><br><br>';
        echo '<br/>';
//        var_dump($_W) . '<br/>';


        $servername = "39.104.26.166";
        $username = "we7_think2009_c";
        $password = "CyiBWHnY5x";
        $dbname = "we7_think2009_c";

        // 创建连接
        $conn = new mysqli($servername, $username, $password, $dbname);
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }


//        数据库 增删改查
//        $sql = "INSERT INTO apply_vip_user (id, category_name, create_time) VALUES ('11', 'Nginx', 'john@example.com')";
//        $sql = "delete from apply_vip_user where id = 8";
//        $sql = "UPDATE apply_vip_user set category_name = 'MySql Tutorial' where id = 9";
        $sql = "select * from ims_apply_vip_user";

        if ($conn->query($sql) == TRUE) {
            echo "New record inserted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $res = $conn->query($sql);

        echo json_encode($res);
        $arr = array();
        if ($res->num_rows > 0) {
            while ($rows = $res->fetch_assoc()) {
                $arr[] = $rows;  // [{}, {}, {}]
                array_push($arr, $rows);
            }
        }
        echo json_encode($arr);
        echo json_encode($arr['id']);
        var_dump(json_encode($arr['id']));

        $conn->close();


//        $category = array('category_name' => 'php video', 'create_time' => 'fe');
//        $result = pdo_insert("apply_vip_user", $category, $replace = false);
//        var_dump($result);

        $userinfo = mc_oauth_userinfo($_W['uniacid']);
        var_dump($userinfo);
        $user = pdo_get('apply_vip_user', array('open_id' => $userinfo['open_id']));

        if (!$user) {
            $to_user = array(
                'open_id' => $userinfo['openid'],
                'nickname' => $userinfo['nickname'],
                'profile_photo' => $userinfo['headimgUrl'],
                'authorize_time' => time()
            );

            pdo_insert('apply_vip_user', $to_user);
        }


        include $this->template('index');

        include $this->template('common/footer');
    }

    public function doMobileWelcome() {
        // 这个操作被定义用来呈现 功能封面
        global $_W, $_GPC;
        include $this->template('welcome');
    }

    public function doMobileMessageBoard() {
        global $_W, $_GPC;
        include $this->template('');

    }


    // 后台方法
    public function doWebIndex() {
        // 这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        $userinfo = mc_oauth_userinfo($_W['uniacid']);

        $activity_data = pdo_get('apply_vip_activity', array('id'=>1));

        include $this->template('activity');
    }

    public function doWebList() {
        // 这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;

        /*$servername = "39.104.26.166";
        $username = "we7_think2009_c";
        $password = "CyiBWHnY5x";
        $dbname = "we7_think2009_c";

        // 创建连接
        $conn = new mysqli($servername, $username, $password, $dbname);
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }*/

        $sql = "select * from " . tablename('apply_vip_order');
        $sources = pdo_fetchall($sql); // 总结果集数组

        $total = count($sources); // 总记录条数
        $page_index = max($_GPC['page'], 1); // 当前页数
        $page_size = 3; // 单页条数
        $pager = pagination($total, $page_index, $page_size);
        $p = ($page_index - 1) * 3; // 未知
        $sql .= " order by id asc limit " . $p . ", " . $page_size;
        $order_list = pdo_fetchall($sql); // 取出结果集数组

        /*$servername = "39.104.26.166";
        $username = "we7_demo_test";
        $password = "101001";
        $dbname = "we7_demo_test";

        // 创建连接
        $conn = new mysqli($servername, $username, $password, $dbname);
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }

        $sql = 'select * from apply_vip_order';
        $res = $conn->query($sql);
        echo json_encode($res);
        $arr = array();
        if($res->num_rows > 0){
            while($rows = $res->fetch_assoc()){
                $arr[] = $rows;  // [{}, {}, {}]
                array_push($arr,$rows);
            }
        }

        echo json_encode($arr);*/

        include $this->template('order');
    }

    public function doWebDetails() {
        // 这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;

        $sql = "select * from " . tablename('apply_vip_user');
        $sources = pdo_fetchall($sql); // 总结果集数组

        $total = count($sources); // 总记录条数
        $page_index = max($_GPC['page'], 1); // 当前页数
        $page_size = 3; // 单页条数
        $pager = pagination($total, $page_index, $page_size);
        $p = ($page_index - 1) * 3; // 未知
        $sql .= " order by id asc limit " . $p . ", " . $page_size;
        $user_list = pdo_fetchall($sql); // 取出结果集数组

        include $this->template('user');
    }


}