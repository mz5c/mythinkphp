<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/9
 * Time: 17:02
 */

namespace Admin\Controller;
use Think\Controller;

class NewcController extends Controller{
    public function Index(){
        $this->show('new controller');
    }
    public function hello(){
        $this->Index();
        echo '<br>';
        echo 'hello world';
    }
}