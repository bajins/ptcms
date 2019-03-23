<?php
class UserController extends CommonController{ public function init() { parent::init(); if (!IS_LOGIN){ $this->redirect(U('user.public.login')); } } }
?>