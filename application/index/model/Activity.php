<?php
namespace app\index\model;
use think\Model;
use think\Cache;
class Activity extends Model{
    public function activity(){
        $p = [
            'type'  =>  'Redis',
            'expire'=>  0,
            'prefix'=>  'think',
        ];

        $redis = Cache::connect($p);
        if(!$redis->get('index_activity')){
            $res = db('activity')->order('activity_id desc')->select();
            $redis->set('index_activity',$res);
            return $res;
        }else{
            return $redis->get('index_activity');
        }

    }
}