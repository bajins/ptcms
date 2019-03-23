<?php
class SetController extends AdminController{

    public function init() {
        $this->tableName='config';
        parent::init();
        C('water_type',0);
        C('storage_path', C('upload_path',null,'uploads'));
    }

    //网站图片上传
    public function picAction() {
        if (IS_POST){
            if ($_POST['ico']!='favicon.ico'){
                if (stripos($_POST['ico'], 'http') === 0) {
                    $content=F($_POST['ico']);
                } elseif (is_file($_POST['ico'])) {
                    $content=F($_POST['ico']);
                }  elseif (is_file(PT_ROOT.'/'.ltrim($_POST['ico'],'/'))) {
                    $content=F(PT_ROOT.'/'.ltrim($_POST['ico'],'/'));
                }
                if (isset($content)){
                    F(PT_ROOT.'/favicon.ico',$content);
                }
            }
            if ($_POST['logo']!=C('logo')){
                $this->model->where(array('key'=>'logo'))->edit(array('value'=>$_POST['logo']));
            }
            if ($_POST['water_image']!=C('water_image')){
                $this->model->where(array('key'=>'water_image'))->edit(array('value'=>$_POST['water_image']));
            }
            $this->success('操作成功');
        }
        $this->display();
    }

    public function urlAction() {
        if (IS_POST){
            $this->model->seturl($_POST);
            $this->success('操作成功');
        }
        $this->view->config=$this->model->where(array('group'=>'-2'))->field('title,key,intro,value')->order('ordernum asc')->select();
        $this->display();
    }

    public function tkdAction() {
        if (IS_POST){
            (new ConfigModel())->settkd($_POST);
            $this->success('操作成功');
        }
        $config=$this->model->where(array('group'=>'-1'))->field('title,key,intro,value')->order('ordernum asc')->select();
        foreach($config as $k=>&$v){
            if ($v['value']){
                $v['value']=json_decode($v['value'],true);
            }else{
                $v['value']=array(
                    'title'=>'',
                    'keywords'=>'',
                    'description'=>'',
                );
            }
        }
        $this->list=$config;
        $this->display();
    }

    public function collectAction() {
        if (IS_POST){
            foreach($_POST as $k=>$v){
                $this->model->where(array('key'=>$k))->edit(array('value'=>$v));
            }
            $this->success('操作成功');
        }
        $list=$this->model->where(array('group'=>'-3'))->field('title,key,value,type,extra,intro')->order('ordernum asc')->select();
        $this->list=$this->model->parseConfigValue($list);
        $this->display();
    }
}