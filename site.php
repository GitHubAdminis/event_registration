<?php

/**
 * event_registration模块微站定义
 *
 * @author hc88888888
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Event_registrationModuleSite extends WeModuleSite
{

    // 引导页前台方法
    public function doMobileIndex()
    {

        global $_W, $_GPC;
        $userinfo = mc_oauth_userinfo($_W['uniacid']);
        $user = pdo_get('vip_activity_user', array('openid' => $userinfo['openid']));
        if (!$user) {
            $to_user = array(
                'openid' => $userinfo['openid'],
                'nickname' => $userinfo['nickname'],
                'avatar' => $userinfo['avatar']
            );
            pdo_insert('vip_activity_user', $to_user);
        }
        $is_hava_activity_apply_data = pdo_get('vip_activity_apply', array('openid' => $userinfo['openid']));
        include $this->template('index');
    }

    // 活动主页
    public function doMobileHome_Page()
    {

        global $_W, $_GPC;
        $userinfo = mc_oauth_userinfo($_W['uniacid']);
        // 我报名的活动
        $sql_apply = "SELECT apply.id as id, apply.aid as aid, act.title, act.description, act.date, act.time, img_url FROM
            ims_vip_activity_apply AS apply
            LEFT JOIN ims_vip_activity AS act ON apply.aid = act.id
            LEFT JOIN ims_vip_activity_category_cover AS cover ON act.img_id = cover.id
            WHERE apply.openid = '$userinfo[openid]'";
        $sources = pdo_fetchall($sql_apply);
        $endData = array();
        foreach ($sources as $key => $value) {

            $sql = "SELECT * from ims_vip_activity_apply as a where a.aid = $value[aid]";
            $source = sizeof(pdo_fetchall($sql));
            array_push($sources[$key], $source);
            // $sources[$key-1]->number = $source;

            array_push($endData, $sources[$key]);
        }

        // 我发布的活动
        $sql_me_issue = "SELECT count(apply.aid) as count_no, apply.id, apply.aid, act.id AS aid, act.title, act.description, act.date, act.time, img_url
             from ims_vip_activity as act
             LEFT join ims_vip_activity_apply AS apply on apply.aid = act.id
             LEFT JOIN ims_vip_activity_category_cover AS cover ON act.img_id = cover.id
             WHERE act.openid = '$userinfo[openid]'
             GROUP BY (apply.aid)";
        $source_data = pdo_fetchall($sql_me_issue);

        /*$issue_data = array();
        foreach ($source_data as $key => $value) {
            $sql_act = "SELECT * from ims_vip_activity_apply as a where a.aid = $value[aid]";
            $size_source = sizeof(pdo_fetchall($sql_act));
            array_push($source_data[$key], $size_source);

            array_push($issue_data, $source_data[$key]);
        }*/

//     return json_encode($endData[0]);
//    return json_encode($endData);
        include $this->template('home_page');
    }

    // 活动创建编辑活动
    public function doMobileCreate_Event_Editor()
    {
        global $_W, $_GPC;
        if ($_GPC['aid'] !== null) { // 已报名, 编辑活动
            // echo $_GPC['aid'];
            $sql_editor_event = "SELECT apply.id AS id, apply.aid AS aid, act.title, act.description,
            act.date, act.time, act.address, cover.cid, cover.img_url
        FROM ims_vip_activity_apply AS apply
        LEFT JOIN ims_vip_activity AS act ON apply.aid = act.id
        LEFT JOIN ims_vip_activity_category_cover AS cover ON act.img_id = cover.id
        WHERE act.id = '$_GPC[aid]'";
            $editor_event_data = pdo_fetchall($sql_editor_event);
            $editor_id = $_GPC['aid'];
//      echo $editor_event_data[0]['img_url'];
//      var_dump($editor_event_data);
//      die();
        }

        if ($_GPC['img_id']) {
            $category_cover_data = pdo_get('vip_activity_category_cover', array('id' => $_GPC['img_id']));
            $img_url = $category_cover_data['img_url'];
        }

        include $this->template('create_event_editor');
    }

    // 创建活动自己默认加入
    public function doMobileInsertActivity()
    {
        global $_W, $_GPC;
        $to_apply = array(
            'aid' => $_GPC['aid'],
            'openid' => $_GPC['openid']
        );
        $to_apply_result = pdo_insert('vip_activity_apply', $to_apply);
        var_dump($to_apply_result);
        if ($to_apply_result) {
            return 'successfully';
        }
    }

    //选择封面
    public function doMobileSelect_Cover()
    {
        global $_W, $_GPC;
        $data_aid = $_GPC['aid'];

        $sql = "select category.id, category.name, cover.id img_id, cover.img_url 
        from ims_vip_activity_category as category, ims_vip_activity_category_cover as cover where category.id = cover.cid";
        $cover_data = pdo_fetchall($sql);
        foreach ($cover_data as $key => $value) {
            if (!$map[$value['id']]) {
                $map[$value['id']] = array($value);
            } else {
                array_push($map[$value['id']], $value);
            }
        }
        include $this->template('select_cover');
    }

    //  活动表数据写入
    public function doMobileSetActivity()
    {
        global $_W, $_GPC;
        if ($_W['ispost']) {
            $to_activity = array(
                'img_id' => $_GPC['img_id'] ? $_GPC['img_id'] : 1,
                'openid' => $_GPC['openid'],
                'title' => $_GPC['title'],
                'description' => $_GPC['description'],
                'date' => $_GPC['date'],
                'time' => $_GPC['time'],
                'address' => $_GPC['address']
            );
            $to_activity_result = pdo_insert('vip_activity', $to_activity);
            if (!empty($to_activity_result)) {
                $uid = pdo_insertid();
                return $uid;
            }
        }
    }

    //  活动表数据修改
    public function doMobileEditorActivity()
    {
        global $_W, $_GPC;
        $gpc_id = $_GPC['id'];
        if ($_W['ispost']) {
            $to_activity = array(
                'id' => $_GPC['editor_id'],
                'img_id' => $_GPC['img_id'] ? $_GPC['img_id'] : 1,
                'openid' => $_GPC['openid'],
                'title' => $_GPC['title'],
                'description' => $_GPC['description'],
                'date' => $_GPC['date'],
                'time' => $_GPC['time'],
                'address' => $_GPC['address']
            );

            $to_activity_result = pdo_insert('vip_activity', $to_activity, true);

            if (!empty($to_activity_result)) {
                $uid = pdo_insertid();
                return $uid;
            }
        }
    }

    // 活动详情页
    public function doMobileDetails()
    {

        global $_W, $_GPC;
        $aid_details = $_GPC['aid']; // details aid

        $userinfo = mc_oauth_userinfo($_W['uniacid']);
        /*$sql_apply = "SELECT apply.id as id, apply.aid as aid, act.title, act.description, act.date, act.time, act.address, img_url,
            `user`.nickname, `user`.avatar, `user`.openid, apply.apply_time
            FROM ims_vip_activity_apply AS apply
            LEFT JOIN ims_vip_activity AS act ON apply.aid = act.id
            LEFT JOIN ims_vip_activity_category_cover AS cover ON act.img_id = cover.id
            LEFT JOIN ims_vip_activity_user AS `user` ON `user`.openid = apply.openid
            WHERE apply.aid = '$aid_details'";*/

        $sql_apply = "SELECT DISTINCT(act.id) as id, apply.aid as aid, act.title, act.description, act.date, act.time, act.address, img_url,
        `user`.nickname, `user`.avatar, `user`.openid, apply.apply_time
        FROM ims_vip_activity_apply AS apply
        LEFT JOIN ims_vip_activity AS act ON act.id = apply.aid
        LEFT JOIN ims_vip_activity_category_cover AS cover ON act.img_id = cover.id
        LEFT JOIN ims_vip_activity_user AS `user` ON `user`.openid = apply.openid
        WHERE act.id = '$aid_details'";
        $sources_details = pdo_fetchall($sql_apply);
        $activity_id = $sources_details[0]['id'];

        $apply_no_people = count($sources_details); // apply number of people


        // 查询
        /*$sql_is_apply = "SELECT apply.id as id, apply.aid as aid, apply.apply_time, act.title, act.description, act.date, act.time, act.address, img_url,
            `user`.nickname, `user`.avatar, `user`.openid
            FROM ims_vip_activity_apply AS apply
            LEFT JOIN ims_vip_activity AS act ON apply.aid = act.id
            LEFT JOIN ims_vip_activity_category_cover AS cover ON act.img_id = cover.id
            LEFT JOIN ims_vip_activity_user AS `user` ON 	`user`.openid = apply.openid
            WHERE apply.aid = '$aid_details' AND `user`.openid = '$userinfo[openid]'";*/

        // 是否自己报名
        $sql_apply = "SELECT id, openid FROM ims_vip_activity WHERE id = '$aid_details'";
//    var_dump($_GPC['id']);
//    die();
//    $sql_is_apply_result = pdo_fetchall($sql_is_apply);
        $sql_is_apply_result = pdo_fetchall($sql_apply);

        $account_api = WeAccount::create();
        $jssdk = $account_api->getJssdkConfig();

        // 别人发布的活动自己是否报名
        $sql_query = "select apply.aid from ims_vip_activity_apply as apply where apply.aid = '$aid_details' and apply.openid = '$userinfo[openid]'";
        $sql_query_result = pdo_fetchall($sql_query);

        include $this->template('details');
    }

    // 查询是否报名
    public function doMobileIsApplyQuery()
    {
        global $_W, $_GPC;
        $sql = "SELECT aid FROM ims_vip_activity_apply WHERE openid = '$_GPC[openid]'";
        $sql_result = pdo_fetchall($sql);
        return json_encode($sql_result);

    }

    // 报名表数据删除 -- 取消报名
    public function doMobileDeleteActivityApplyResult()
    {
        global $_W, $_GPC;
        $aid_details = $_GPC['aid']; // details aid
        $userinfo = mc_oauth_userinfo($_W['uniacid']);
//    $sql = "delete from ims_vip_activity_apply where openid = '$_GPC[openid]' and aid = '$_GPC[aid]'";
        $result = pdo_delete('vip_activity_apply', ['aid' => $aid_details, 'openid' => $userinfo['openid']]);
        return $code = $result ? 'success' : 'failed';
    }

    // 活动表及报名表数据删除 -- 取消报名自己发布的活动
    public function doMobileDeleteActivityApplyMyself()
    {
        global $_W, $_GPC;
        $activity_id = $_GPC['id']; // activity id
        $userinfo = mc_oauth_userinfo($_W['uniacid']);
//    $sql = "delete from ims_vip_activity_apply where openid = '$_GPC[openid]' and aid = '$_GPC[aid]'";
        $result = pdo_delete('vip_activity', ['id' => $activity_id, 'openid' => $userinfo['openid']]);
        return $code = $result ? 'success' : 'failed';
    }

    // 后台方法
    public function doWebIndex()
    {
        // 这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        $userinfo = mc_oauth_userinfo($_W['uniacid']);
        $activity_data = pdo_get('apply_vip_activity');
        if ($_W['ispost']) {
            $up_activity = [
//                'id' => $_GPC['id'],
                'title' => $_GPC['title'],
                'custom_share_icon' => $_GPC['custom_share_icon'],
                'custom_share_title' => $_GPC['custom_share_title'],
                'custom_share_describe' => $_GPC['custom_share_describe'],
                'custom_share_website' => $_GPC['custom_share_website'],
                'creation_time' => time()
            ];
//            $result = pdo_insert('apply_vip_activity', $up_activity, true);
            if ($result) {
                message('update successfully!', $this->createWebUrl('index'), 'success');
            } else {
                message('The system is error!', $this->createWebUrl('index'), 'error');
            }
        }

        include $this->template('index');
    }

    // 后台封面图管理
    public function doWebBanner()
    {
        global $_W, $_GPC;
        $oid = $_GPC['oid'];

        $sql = 'SELECT category.id cid, category.`name`, cover.id oid, cover.cid cover_id, cover.img_url, cover.create_time
       FROM ims_vip_activity_category AS category
       LEFT JOIN ims_vip_activity_category_cover AS cover
       ON category.id = cover.cid
       ORDER BY oid desc, cid asc';
        $sources = pdo_fetchall($sql); // 总结果集数组

        $total = count($sources); // 总记录条数
        $page_index = max($_GPC['page'], 1); // 当前页数
        $page_size = 5; // 单页条数
        $pager = pagination($total, $page_index, $page_size);
        $p = ($page_index - 1) * 5; // 从第几条数据开始检索, 从0开始
        $sql .= " limit " . $p . ", " . $page_size;
        $order_list = pdo_fetchall($sql); // 取出结果集数组

        /*$endData = array();
        foreach ($order_list as $key => $value) {

          $sql = "SELECT * from ims_vip_activity_category_cover as a where a.id = $value[id]";
          $source = sizeof(pdo_fetchall($sql));
          array_push($sources[$key], $source);
          // $sources[$key-1]->number = $source;

          array_push($endData, $sources[$key]);

        }
        var_dump($_GPC['id']);*/
//    return json_encode($endData);
        include $this->template('banner');
    }


    // 后台封面图新建及编辑
    public function doWebEditor_New_Cover()
    {
        global $_W, $_GPC;
        $oid = $_GPC['oid'];
        if($oid){
            // 编辑封面ID及分类
            $category_sql_editor = "select category.`name`, cover.id, cover.cid, cover.img_url
               FROM ims_vip_activity_category AS category
               LEFT JOIN ims_vip_activity_category_cover AS cover
               ON category.id = cover.cid
               WHERE cover.id = '$oid'";
            $category_sql_editor_result = pdo_fetchall($category_sql_editor);
            $cid_str = $category_sql_editor_result[0]['cid'];
            if (!$oid) {
                $cid_str = $category_sql_editor_result[0]['cid'] + 1;
            }
            $cid_str_to_int = intval($cid_str);

            // 封面ID及分类
            $category_sql_query = "select * from ims_vip_activity_category";
            $category_sql_query_result = pdo_fetchall($category_sql_query);

        }else{
            $category_sql_query = "select * from ims_vip_activity_category";
            $category_sql_query_result = pdo_fetchall($category_sql_query);
        }

        include $this->template('editor_new_cover');
    }

    // 后台编辑封面
    public function doWebEdt_Cover()
    {
        global $_W, $_GPC;
        $to_cover = [
            'id' => $_GPC['cid'],
            'cid' => $_GPC['obj'],
            'img_url' => $_GPC['img_url']
        ];
        $result = pdo_insert('vip_activity_category_cover', $to_cover, true);
        if($result){
            return "suc";
        }

    }

    // 后台新建封面
    public function doWebNew_Cover()
    {
        global $_W, $_GPC;
        $img_url = $_GPC['img_url'];

        if ($_W['ispost']) {
            $to_activity = array(
                'cid' => $_GPC['cid'],
                'img_url' => $_GPC['img_url']
            );
            $to_activity_result = pdo_insert('vip_activity_category_cover', $to_activity);
//      if (!empty($to_activity_result)) {
//        $uid = pdo_insertid();
//        return $uid;
//      }
            if ($to_activity_result) {
                message("新建成功",  $this->createWebUrl('Banner'));
                $this->createWebUrl('Banner');
            } else {
                message("系统出错",  $this->createWebUrl('Banner'));
                $this->createWebUrl('Banner');
            }

        }

    }

    // 后台删除封面
    public function doWebDelete_Cover()
    {
        global $_W, $_GPC;
        $oid = $_GPC['oid'];
        //    $sql = "delete from ims_vip_activity_category_cover where id =" .$_GPC[oid];
        //    $category_sql_result = pdo_fetchall($sql);
        $result = pdo_delete('vip_activity_category_cover', ['id' => $oid]);
//        return $code = $result ? 'success' : 'fail';

        if (!empty($result)) {
            message("删除成功", $this->createWebUrl('Banner'));
        } else {
            message("系统出错", $this->createWebUrl('Banner'));
        }
    }


    // 后台用户管理展示
    public function doWebUser()
    {
        global $_W, $_GPC;

        $sql = "select * from ims_vip_activity_user";
        $sources = pdo_fetchall($sql); // 总结果集数组

        $total = count($sources); // 总记录条数
        $page_index = max($_GPC['page'], 1); // 当前页数
        $page_size = 5; // 单页条数
        $pager = pagination($total, $page_index, $page_size);
        $p = ($page_index - 1) * 5; // 从第几条数据开始检索, 从0开始
        $sql .= " order by id asc limit " . $p . ", " . $page_size;
        $user_list = pdo_fetchall($sql); // 取出结果集数组

        include $this->template('user');
    }

}
