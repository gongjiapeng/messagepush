<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\MsgSchool;
use App\Http\Controllers\Controller;
use App\Pagination\LengthAwarePaginator as Paginator;
use URL;

class SchoolController extends Controller
{
    static $activeType = 'school';
    private $pageNum = 15;//每条条数
    public function index()
    {
        return view('admin.index');
    }
    /**
     * [getSchoolList 获取学校列表]
     * @param  Request $request [description]
     * @return [type]           [array]
     * author jiapeng.gong
     */
    public function getSchoolList(Request $request)
    {
        $params['page'] = $request->input('page',1);//当前页数
        $params['pageNum'] = $this->pageNum;//每页显示的条数

        $path = URL::action('Admin\SchoolController@getSchoolList');
        //获取列表  
        $schoolList = MsgSchool::getMsgSchoolList($params);
        //echo '<pre>';print_r($schoolList);exit;
        $list = new Paginator($schoolList['list'],$schoolList['total'],$schoolList['pageNum'],$schoolList['page'],  ['path'=>$path,'query'=>['page'=>$params['page']]]);
        $data = [
            'list'          => $list,
            'pageNum'       => $this->pageNum,
            'activeType'    => self::$activeType,
        ];
        
        return view('admin.schoollist',$data);
    }
        /**
     * [addSchool 添加学校信息]
     */
    public function addSchool(Request $request)
    {
        $data = [
            'activeType' => self::$activeType,
        ];
        return view('admin.addschool',$data);
    }
    /**
     * [saveSchool 修改学校信息]
     * @return [type] [description]
     */
    public  function saveSchool(Request $request)
    {
        $Id        = $request->input('id','');//id
        $schoolname= $request->input('schoolname','');//学校名称
        $data       = [
            'id'            => $Id,
            'schoolname'    => $schoolname,
        ];
        //echo '<pre>';print_r($data);exit;
        $res = MsgSchool::saveMsgSchool($data);
        
        
        //echo '<pre>';print_r($res);exit;
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * [updateSchool 修改学校信息]
     * @param  Request $request [description]
     * @return [type]           [[]]
     */
    public  function updateSchool(Request $request)
    {
        $id = $request->input('id',1);//id
        $schoolInfo = MsgSchool::getMsgSchoolById($id);
        $data = [
            'schoolInfo'=>$schoolInfo,
            'activeType'=>self::$activeType,
        ];
        //echo '<pre>';print_r($data);exit;
        return view('admin.updateschool',$data);
    }
    /**
     * [delSchool 删除学校信息]
     * @param  Request $request [description]
     * @return [type]           [description]
     * author jiapeng.gong
     */
    public function delSchool(Request $request)
    {
        $id = $request->input('id','');//模板id
        //echo '<pre>';print_r($tId);exit();
        $data = ['id'=>$id];
        $res = MsgSchool::deleteMsgSchoolById($data);
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }
}
