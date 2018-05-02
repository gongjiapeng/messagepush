<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\MsgType;
use App\Pagination\LengthAwarePaginator as Paginator;
use URL;

class MsgTypeController extends Controller
{
	//static $type = [1,2,3,4];
    private $pageNum = 5;//每条条数
	public function index()
	{
		return view('admin.index');
	}
	/**
	 * [getMsgTypeList 获取日志模板类型列表]
	 * @param  Request $request [description]
	 * @return [type]           [array]
	 * author jiapeng.gong
	 */
	public function getMsgTypeList(Request $request)
	{
		$params['parenttype']= $request->input('parenttype','1');//上级分类id
		$params['page'] = $request->input('page',1);//当前页数
		$params['pageNum'] = $this->pageNum;//每页显示的条数

		$path = URL::action('Admin\MsgTypeController@getMsgTypeList'); 
		//获取列表  
		$msgTypeList = MsgType::getMsgTypeList($params);
		$list = new Paginator($msgTypeList['list'],$msgTypeList['total'],$msgTypeList['pageNum'],$msgTypeList['page'],  ['path'=>$path,'query'=>['page'=>$params['page']]]);
		$data = [
			'list'			=> $list,
			'pageNum'		=> $this->pageNum,
			'activeType' 	=> $params['parenttype'],
			'parenttype' 	=> $params['parenttype'],
		];
		
		return view('admin.msgtypelist',$data);
	}
		/**
	 * [addMsgType 添加信息]
	 */
	public function addMsgType(Request $request)
	{
		$parenttype = $request->input('parenttype','1');
		$data = [
			'parenttype' => $parenttype,
			'activeType' => $parenttype,
		];
		return view('admin.addmsgtype',$data);
	}
	/**
	 * [updateMsgType 修改信息]
	 * @return [type] [description]
	 */
	public  function updateMsgType(Request $request)
	{
		$parenttype = $request->input('parenttype','1');
		$tId = $request->input('tid',1);//模板id
		$msgTypeInfo = MsgType::getMsgTypeById($tId);
		return view('admin.updatemsgtype',['msgTypeInfo'=>$msgTypeInfo,'activeType'=>$parenttype]);
	}

	/**
	 * [saveMsgType 保存信息]
	 * @param  Request $request [description]
	 * @return [type]           [[]]
	 */
	public  function saveMsgType(Request $request)
	{
		$parenttype = $request->input('parenttype','1');
		if (!in_array($parenttype, array_keys(MsgType::$MSGTYPE) )) {
			$res = ['code'=>'-1','message'=>'父类型不存在','url'=>''];
		} else {
			$tId 		= $request->input('tid','');//模板类型id
			$msgtype 	= $request->input('msgtype','');//模板类型
			$author 	= $request->input('author','');//发布人
			$data 		= [
				'tid'		=> $tId,
				'msgtype'	=> $msgtype,
				'author'	=> $author,
				'parenttype'=> $parenttype,
			];
			//echo '<pre>';print_r($data);exit;
			$res = MsgType::saveMsgType($data);
		}
		
		//echo '<pre>';print_r($res);exit;
		return json_encode($res,JSON_UNESCAPED_UNICODE);
	}
	/**
	 * [deleteMsgType 删除信息]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 * author jiapeng.gong
	 */
	public function delMsgType(Request $request)
	{
		$tId = $request->input('id','');//模板id
		//echo '<pre>';print_r($tId);exit();
		$data = ['tid'=>$tId];
		$res = MsgType::deleteMsgTypeById($data);
		return json_encode($res,JSON_UNESCAPED_UNICODE);
	}
}
