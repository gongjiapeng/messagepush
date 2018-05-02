<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use URL;
use DB;

class MsgOpinion extends Model
{
   
    protected $table = 'msgopinion';//建议表
    protected $primaryKey = 'id';
    static $fields = [
        'msgopinion.id',
        'msgopinion.user_id',
        'msgopinion.user_name',
        'msgopinion.content',
        'msgopinion.created_at',
        'msgopinion.updated_at',
    ];
    //public $timestamps = true;
    /**
     * [getMsgOpinionList 获取意见列表]
     * @param  [type] $params [参数 ]
     * @return [type]         []
     */
    public static function getMsgOpinionList($params)
    {
        $data = [];
        $msgopinion = DB::table('msgopinion')
                    ->forPage($params['page'], $params['pageNum'])
                    ->orderBy('msgopinion.created_at', 'desc')
                    ->get(self::$fields)->toArray();
                    
        $countmsgopinion = DB::table('msgopinion')
                    ->count();
        $data['total'] = $countmsgopinion;
        $data['list'] = $msgopinion;
        $data['page'] = $params['page'];
        $data['pageNum'] = $params['pageNum'];
        //echo '<pre>';print_r($data);exit;
        return $data;
    }
    /**
     * [getMsgOpinionById 获取单条信息]
     * @param  [type] $Id [id]
     * @return [type]        [[]]
     */
    public static function getMsgOpinionById($id)
    {
        $msgInfo = DB::table('msgopinion')
            ->where('msgopinion.id',$id)
            ->select(self::$fields)
            ->first();
        return $msgInfo;
    }

    /**
     * [saveMsgOpinion 保存意见信息]
     * @param  [type] $data [保存的数据]
     * @return [type]       [description]
     */
    public static function saveMsgOpinion($data)
    {
        $res = ['code'=>'','message'=>'','url'=>''];
        $saveData = [
                'user_name'     =>$data['userName'],
                'user_id'   =>$data['userId'],
                'content'   =>$data['content'],
        ];
        $result  =DB::table('msgopinion')->insertGetId($saveData);
        if($result) {
            $res = $result;
        } else {
            $res = false;
        }
        return $res;
    }
    /**
     * [deleteMsgSchoolById 根据id删除学校]
     * @param  [type] $id [description]
     * @return [type]       [description]
     */
    public static function deleteMsgOpinionById($id)
    {
        $url = URL::action('Admin\MsgOpinionController@getOpinionList');
        $result = DB::table('msgopinion')
                    ->where('id', $id)
                    ->delete();
        if($result) {
            $res = ['msg'=>'删除成功','url'=>$url];
        } else {
            $res = ['msg'=>'删除失败','url'=>''];
        }
        return $res;
        
    }
    /**
     * [getMsgCountByUserId description]
     * @return [type] [description]
     */
    public static function getMsgCountByUserId($userId)
    {
        $endTime= date('Y-m-d H:i:s',time());
        $startTime = date('Y-m-d 00:00:00',time());
        $msgcount= DB::table('msgopinion')
                    ->where('msgopinion.user_id',$userId)
                    ->where('msgopinion.created_at','>',$startTime)
                    ->where('msgopinion.created_at','<',$endTime)
                    ->count();
        return $msgcount;
    } 

}
