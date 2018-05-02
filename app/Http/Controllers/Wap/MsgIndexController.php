<?php

namespace App\Http\Controllers\Wap;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\MsgContent;
use App\Models\Admin\MsgType;
use App\Models\Admin\MsgSchool;

class MsgIndexController extends Controller
{
    private $pageNum = 15;//每条条数
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $params['tid'] = $request->input('tid','');//当前页数
        // $params['sid'] = $request->input('sid','');//当前页数
        // $params['page'] = $request->input('page',1);//当前页数
        // $params['pageNum'] = $this->pageNum;//每页显示的条数
        // //echo '<pre>';print_r($params);exit;
        // $list = MsgContent::getMsgContentLists($params);
        // //类型
        // $tmpTypeList = MsgType::getMsgTypeALl();
        // //获取学校
        // $schoolList = MsgSchool::getMsgSchoolALl();
        // $data = [
        //     'list'      =>$list,
        //     'tmplist'   =>$tmpTypeList,
        //     'schoollist'=>$schoolList,
        //     'params'    =>$params
        // ];
        //echo 111;exit;
        return view('wap.wapindex');
    }
    /**
     * [selectSchool 选择学校]
     * @return [type] [description]
     */
    public function selectSchool(Request $request)
    {
        $tid = $request->input('tid','');//当前页数
        $tmpTypeInfo = MsgType::getMsgTypeById($tid);
        //获取学校
        $schoolList = MsgSchool::getMsgSchoolALl();
        $data = [
            'schoollist'    => $schoolList,
            'tmptypeinfo'   => $tmpTypeInfo
        ];
        return view('wap.school',$data);
    }
    /**
     * [msgTypeList 获取标签列表]
     * @return [type] [description]
     */
    public function msgTypeList()
    {
        $tmpTypeList = MsgType::getMsgTypeALl();
        $data = [
            'tmplist'   =>$tmpTypeList,
        ];
        
        return view('wap.msgtypelist',$data);
    }
    /**
     * [msgTypeList 信息发布]
     * @return [type] [description]
     */
    public function addMsgContent()
    {
        $tmpTypeList = MsgType::getMsgTypeALl();
        $schoolList = MsgSchool::getMsgSchoolALl();
        $data = [
            'schoollist'    => $schoolList,
            'tmptypeinfo'   => $tmpTypeList
        ];
        //echo '<pre>';print_r($data);exit;
        return view('wap.addcontent',$data);
    }
    /**
     * [addMsgOpinion 吐槽]
     * @return [type] [description]
     */
    public function addMsgOpinion()
    {
        return view('wap.addopinion');
    }
}
