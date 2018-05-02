<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\MsgContent;
use App\Pagination\LengthAwarePaginator as Paginator;
use URL;
use App\Models\Admin\MsgType;
use App\Models\Admin\MsgSchool;


class MsgContentController extends Controller
{
	private $pageNum = 15;//每条条数
	public function index()
	{
		return view('admin.index');
	}
	/**
	 * [getContentList 获取信息列表]
	 * @param  Request $request [description]
	 * @return [type]           [array]
	 * author jiapeng.gong
	 */
	public function getContentList(Request $request)
	{
		$params['page'] = $request->input('page',1);//当前页数
		$params['pageNum'] = $this->pageNum;//每页显示的条数
		$params['tid'] = $request->input('tid','');
		$path = URL::action('Admin\MsgContentController@getContentList'); 
		//获取列表
		$tmpList = MsgContent::getMsgContentLists($params);

		$list = new Paginator($tmpList['list'],$tmpList['total'],$tmpList['pageNum'],$tmpList['page'],  ['path'=>$path,'query'=>['page'=>$params['page']]]);
		//echo '<pre>';print_r($list);exit;
		//获取类型信息
		$tmpTypeList = MsgType::getMsgTypeALl();
		foreach ($tmpTypeList as $key => $value) {
			$params['msgtype'] = !empty($params['tid'])?$tmpTypeList[$params['tid']]['msgtype']:'全部类型';
		}
		$params['msgtype'] = !empty($params['tid'])?$tmpTypeList[$params['tid']]['msgtype']:'全部类型';
		$data = [
			'list'=>$list,
			'pageNum'=>$this->pageNum,
			'activeType'=>'tmp',
			'tmplist'=>$tmpTypeList,
			'params'=>$params,
			'defaultimg'=> URL::asset('/').'/images/face.jpg',
		];
		//echo '<pre>';print_r($data);exit;
		return view('admin.contentlist',$data);
	}
	/**
	 * [addContent 添加信息]
	 */
	public function addContent(Request $request)
	{
		$params['page'] = $request->input('page',1);//当前页数
		$params['pageNum'] = $this->pageNum;//每页显示的条数
		$params['tid'] = $request->input('tid','');

		$path = URL::action('Admin\MsgContentController@getContentList'); 
		//获取列表
		//$tmpList = MsgContent::getMsgContentList($params);
		
		$msgTypeList = MsgType::getMsgTypeALl();
		$msgParentName = MsgType::$MSGTYPE;
		//echo '<pre>';print_r($msgTypeList);exit();
		$params['tmptype'] = !empty($params['tid'])?$msgTypeList[$params['tid']]['tmptype']:'全部类型';
		$schoolList = MsgSchool::getMsgSchoolALl();
		//echo '<pre>';print_r($schoolList);exit();
		$data = [
			'activeType' 	=> 'tmp',
			'msgtypelist'	=> $msgTypeList,
			'params'		=> $params,
			'msgParentName'	=> $msgParentName,
			'schoolList'	=> $schoolList,
		];
		//echo '<pre>';print_r($data);exit();
		return view('admin.addcontent',$data);
	}
	/**
	 * [updateContent 修改信息]
	 * @return [type] [description]
	 */
	public  function updateContent(Request $request)
	{
		$msgId = $request->input('id',1);//模板id
		;
		$msgInfo = MsgContent::getMsgContentById($msgId);
		$msgTypeList = MsgType::getMsgTypeALl();
		$schoolList = MsgSchool::getMsgSchoolALl();
		$data = [
			'msgInfo'		=> $msgInfo,
			'msgtypelist'	=> $msgTypeList,
			'activeType'	=> 'tmp',
			'schoolList'	=> $schoolList,
		];
		//echo '<pre>';print_r($data);exit;
		return view('admin.updatecontent',$data);
	}

	/**
	 * [saveContent 保存信息]
	 * @param  Request $request [description]
	 * @return [type]           [[]]
	 */
	public  function saveContent(Request $request)
	{
		$id 		= $request->input('id','');//类型id
		$msgTitle 	= $request->input('title','');//标题
		$msgContent = $request->input('content','');//内容
		$msgTypeId 	= $request->input('msgtypeid','');//类型内容
		$sid 		= $request->input('sid','');//学校id
		$data = [
			'id'		=> $id,
			'title'		=> $msgTitle,
			'content'	=> $msgContent,
			'tid'		=> $msgTypeId,
			'sid'		=> $sid,
			'imgurl'    => 'ceshi',
			'userId'    => 7,
			'userName'  => 'ceshi',
		];

		$res = MsgContent::saveMsgContent($data);
		
        $url = URL::action('Admin\MsgContentController@getContentList');
        $ress = ['code'=>'0','message'=>'添加成功','url'=>$url];
		//echo '<pre>';print_r($res);exit;
		return json_encode($ress,JSON_UNESCAPED_UNICODE);
	}
	/**
	 * [deleteContent 删除]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function deleteContent(Request $request)
	{
		$tmpId = $request->input('id','');//模板id
		$data = ['id'=>$tmpId];

		$res = MsgContent::managerDeleteMsgById($data);
		return json_encode($res,JSON_UNESCAPED_UNICODE);
	}
}
?>
