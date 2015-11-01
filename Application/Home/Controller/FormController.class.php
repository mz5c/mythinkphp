<?php
namespace Home\Controller;
use Think\Controller;
class FormController extends Controller{
    public function insert(){
        $Form   =   D('Form');
        if($Form->create()) {
            $result =   $Form->add();
            if($result) {
                $this->success('数据添加成功！');
            }else{
                $this->error('数据添加错误！');
            }
        }else{
            $this->error($Form->getError());
        }
    }
    public function read($id=1){
        $Form   =   M('Form');
        // 读取数据
        $data =   $Form->find($id);
        $title = $Form->where("id=2")->getField('title');
        if($data) {
            $this->assign('data',$data);// 模板变量赋值
            $this->assign('title',$title);
        }else{
            $this->error('数据错误');
        }
        $this->display();
    }
    public function edit($id=0){
        $Form   =   M('Form');
        $res = $Form->delete(4);
        if($res){
            $this->success('delete success');
        }else{
            //$this->error('f');
        }
        $this->assign('vo',$Form->find($id));
        $this->display();
    }
    public function update(){
        $Form   =   D('Form');
        if($Form->create()) {
            $result =   $Form->save();
            if($result) {
                $this->success('操作成功yes！');
            }else{
                $this->error('写入错误！');
            }
        }else{
            $this->error($Form->getError());
        }
    }
    public function printlist(){
        $Form = M('Form');
        $list = $Form->limit(10)->select();
        $this->assign('list',$list);
        $this->display();
    }
}