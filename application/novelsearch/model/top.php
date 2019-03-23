<?php
class TOpModel extends PT_Model{ public function getlist() { $zym_6=cache::get('toplist'); if (APP_DEBUG || !$zym_6){ $zym_6=C('caption_top'); foreach($zym_6 as $zym_5=>$zym_7){ $zym_6[$zym_5]=array( 'name'=>$zym_7, 'key'=>$zym_5, 'url'=>U('novelsearch.list.top',array('type'=>$zym_5)), ); } cache::set('toplist',$zym_6,'3600'); } return $zym_6; } }
?>