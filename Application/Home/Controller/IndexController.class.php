<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Org\Util\ExcelXml;
session_start();
class IndexController extends Controller {
    public function index(){
		//echo 'hello';
        if(isset($_SESSION['views'])){
            $_SESSION['views']++;
        }else{
            $_SESSION['views'] = 1;
        }
        echo 'hello world '.$_SESSION['views'].'<br>';
        $Data = M('data');
        $result = $Data->find(33);
        $this->assign('result',$result);
        $this->display();
	}
	public function hello($name='thinkphp'){
        //echo 'hello,'.$name.'!';
        $this->assign('name',$name);
        $this->display();
    }
	public function test(){
        echo '这是一个测试方法!';
        echo '<br>';
        $this->hello2();
        echo '<br>';
        $this->hello3();
    }

    protected function hello2(){
        echo '只是protected方法!';
    }

    private function hello3(){
        echo '这是private方法!';
    }
    //前置操作方法
    public function _before_index(){
        echo 'before<br/>';
    }
    //后置操作方法
    public function _after_index(){
        echo 'after';
    }
    public function wucheng($name = 'thinkphp'){
        if(isset($_SESSION['views'])){
            $_SESSION['views']++;
        }else{
            $_SESSION['views'] = 1;
        }
        echo 'hello world '.$_SESSION['views'].'<br>';
        $user = M('user');
        $condition['status']=1;
        $condition['id']=array('in','1,3,5,7');
        //$data['score']=array('exp','score+45');
        //$user->where('score=0')->save($data);
        $count = $user->avg('score');
        echo $count.'<br>';
        $res = $user->where($condition)->select();
        $this->assign('list',$res);
        $this->assign('name',$name);
        $this->assign('test',json_encode($res));

        $model = new Model();
        //$sql = "update think_user set name='hello' where id=5";
        $mres = $model->table('think_user')->field('id,name')->where("type='vip5'")->select();
        var_dump($mres);
        $this->display();
    }
    public function export(){
        $data = null;
        if (!empty($_POST['data'])) {
            $data = json_decode($_POST['data']);
        }else{
            die('操作失败！');
        }

        $titleArr=array("id","name","passwd","status","type","money","score","modify_datetime");
        array_unshift($data, $titleArr);

        //导入excel插件=
        //import('Org.Util.ExcelXml');3.2.3 use Org\Util\ExcelXml  namespace Org\Util
        $excel=new ExcelXml();
        $excel->addArray($data);
        $excel->setWorksheetTitle('think_user'.date("Y-m-d H:i"));
        $excel->generateXML('think_user'.date("Y-m-d H:i"));
        echo 'hello world';
        echo '<br>';
    }
}