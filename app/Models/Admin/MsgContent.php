<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\MsgType;
use URL;
use DB;
class MsgContent extends Model
{
	protected $table = 'msgcontent';//表名
    protected $primaryKey = 'id';
    static $fields = [
        'msgcontent.id',
        'msgcontent.tid',
        'msgcontent.sid',
        'msgcontent.user_id',
        'msgcontent.phone',
        'msgcontent.user_name',
        'msgcontent.title',
        'msgcontent.imgurl',
        'msgcontent.content',
        'msgcontent.created_at',
        'msgcontent.updated_at',
        'msgcontent.content',
        'msgcontent.remarks',
        'msgcontent.status',
        'msgschool.schoolname',
        'msgtype.msgtype',
    ];
	/**
	 * [getMsgContentList 获取列表的model]
	 * @param  [type] $params [参数 ]
	 * @return [type]         [信息列表]
	 */
    public static function getMsgContentList($params)
    {
        $data = [];
        //第二种分页
        if(empty($params['tid']))
        {
            $list = MsgContent::get()
                    ->forPage($params['page'], $params['pageNum'])
                    ->toArray();
            $count = MsgContent::count();
        }
        else
        {
            $list = MsgContent::where('tid',$params['tid'])
                    ->get()
                    ->forPage($params['page'], $params['pageNum'])
                    ->toArray();
            $count = MsgContent::where('tid',$params['tid'])->count();
        }
        
        $data['total'] = $count;
        $data['list'] = $list;
        $data['page'] = $params['page'];
        $data['pageNum'] = $params['pageNum'];
        return $data;
    }
    /**
     * [getMsgContentLists 获取列表]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public static function getMsgContentLists($params)
    {
        $data = [];
        $msgcontent = DB::table('msgcontent')
                    ->leftJoin('msgtype', 'msgcontent.tid', '=', 'msgtype.tid')
                    ->leftJoin('msgschool', 'msgcontent.sid', '=', 'msgschool.sid')
                    ->forPage($params['page'], $params['pageNum'])
                    ->orderBy('msgcontent.created_at', 'desc');
        $countcontent = DB::table('msgcontent')
                    ->leftJoin('msgtype', 'msgcontent.tid', '=', 'msgtype.tid')
                    ->leftJoin('msgschool', 'msgcontent.sid', '=', 'msgschool.sid');
        if (!empty($params['tid'])) {
            $msgcontent->where('msgcontent.tid',$params['tid']);
            $countcontent->where('msgcontent.tid',$params['tid']);
        }
        if (!empty($params['sid'])) {
            $msgcontent->where('msgcontent.sid',$params['sid']);
            $countcontent->where('msgcontent.sid',$params['sid']);
        }
        if (!empty($params['userId'])) {
            $msgcontent->where('msgcontent.user_id',$params['userId']);
            $countcontent->where('msgcontent.user_id',$params['userId']);
        }
        if (!empty($params['status'])) {
            $msgcontent->where('msgcontent.status',$params['status']);
            $countcontent->where('msgcontent.status',$params['status']);
        }
        if (!empty($params['keywords'])) {
            $msgcontent->where('msgcontent.title','like',$params['keywords'].'%');
            $countcontent->where('msgcontent.title','like',$params['keywords'].'%');
        }
        $list = $msgcontent->get(self::$fields)->toArray();
        $count = $countcontent->count();
        //echo '<pre>';print_r($list);exit;
        $data['total'] = $count;
        $data['list'] = $list;
        $data['page'] = $params['page'];
        $data['pageNum'] = $params['pageNum'];
        return $data;
    }
    /**
     * [getMsgContentById 获取单个信息]
     * @param  [type] $tmpId [模板id]
     * @return [type]        [[]]
     */
    public static function getMsgContentById($id)
    {
        $msgInfo = DB::table('msgcontent')
                    ->leftJoin('msgtype', 'msgcontent.tid', '=', 'msgtype.tid')
                    ->leftJoin('msgschool', 'msgcontent.sid', '=', 'msgschool.sid')
                    ->where('msgcontent.id',$id)
                    ->select(self::$fields)
                    ->first();
        return $msgInfo;
    }
    /**
     * [getMsgCountByUserId description]
     * @return [type] [description]
     */
    public static function getMsgCountByUserId($userId)
    {
        $endTime= date('Y-m-d H:i:s',time());
        $startTime = date('Y-m-d 00:00:00',time());
        $msgcount= DB::table('msgcontent')
                    ->where('msgcontent.user_id',$userId)
                    ->where('msgcontent.created_at','>',$startTime)
                    ->where('msgcontent.created_at','<',$endTime)
                    ->count();
        return $msgcount;
    } 
    /**
     * [saveMsgContent 保存信息]
     * @param  [type] $data [保存的数据]
     * @return [type]       [description]
     */
    public static function saveMsgContent($data)
    {
        if(!empty($data['id'])) {
            $saveData = [
                'title'     =>$data['title'],
                'content'   =>$data['content'],
                'remarks'   =>$data['remarks'],
                'tid'       =>$data['tid'],
                'sid'       =>$data['sid'],
                'user_id'   =>$data['userId'],
                'imgurl'    =>$data['imgurl'],
                'phone'     =>$data['phone'],
                'user_name' =>$data['userName'],
            ];
            $result = DB::table('msgcontent')
                        ->where('id', $data['id'])
                        ->update($saveData);
        } else {
            $saveData = [
                'title'     =>$data['title'],
                'content'   =>$data['content'],
                'remarks'   =>$data['remarks'],
                'tid'       =>$data['tid'],
                'sid'       =>$data['sid'],
                'user_id'   =>$data['userId'],
                'imgurl'    =>$data['imgurl'],
                'phone'     =>$data['phone'],
                'user_name' =>$data['userName'],
            ];
            $result  =DB::table('msgcontent')->insertGetId($saveData);
        }
        if($result) {
            $res = $result;
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * [deleteMsgById 根据id删除信息]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function deleteMsgById($id,$userId)
    {
        $url = URL::action('Admin\MsgContentController@getContentList');
        $result = DB::table('msgcontent')
                    ->where('id', $id)
                    ->where('user_id', $userId)
                    ->delete();
        if($result) {
            $res = ['msg'=>'删除成功','url'=>$url];
        } else {
            $res = ['msg'=>'删除失败','url'=>''];
        }
        return $res;
    }
    /**
     * [deleteMsgById 根据id删除信息]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function managerDeleteMsgById($id)
    {
        $url = URL::action('Admin\MsgContentController@getContentList');
        $result = DB::table('msgcontent')
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
     * [updateStatusContent 修改状态 上下架]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function updateStatusContent($id,$status,$userId)
    {
        $result = DB::table('msgcontent')
                    ->where('id', $id)
                    ->where('user_id', $userId)
                    ->update(['status'=>$status]);
        return $result;
    }

}
