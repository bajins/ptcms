<?php
class manageController extends AdminController{ public function indexAction() { $this->redirect(U('admin.module.config',array('module'=>MODULE_NAME))); } }
?>