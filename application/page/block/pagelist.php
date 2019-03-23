<?php
class pagelistblock extends block{ public function run($zym_6) { if (isset($zym_6['num'])){ $zym_5=M('page')->field('name,key,id')->where('status=1')->order('ordernum asc')->limit(intval($zym_6['num']))->getlist(); }else{ $zym_5=M('page')->field('name,key,id')->where('status=1')->order('ordernum asc')->getlist(); } return $zym_5; } }
?>