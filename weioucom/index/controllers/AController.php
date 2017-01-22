<?php

/**
 * Description of AboutController.php
 * 
 * @author Liu Junjie (jjliu@weiou.com)
 * @Date 2016-2-13 23:22:39
 * @copyright 为偶公司
 */
class AController extends Weiou_Controller_Web {

    public function indexAction() {
        $this->view->setNoRender();
        $this->_redirect("/");
    }

}
