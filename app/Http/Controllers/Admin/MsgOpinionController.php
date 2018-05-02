<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\MsgOpinion;
use App\Pagination\LengthAwarePaginator as Paginator;
use URL;
class MsgOpinionController extends Controller
{
    private $pageNum = 15;//每条条数
    public function getOpinionList(Request $request)
    {
        $params['page'] = $request->input('page',1);//当前页数
        $params['pageNum'] = $this->pageNum;//每页显示的条数
        $path = URL::action('Admin\MsgOpinionController@getOpinionList'); 
        //获取列表
        $opinionList = MsgOpinion::getMsgOpinionList($params);
        $list = new Paginator($opinionList['list'],$opinionList['total'],$opinionList['pageNum'],$opinionList['page'],  ['path'=>$path,'query'=>['page'=>$params['page']]]);    
        $data = [
            'list'=>$list,
            'pageNum'=>$this->pageNum,
            'activeType'=>'opinion',
            'params'=>$params
        ];
        //echo '<pre>';print_r($data);exit;
        return view('admin.opinionlist',$data);          
    }
    /**
     * [getOpinionInfo 获取详细信息]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getOpinionInfo(Request $request)
    {
        $id = $request->input('id',1);
        $opinionInfo = MsgOpinion::getMsgOpinionById($id);
        $data = [
            'activeType'    => 'opinion',
            'opinionInfo'   => $opinionInfo,
        ];
        //echo '<pre>';print_r($data);exit();
        return view('admin.opinioninfo',$data);
    }
    /**
     * [delOpinionById 根据id删除信息]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delOpinionById(Request $request)
    {
        $id = $request->input('id',1);
        
        $res = MsgOpinion::deleteMsgOpinionById($id);
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }
}