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
//        echo "http://we7.think2009.com/app/" . $this->createMobileUrl('index') . '<br/>';
//        echo "<a href='" . $this->createMobileUrl('index') . "'>MobileUrl</a>";
//        echo "<a href='" . $this->createWebUrl('details') . "'>WebUrl</a>";

        // 创建路由
//        echo 'MODULE_URL' . MODULE_URL . '<br/>';
//        var_dump($_GPC) . '<br/><br><br>';


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


        /*        数据库 增删改查
                $sql = "INSERT INTO apply_vip_user (id, category_name, create_time) VALUES ('11', 'Nginx', 'john@example.com')";
                $sql = "delete from apply_vip_user where id = 8";
                $sql = "UPDATE apply_vip_user set category_name = 'MySql Tutorial' where id = 9";
                $sql = "select * from ims_apply_vip_user";*/

        /*        if ($conn->query($sql) == TRUE) {
                    echo "New record inserted successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $res = $conn->query($sql);

                echo json_encode($res);*/
        /* $arr = array();
         if ($res->num_rows > 0) {
             while ($rows = $res->fetch_assoc()) {
                 $arr[] = $rows;  // [{}, {}, {}]
                 array_push($arr, $rows);
             }
         }*/
        /*        echo json_encode($arr);
                echo json_encode($arr['id']);
                var_dump(json_encode($arr['id']));


                $category = array('category_name' => 'php video', 'create_time' => 'fe');
                $result = pdo_insert("apply_vip_user", $category, $replace = false);
                var_dump($result);*/

//        $userinfo = mc_oauth_userinfo($_W['uniacid']);
//        var_dump($userinfo);
//        $user = pdo_get('apply_vip_user', array('open_id' => $userinfo['open_id']));
//
        /*if (!$user) {
            $to_user = array(
                'open_id' => $userinfo['openid'],
                'nickname' => $userinfo['nickname'],
                'profile_photo' => $userinfo['headimgUrl'],
                'authorize_time' => time()
            );

            pdo_insert('apply_vip_user', $to_user);
        }*/

        // query activity data
        $activity_data_apply = pdo_get('apply_vip_activity', array('status' => 0));
        $activity_data_join = pdo_get('apply_vip_activity', array('status' => 1));

        // query user is activity or not
        $is_activity = pdo_get('apply_vip_order', array('open_id' => $userinfo['openid'], 'status' => 1));

//        $conn->close();

        include $this->template('index');

//        include $this->template('common/footer');
    }


    public function doMobileDetails() {
        // 这个操作被定义用来呈现 功能封面
        include $this->template('common/header');

        global $_W, $_GPC;

        $userinfo = mc_oauth_userinfo($_W['uniacid']);

        // query activity data
        $activity_data_details = pdo_get('apply_vip_activity');
        $activity_data_join = pdo_get('apply_vip_activity', array('status' => 1));

        // query user is activity or not
        $activity_userInfo = pdo_get('apply_vip_user');

        include $this->template('details');
        // include $this->template('common/footer');
    }



    public function doMobileGetPay() {
        global $_W, $_GPC;
        echo 'Welcome to payment page';

        $userinfo = mc_oauth_userinfo($_W['uniacid']); // 获取授权用户信息
        $activity = pdo_get('apply_vip_activity', array('id' => 1));
        $order_num = $this->getOrderNums();
        $order_data = [
            'order_num' => $order_num,
            'open_id' => $userinfo['openid'],
            'username' => $_GPC['username'],
            'phone' => $_GPC['phone'],
            'status' => 0,
            'price' => $activity['price'],
            'create_time' => time()
        ];

        $result = pdo_insert('apply_vip_order', $order_data);

        if ($result) {

            //构造支付请求中的参数
            $params = array(
                'tid' => $order_num,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
                'ordersn' => $order_num,  //收银台中显示的订单号
                'title' => '系统充值余额',          //收银台中显示的标题
                'fee' => $activity['price']      //收银台中显示需要支付的金额,只能大于 0
//                'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
            );

            $this->pay($params);

        }

    }


    public function doMobileCreate_Event() {

        global $_W, $_GPC;

        // include $this->template('common/header');
        if ($_W['ispost']) {
            $to_activity = array(
                'title' => $_GPC['title'],
                'description' => $_GPC['description'],
                'date' => $_GPC['date'],
                'time' => $_GPC['time'],
                'address' => $_GPC['address'],
                'status' => 0,
                'creation_time' => time()
            );

            $result = pdo_insert('apply_vip_activity', $to_activity);

        };

        $userinfo = mc_oauth_userinfo($_W['uniacid']);
//        var_dump($userinfo);
        $user = pdo_get('apply_vip_user', array('open_id' => $userinfo['openid']));

        if (!$user) {
            $to_user = array(
                'open_id' => $userinfo['openid'],
                'nickname' => $userinfo['nickname'],
//                'profile_photo' => $userinfo['avatarUrl'],
//                'profile_photo' => $userinfo['headimgUrl'],
                'profile_photo' => $userinfo['avatar'],
                'authorize_time' => time(),
                'sex' => $userinfo['sex'],
                'language' => $userinfo['language'],
                'city' => $userinfo['city'],
                'province' => $userinfo['province'],
                'country' => $userinfo['country'],
                'subscribe_time' => time(),
                'mobile' => $userinfo['mobile'],
                'realname' => $userinfo['realname'],

            );

            pdo_insert('apply_vip_user', $to_user);
        }

        // query activity data
        $activity_data = pdo_get('apply_vip_activity', array('id' => $_GPC['id']));

        // query user is activity or not
//        $is_activity = pdo_get('apply_vip_order', array('open_id' => $userinfo['openid'], 'status' => 1));


        include $this->template('create_event');
    }


    // 生成随机订单号
    public function getOrderNums() {
        $order_no = date('Ymd') . substr(implode(null, array_map('ord',
                str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return $order_no . mt_rand(1000, 9999);
    }

    public function payResult($params) {

        // loading log function
        load()->func('logging');

        // record test.html log
        logging_run($params);

        // 根据参数params中的result来判断支付是否成功
        if ($params['result'] == 'success' && $params['from'] == 'notify') {

            $up_order = pdo_update('apply_vip_order', array('status' => 1), array('order_num' => $params['tid']));

            if ($up_order) {

            }

            $data = array(
                'first' => array(
                    'value' => '付款成功',
                    'color' => '#ff510'
                ),
                'orderProductPrice' => array(
                    'value' => '1元',

                )
            );

            $url = 'http://we7.think2009.com/app/index.php?i=7&c=entry&eid=136';
        }

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

        $activity_data = pdo_get('apply_vip_activity', array('id' => $_GPC['id']));

        /*if ($_W['ispost']) {
            $up_activity = [
                'id' => $_GPC['id'],
                'title' => $_GPC['title'],
                'content' => $_GPC['content'],
                'price' => $_GPC['price'],
                'custom_share_icon' => $_GPC['custom_share_icon'],
                'custom_share_title' => $_GPC['custom_share_title'],
                'custom_share_describe' => $_GPC['custom_share_describe'],
                'custom_share_website' => $_GPC['custom_share_website'],
                'creation_time' => time()
            ];

            $result = pdo_insert('apply_vip_activity', $up_activity, true);
            if ($result) {
                message('update successfully!', $this->createWebUrl('index'), 'success');
            } else {
                message('The system is error!', $this->createWebUrl('index'), 'error');
            }

        }*/

        include $this->template('activity');
    }


    /*public function doWebDetails() {
        global $_W, $_GPC;
        $userinfo = mc_oauth_userinfo($_W['uniacid']);
        include $this->template('create_event');
    }*/


    public function doWebCreate_Event() {
        echo '123';
        global $_W, $_GPC;
        $userinfo = mc_oauth_userinfo($_W['uniacid']);
        echo '$userinfo' . $userinfo;

        $activity_data = pdo_get('apply_vip_activity', array(id => $_GPC['id']));

        /*if ($_W['ispost']) {
            $up_activity = [
//                'id' => $_GPC['id'],
                'title' => $_GPC['title'],
                'description' => $_GPC['description'],
                'date' => $_GPC['date'],
                'time' => $_GPC['time'],
                'address' => $_GPC['address']
            ];

            $result = pdo_insert('apply_vip_activity', $up_activity, true);
            if ($result) {
                message('update successfully!', $this->createWebUrl('index'), 'success');
            } else {
                message('The system is error!', $this->createWebUrl('index'), 'error');
            }

        }*/

        include $this->template('create_event');
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
