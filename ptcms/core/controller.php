<?php

/**
 * @Author: 杰少Pakey
 * @Email : admin@ptcms.com
 * @File  : controller.php
 */
class PT_Controller extends PT_Base {

    public function getView() {
        static $view;
        if (!isset($view)) {
            $this->plugin->call('view_start');
            //实例化view
            $view = $this->view;
            //初始化模版
            $view->getTheme();
        }
        return $view;
    }

    /**
     * 显示当前页面的视图内容
     *
     * @access public
     * @param string $tpl    视图模板
     * @param string $module 所属模块
     * @param string $theme  所属模版
     * @return void
     */
    public function display($tpl = null, $module = null, $theme = null) {
        $this->response->disableRender();
        $content = $this->fetch($tpl, $module, $theme);
        $this->show($content);

    }

    // 实现 $this->name=value 的赋值方法
    public function __set($name, $value) {
        if (in_array($name, $this->protected)) {
            $this->$name = $value;
        } else {
            $this->getView()->set($name, $value);
        }
    }

    // 获取 $this->name 的值
    public function __get($name) {
        $var = null;
        if (in_array($name, $this->protected)) {
            $var = parent::__get($name);
        }
        if ($var === null) {
            $var = $this->getView()->get($name);
        }
        return $var;
    }

    /**
     * 模板复制 兼容老版本
     * assign
     *
     * @param      $name
     * @param null $value
     */
    public function assign($name, $value = null) {
        $this->getView()->set($name, $value);
    }

    /**
     * 输出视图内容
     *
     * @access public
     * @param string $content  输出内容
     * @param string $mimeType MIME类型
     * @return void
     */
    protected function show($content, $mimeType = 'text/html') {
        $this->response->setBody($content, $mimeType);
    }

    /**
     * 获取模板内容
     * fetch
     *
     * @param null $tpl
     * @param null $module
     * @param null $theme
     * @return string
     */
    protected function fetch($tpl = null, $module = null, $theme = null) {
        return $this->view->fetch($tpl, $module, $theme);
    }

    /**
     * 渲染开关
     * render
     *
     * @param $var
     */
    protected function render($var) {
        if ($var === true) {
            $this->response->enableRender();
        } elseif ($var === false) {
            $this->response->disableRender();
        } else {
            $this->view->setFile($var);
        }
    }

    /**
     * 失败返回
     * success
     *
     * @param        $info
     * @param string $jumpUrl
     * @param int    $second
     * @return mixed
     */
    public function success($info='success', $jumpUrl = '', $second = 1) {
        if (isset($_GET['f']) && ($_GET['f'] == 'json' || $_GET['f'] == 'xml')) {
            return array('status' => 1, 'info' => $jumpUrl?$jumpUrl:'success', 'data' => $info);
        }
        $this->dispatchJump($info, 1, $jumpUrl, $second);
    }

    /**
     * 失败返回
     * success
     *
     * @param        $info
     * @param string $jumpUrl
     * @param int    $second
     * @return mixed
     */
    public function error($info='error', $jumpUrl = '', $second = 3) {
        if (isset($_GET['f']) && ($_GET['f'] == 'json' || $_GET['f'] == 'xml')) {
            return array('status' => 0, 'info' => $info, 'data' => $jumpUrl?$jumpUrl:array());
        }
        $this->dispatchJump($info, 0, $jumpUrl, $second);
    }


    protected function dispatchJump($message, $status = 1, $jumpurl = '', $second = 1) {
        $this->config->set('layout', false);
        if ($this->request->isAjax() or $second === true) {
            $data['status'] = $status;
            $data['info']   = $message;
            $data['url']    = $jumpurl;
            $this->ajax($data);
        } else {
            defined('PT_SITENAME') ? $this->view->set('msgname', PT_SITENAME) : $this->view->set('msgname', $this->config->get('sitename', null, 'PTFrameWork'));
            if (is_array($jumpurl)) {
                if (count($jumpurl) > 1) {
                    $second = $second < 3 ? 3 : $second;
                    $this->view->set('urllist', $jumpurl);
                }
                $first   = current($jumpurl);
                $jumpurl = $first['url'];
            }
            //如果设置了关闭窗口，则提示完毕后自动关闭窗口
            $this->view->set('status', $status); // 状态
            $this->view->set('waitsecond', $second);
            $this->view->set('message', $message); // 提示信息
            $this->view->set('msgtitle', $status ? '成功' : '失败');
            if ($status) { //发送成功信息
                $this->view->set('msgtype', 'success'); // 提示类型
                // 默认操作成功自动返回操作前页面
                if ($jumpurl) {
                    $this->view->set("jumpurl", $jumpurl);
                } elseif (!empty($_SERVER['HTTP_REFERER'])) {
                    $this->view->set("jumpurl", $_SERVER["HTTP_REFERER"]);
                } else {
                    $this->view->set('jumpurl', $_SERVER['REQUEST_URI']);
                }
            } else {
                $this->view->set('msgtype', 'error'); // 提示类型
                // 默认发生错误的话自动返回上页
                if ($jumpurl) {
                    $this->view->set("jumpurl", $jumpurl);
                } elseif (!empty($_SERVER['HTTP_REFERER'])) {
                    $this->view->set("jumpurl", '#back#');
                } else {
                    $this->view->set('jumpurl', $_SERVER['REQUEST_URI']);
                }
            }
            if ($this->config->get('tpl_message')) {
                $this->display($this->config->get('tpl_message'));
            } else {
                $this->display('message', 'common', $this->config->get('tpl_theme') ? $this->config->get('tpl_theme') : 'default');
            }
            exit;
        }
    }

    public function ajax($data, $type = 'json') {

        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                $data = $this->response->jsonEncode($data);
                break;
            case 'JSONP':
                // 返回JSONP数据格式到客户端 包含状态信息
                $data = $this->response->jsonpEncode($data);
                break;
            case 'EVAL' :
                // 返回可执行的js脚本
                break;
            default     :
        }
        $this->response->setBody($data, 'application/json');
        exit;
    }

    public function redirect($url, $type = 302) {
        $this->response->redirect($url, $type);
    }
}

class controller extends PT_controller {

}