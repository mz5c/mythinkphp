<?php
namespace Home\Controller;
use Think\Controller;
class BlogController extends Controller{
    public function read($id=0){
        echo 'id='.$id;
        $this->success('即将前往Empty','../Index/index');
        //$this->redirect('Index/index', '', 1,'页面跳转中...');
    }

    public function archive($year='2013',$month='01'){
        echo 'year='.$year.'&month='.$month;
        //$this->success('即将前往Empty','/mythinkphp/Home/Index/index');
        //$this->redirect('Index/index', '', 5,'页面跳转中...');
    }
}