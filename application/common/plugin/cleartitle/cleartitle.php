<?php
class cleartitlePlugin extends Plugin{ public $tag='template_compile_end'; public $config=array(); public function run(&$zym_5) { $zym_5=preg_replace('{\s*-\s*Power\s*by\s*PTcms\s*</title>}isU','</title>',$zym_5); return $zym_5; } }
?>