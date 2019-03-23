<?php
 class SearchController extends Controller{ public function suggestAction() { $zym_6=$this->input->get('key','str',''); $zym_5=http::get('http://bookshelf.html5.qq.com/data/ajax?m=search&start=1&hotwords=1&resourcename='.urlencode($zym_6)); $zym_5=json_decode($zym_5,true); return $this->success(array_column($zym_5['rows'],'name')); } }
?>