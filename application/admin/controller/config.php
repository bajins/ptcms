<?php

//配置项管理
class ConfigController extends AdminController {

    public function init() {
        $this->tableName = 'config';
        parent::init();
    }

    public function indexAction() {
        $where          = $this->_parsemap();
        $this->page     = I('request.page', 'int', 1);
        $this->pagesize = C('admin_pagesie', null, 20);
        $this->list     = $this->model->field('id,title,key,intro,group,create_user_id,update_user_id,create_time,update_time')->where($where)->page($this->page)->limit($this->pagesize)->getlist();
        $this->totalnum = $this->model->where($where)->count();
        $this->pagenum  = ceil($this->totalnum / $this->pagesize);
        //$this->pagelist=B('pagelist',array('totalnum'=>$this->totalnum,'page'=>$this->page,'pagesize'=>C('admin_pagesie',null,20)));
        if (IS_AJAX) {
            $this->ajax(array('data' => $this->list, 'totalnum' => $this->totalnum, 'pagenum' => $this->pagenum));
        } else {
            $this->display();
        }

    }

    public function addAction() {
        if (IS_POST) {
            $param['title']          = I('title', 'str', '');
            $param['key']            = I('key', 'en', '');
            $param['intro']          = I('intro', 'str', '');
            $param['value']          = I('value', 'str', '');
            $param['extra']          = I('extra', 'str', '');
            $param['type']           = I('type', 'en', '');
            $param['group']          = I('group', 'int', 0);
            $param['ordernum']       = I('ordernum', 'int', 50);
            $param['level']          = I('level', 'int', 1);
            $param['status']         = I('status', 'int', 1);
            $param['create_user_id'] = $_SESSION['admin']['userid'];
            $param['create_time']    = NOW_TIME;
            if ($this->model->add($param)) {
                $this->success('添加成功', U('index'));
            } else {
                $this->error('添加失败');
            }
        }
        $this->grouplist = C('config_group');
        $this->display();
    }

    public function editAction() {
        $id   = I('request.id', 'int', 0);
        $info = $this->model->where(array('id' => $id))->find();
        if (IS_POST) {
            $param['title']          = I('title', 'str', '');
            $param['key']            = I('key', 'en', '');
            $param['intro']          = I('intro', 'str', '');
            $param['value']          = I('value', 'str', '');
            $param['extra']          = I('extra', 'str', '');
            $param['type']           = I('type', 'en', '');
            $param['group']          = I('group', 'int', 0);
            $param['ordernum']       = I('ordernum', 'int', 50);
            $param['level']          = I('level', 'int', 1);
            $param['status']         = I('status', 'int', 1);
            $param['update_user_id'] = $_SESSION['admin']['userid'];
            $param['update_time']    = NOW_TIME;
            $param['id']             = $id;
            if ($this->model->edit($param)) {
                $this->success('修改成功', U('index'));
            } else {
                $this->error('修改失败');
            }
        }
        $this->info      = $info;
        $this->grouplist = C('config_group');
        $this->display();
    }

    public function setAction() {
        if (IS_POST) {
            $vo = include APP_PATH . '/common/config.php';
            foreach ($_POST as $k => $v) {
                $this->model->where(array('key' => $k))->edit(array('value' => $v));
            }
            $_POST['wap_domain'] = rtrim($_POST['wap_domain'], '/');
            if (strpos($_POST['wap_domain'], 'http://') === false) {
                $_POST['wap_domain'] = 'http://' . $_POST['wap_domain'];
            }
            if ($_POST['wap_domain'] == $_POST['siteurl']) {
                $this->error('手机地址和pc地址不能一致');
            }
            $sitedomain = $domain = $this->request->getSiteCode($_POST['siteurl']);
            $olddomain  = $domain = $this->request->getSiteCode($vo['wap_domain']);
            $newdomain  = $domain = $this->request->getSiteCode($_POST['wap_domain']);
            if ($_POST['wap_type'] == 2) {
                if($sitedomain == $newdomain){
                    $this->error('该模式下您的wap域名设置和pc域名一致，会造成无限循环');
                }else{
                    $data = array(
                        'tpl_theme' => $vo['wap_theme'],
                        'html'      => 0,
                    );
                    F(APP_PATH . '/common/' . $olddomain . '.config.php', null);
                    if (!F(APP_PATH . '/common/' . $newdomain . '.config.php', $data)) {
                        $this->error('创建手机站配置文件失败，请检查' . APP_PATH . '/common/' . '文件夹权限', 0, 0);
                    }
                }
            } else {
                F(APP_PATH . '/common/' . $olddomain . '.config.php', null);
            }
            $this->success('操作成功');
        }
        $grouplist = C('config_group');
        $data      = array();
        foreach ($grouplist as $k => $v) {
            if ($k > 0) {
                $data[$k]['name'] = $v;
                $data[$k]['list'] = $this->model->where(array('status' => 1, 'group' => $k))->order('ordernum asc')->field('title,key,value,type,extra,intro')->select();
                $data[$k]['list'] = $this->model->parseConfigValue($data[$k]['list']);
            }
        }
        $this->list = $data;
        $this->display();
    }

}