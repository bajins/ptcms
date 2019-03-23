<?php
 class FriendlinkBlock extends Block { public function run($zym_7) { $zym_5 = I('num', 'int', 10, $zym_7); $zym_6 = M('friendlink')->where(array('status' => 1))->field('name,url,description,logo,isbold,color')->order('ordernum asc')->limit($zym_5)->getlist(); return $zym_6; } }
?>