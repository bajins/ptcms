<?php
 $zym_5 = new Memcache(); $zym_5->connect('127.0.0.1', '11211'); $zym_5->set('test', 'test mc'); var_dump($zym_5->get('test')); $zym_5->flush(); exit('ok');
?>