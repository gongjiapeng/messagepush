<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use URL;
use DB;

class MsgType extends Model
{
    public static $MSGTYPE = [
        1 => '书籍',
        2 => '电子设备',
        3 => '办公用品',
        4 => '生活用品',
    ];
    protected $table = 'msgtype';//模板类型表
    protected $primaryKey = 'tid';
    //public $timestamps = true;
    /**
	 * [getMessageTypeList 获取类型的model]
	 * @param  [type] $params [参数 ]
	 * @return [type]         []
	 */
    public static function getMsgTypeList($params)
    {
        $data = [];
        //第一种分页
        /*$list = Messagelog::paginate($params['pageNum'],['*'],['page'=>$params['page']],$params['page']);
        return $list;*/
        //第二种分页
        $list = MsgType::where('parenttype',$params['parenttype'])
            ->get()
            ->forPage($params['page'], $params['pageNum'])
            ->toArray();
        $count = MsgType::where('parenttype',$params['parenttype'])->count();
        $data['total'] = $count;
        $data['list'] = $list;
        $data['page'] = $params['page'];
        $data['pageNum'] = $params['pageNum'];
        //echo '<pre>';print_r($data);exit;
        return $data;
    }
    /**
     * [getMsgTypeALl 获取所有的类型]
     * @return [type] [description]
     */
    public static function getMsgTypeALl()
    {

        //$msgType = MsgType::all(['tid','msgtype','author','parenttype'])->groupBy(['parenttype'])->toArray();
        $msgType = MsgType::all(['tid','msgtype','author','parenttype'])->toArray();
       
        if(!empty($msgType))
        {
            foreach ($msgType as $key => $value)
            {
                $msgTypeList[$value['tid']] = $value;
            }
        }
        else
        {
            $msgTypeList = [];
        }
        //echo '<pre>';print_r($msgTypeList);exit();
        return $msgTypeList;
    }
    /**
     * [getMsgTypeById 获取类型信息]
     * @param  [type] $tmpId [模板id]
     * @return [type]        [[]]
     */
    public static function getMsgTypeById($tid)
    {
        $msgTypeInfo = [];
        $msgTypeModel = MsgType::where('tid',$tid)
                        ->first(['tid','msgtype','author']);
        if(isset($msgTypeModel->tid))
        {
            $msgTypeInfo = $msgTypeModel->toArray();
        }
        else
        {
            $msgTypeInfo = [];
        }
        return $msgTypeInfo;
    }
    /**
     * [getMsgTypeByIds 根据tid 获取多个类型]
     * @param  [type] $tids [[]]
     * @return [type]       [[]]
     */
    public static function getMsgTypeByIds($tids)
    {
        $msgtype = new MsgType;
        $res = ['code'=>'','message'=>'','url'=>''];
        if(empty($tids))
        {
            $res = ['code'=>'-1','message'=>'查询失败','url'=>''];
            return $res;
        }
        $list = $msgtype->findMany($tids,['tid','msgtype'])->toArray();
        return $list;
    }
    /**
     * [saveMsgType 保存模板类型信息]
     * @param  [type] $data [保存的数据]
     * @return [type]       [description]
     */
    public static function saveMsgType($data)
    {
        $res = ['code'=>'','message'=>'','url'=>''];
        $url = URL::action('Admin\MsgTypeController@getMsgTypeList',['parenttype'=>$data['parenttype']]);
        if(!empty($data['tid']))
        {
            $msgtype = MsgType::find($data['tid']);
            $msgtype->msgtype   = $data['msgtype'];
            $msgtype->author    = $data['author'];
            $msgtype->parenttype = $data['parenttype'];
            $result = $msgtype->save();
        }
        else
        {
            $msgtype = new MsgType;
            $msgtype->msgtype   = $data['msgtype'];
            $msgtype->author    = $data['author'];
            $msgtype->parenttype = $data['parenttype'];
            $result = $msgtype->save();
        }
        if($result)
        {
            $res = ['code'=>'0','message'=>'保存成功','url'=>$url];
        }
        else
        {
            $res = ['code'=>'-1','message'=>'保存失败','url'=>''];
        }
        return $res;
    }
    /**
     * [deleteMsgTypeById 根据模板tid删除模板类型]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function deleteMsgTypeById($data)
    {
        $url = URL::action('Admin\MsgTypeController@getMsgTypeList');
        if(empty($data['tid']))
        {
            $res = ['code'=>'-1','message'=>'tid不能为空！','url'=>''];
            return $res;
        }
        $messagetype = MsgType::find($data['tid']);
        $result = $messagetype->delete();
        if($result)
        {
            $res = ['code'=>'0','message'=>'删除成功','url'=>$url];
        }
        else
        {
            $res = ['code'=>'-1','message'=>'删除失败','url'=>''];
        }
        return $res;
    }
    /**
     * [getMsgTypeIdByName 根据类型名称获取类型id]
     * @param  [type] $msgType [description]
     * @return [type]          [description]
     */
    public static function getMsgTypeIdByName($msgType)
    {
        $result = DB::table('msgtype')
                    ->where('msgType', $msgType)
                    ->select(['tid'])
                    ->first();
        if ($result) {
            return $result;
        } else {
            return [];
        }
    }

}
