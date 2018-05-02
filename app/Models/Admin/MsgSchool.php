<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use URL;

class MsgSchool extends Model
{
   
    protected $table = 'msgschool';//模板类型表
    protected $primaryKey = 'sid';
    //public $timestamps = true;
    /**
	 * [getMessageTypeList 获取学校类型的model]
	 * @param  [type] $params [参数 ]
	 * @return [type]         []
	 */
    public static function getMsgSchoolList($params)
    {
        $data = [];
        $list = MsgSchool::get()
            ->forPage($params['page'], $params['pageNum'])
            ->toArray();
        $count = MsgSchool::count();
        $data['total'] = $count;
        $data['list'] = $list;
        $data['page'] = $params['page'];
        $data['pageNum'] = $params['pageNum'];
        //echo '<pre>';print_r($data);exit;
        return $data;
    }
    /**
     * [getMsgSchoolALl 获取所有学校]
     * @return [type] [description]
     */
    public static function getMsgSchoolALl()
    {
        $msgSchoolName = MsgSchool::all(['sid','schoolname'])->toArray();
        return $msgSchoolName;
    }
    /**
     * [getMsgSchoolById 获取学校信息]
     * @param  [type] $tmpId [模板id]
     * @return [type]        [[]]
     */
    public static function getMsgSchoolById($id)
    {
        $msgSchoolInfo = [];
        $msgSchoolModel = MsgSchool::where('sid',$id)
                        ->first(['sid','schoolname']);
        if(isset($msgSchoolModel->sid))
        {
            $msgSchoolInfo = $msgSchoolModel->toArray();
        }
        else
        {
            $msgSchoolInfo = [];
        }
        return $msgSchoolInfo;
    }
    /**
     * [getMsgSchoolByIds 根据id 获取多个学校]
     * @param  [type] $ids [[]]
     * @return [type]       [[]]
     */
    public static function getMsgSchoolByIds($ids)
    {
        $msgSchool = new MsgSchool;
        $res = ['code'=>'','message'=>'','url'=>''];
        if(empty($ids))
        {
            $res = ['code'=>'-1','message'=>'查询失败','url'=>''];
            return $res;
        }
        $list = $msgSchool->findMany($ids,['sid','schoolname'])->toArray();
        return $list;
    }
    /**
     * [saveMsgSchool 保存学校信息]
     * @param  [type] $data [保存的数据]
     * @return [type]       [description]
     */
    public static function saveMsgSchool($data)
    {
        $res = ['code'=>'','message'=>'','url'=>''];
        $url = URL::action('Admin\SchoolController@getSchoolList');
        if(!empty($data['id']))
        {
            $msgSchool = MsgSchool::find($data['id']);
            $msgSchool->schoolname   = $data['schoolname'];
            $result = $msgSchool->save();
        }
        else
        {
            $msgSchool = new MsgSchool;
            $msgSchool->schoolname   = $data['schoolname'];
            $result = $msgSchool->save();
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
     * [deleteMsgSchoolById 根据id删除学校]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function deleteMsgSchoolById($data)
    {
        $url = URL::action('Admin\SchoolController@getSchoolList');
        if(empty($data['id']))
        {
            $res = ['code'=>'-1','message'=>'id不能为空！','url'=>''];
            return $res;
        }
        $msgSchool = MsgSchool::find($data['id']);
        $result = $msgSchool->delete();
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

}
