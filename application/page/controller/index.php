<?php
class indexController extends CommonController{ public function indexAction() { } public function detailAction() { $zym_6=I('get.key','en',''); if($zym_6 && $zym_5=M('page')->where(array('key'=>$zym_6))->find()){ if($zym_5['status']){ $this->assign($zym_5); $this->display('../novelsearch/page'); }else{ halt(''); } }else{ halt('找不到对应的页面'); } } }
?>