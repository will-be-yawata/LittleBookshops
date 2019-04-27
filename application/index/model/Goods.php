<?php
namespace app\index\model;
use think\Model;
use think\Cache;
class Goods extends Model{
    public function select_top6(){
        $res = db('goods')->distinct ( true )->order('Rand()')->limit(6)->select();
        return $res;
    }

    public function focus(){
        $p = [
            'type'  =>  'Redis',
            'expire'=>  0,
            'prefix'=>  '',
        ];

        $redis = Cache::connect($p);
        if(!$redis->get('index_focus')){
            $data = array();
            $label = db('label')->where('label_parent=11')->limit(5)->select();
            foreach($label as $key => $value){
                $data[$key]['label'] = $value['label_name'];
                $data[$key]['goods'] = array();
                $goods = db('goods')->where('goods_label',$value['label_id'])->limit(10)->select();
                foreach($goods as $v){
                    array_push($data[$key]['goods'],$v);
                }
            }
            $redis->set('index_focus',$data);
            return $data;
        }else{
            return $redis->get('index_focus');
        }

    }
    public function bestsell(){
        $p = [
            'type'  =>  'Redis',
            'expire'=>  0,
            'prefix'=>  '',
        ];

        $redis = Cache::connect($p);
        if(!$redis->get('index_bestsell')){
            $data = array();
            $goods = db('goods')->distinct ( true )->order('Rand()')->limit(8)->select();
            foreach($goods as $k=>$v){
                $data[$k]['id'] = $k+1;
                $data[$k]['goods'] = $v;
            }
            $redis->set('index_bestsell',$data);
            return $data;
        }else{
            return $redis->get('index_bestsell');
        }

    }
    public function author(){
        $p = [
            'type'  =>  'Redis',
            'expire'=>  0,
            'prefix'=>  '',
        ];

        $redis = Cache::connect($p);
        if(!$redis->get('index_author')){
            $data = array();
            $author = db('author')->limit(5)->select();
            foreach($author as $key=>$value){
                $data[$key]['author'] = $value['author_name'];
                $data[$key]['img'] = $value['author_img'];
                $data[$key]['about'] = $value['author_about'];

                $data[$key]['goods'] = array();
                $goods = db('goods')->where('goods_author' , $value['author_name'])->limit(4)->select();
                foreach($goods as $v){
                    array_push($data[$key]['goods'],$v);
                }
            }
            $redis->set('index_author',$data);
            return $data;
        }else{
            return $redis->get('index_author');
        }
    }
    public function goodslist_label_select($label){
        $res = db('goods')->where('goods_label|goods_parent_label','=',$label)->paginate(12);
        return $res;
    }
    public function goodslist_headersearch($data){
        $data = '%'.$data.'%';
        $res = db('goods')->where('goods_name|goods_author|goods_press','like',$data)->paginate(12);
        return $res;
    }
    public function detail_select($id){
        $res = db('goods')->where('goods_id',$id)->find();
        return $res;
    }
    public function rm_redis(){
        $p = [
            'type'  =>  'Redis',
            'expire'=>  0,
            'prefix'=>  '',
        ];

        $redis = Cache::connect($p);
        if($redis->get('index_focus')){
            $redis->rm('index_focus');
        }
        if($redis->get('index_bestsell')){
            $redis->rm('index_bestsell');
        }
        if($redis->get('index_author')){
            $redis->rm('index_author');
        }
        if($redis->get('index_label')){
            $redis->rm('index_label');
        }

    }
}