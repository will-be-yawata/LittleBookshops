<?php
namespace app\index\model;

use think\Model;
use think\Cache;

class Label extends Model{

    public function label(){
        $p = [
            'type'  =>  'Redis',
            'expire'=>  0,
            'prefix'=>  '',
        ];

        $redis = Cache::connect($p);
        //var_dump($redis);
        if(!$redis->get('index_label')){
            $label = db('label')->where('label_parent=0')->select();//获取一级分类
            $label2 = array();
            $label3 = array();
            foreach($label as $key=>$value){
                $label[$key]['child'] = array();//二级分类的名字
                $label2 = db('label')->where('label_parent='.$value['label_id'])->select();//获取二级分类

                foreach($label2 as $k=>$v){
                    array_push($label[$key]['child'],$v);//合并一级和二级分类
                    $label[$key]['child'][$k]['child2'] = array();//三级分类的名字

                    $label3 = db('label')->where('label_parent='.$v['label_id'])->select();//获取三级分类
                    foreach($label3 as $v2){
                        array_push($label[$key]['child'][$k]['child2'],$v2); //合并一级二级三级分类
                    }

                }
            }
            //var_dump($label[0]['child'][0]);
            $redis->set('index_label',$label);
            return $label;
        }else{
            return $redis->get('index_label');
        }
    }
}