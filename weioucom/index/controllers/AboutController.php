<?php

/**
 * Description of AboutController.php
 * 
 * @author Liu Junjie (jjliu@weiou.com)
 * @Date 2016-2-13 23:22:39
 * @copyright 为偶公司
 */
class AboutController extends Weiou_Controller_Web {

    public function about_usAction() {
        $this->logger->info("about_us");
//        echo "about_us<br>";
        $this->view->active1 = "active";
    }

    public function xxAction(){
        echo "hello,about";
    }

    public function join_usAction() {
        $this->logger->info("join_us");
//        echo "join_us<br>";
        $this->view->active2 = "active";
    }

    public function contactsAction() {
        $this->logger->info("contactsAction");
//        echo "contactsAction<br>";
        $this->view->active3 = "active";
    }
    
    public function termsAction() {
        $this->logger->info("termsAction");
//        echo "termsAction<br>";
        $this->view->active4 = "active";
    }
    
    public function terms_cnAction() {
        $this->logger->info("terms_cnAction");
//        echo "terms_cnAction<br>";
        $this->view->active4 = "active";
        
//        $this->getRequest()->
    }
    
    public function privacy_enAction() {
        $this->logger->info("privacy_enAction");
//        echo "privacy_enAction<br>";
        $this->view->active5 = "active";
    }
    
    public function privacyAction() {
        $this->logger->info("privacyAction");
//        echo "privacyAction<br>";
        $this->view->active5 = "active";
    }

}
