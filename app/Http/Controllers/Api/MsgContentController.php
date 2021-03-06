<?php
namespace App\Http\Controllers\Api;
use URL;
use Illuminate\Support\Facades\Log;
use Cache;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\MsgContent;
use App\Models\Admin\MsgType;
use App\Models\Admin\MsgSchool;
use App\Jobs\SendWechatTmp;
use App\lib\common;

class MsgContentController extends Controller
{
    private $pageNum = 15;//每条条数
    /**
     * [addContent 内容发布]
     * @param Request $request [description]
     */
    public function saveContent(Request $request)
    {
        //获取签名
        $token = $request->input('token');
        $userInfo = Cache::store('redis')->get($token);
        // $userInfo['userId'] = 9;
        // $userInfo['nickname'] = 'YOU';
        $content = $request->input('content');
        $id    = $request->input('id','');
        $tid    = $request->input('tid');
        $sid    = $request->input('sid');
        $title  = $request->input('title');
        $remarks = $request->input('remarks');
        $imgurl  = $request->input('imgurl');
        //敏感词检查
        $checkWorld = common::checkWorld($title,$content,$remarks);
        if (!$checkWorld) {
            $res = [
                'code'=>'50008',
                'msg'=>['res'=>'您发布的信息含有敏感词！'],
            ];
            return  $this->failApi($res);
        }
        $data = [
            'id'        => $id,
            'tid'       => $tid,
            'sid'       => $sid,
            'title'     => htmlspecialchars($title),
            'remarks'   => htmlspecialchars($remarks),
            'imgurl'    => $imgurl,
            'content'   => htmlspecialchars($content),
            'userId'    => $userInfo['userId'],
            'userName'  => $userInfo['nickname'],
        ];

        //数据校验
        $validator = $this->checkAddData($data);

        if ($validator->fails()) {
            $errMessage = $validator->errors()->first();
            $res = [
                'code'=>'50003',
                'msg'=>['res'=>$errMessage],
            ];
            return  $this->failApi($res);
        }
        $res = MsgContent::saveMsgContent($data);
        if ($res) {
            $url = URL::action('Api\MsgContentController@getContentDetail',['id'=>$res]); 
            $tmpTypeList = MsgType::getMsgTypeALl();
            //关键字匹配 插入队列
            $tids = [];
            foreach ($tmpTypeList as $key => $value) {
                if (strstr($title, $value['msgtype'])) {
                    $tids[] = $key;
                }
            }
            if (!empty($tids)) {
                $sendData = [
                    'tids'  =>$tids,
                    'userId'=>$userInfo['userId'],
                    'title' =>$title,
                    'remarks'=>$remarks,
                    'url'   =>$url,
                    'imgurl'=>$imgurl,
                ];
                $this->sendWechatTmp($sendData);
            }
            return $this->detailApi(['res'=>$res]);
        } else {
            $res = [
                'code'=>'50002',
                'msg'=>'操作失败',
                'msg'=>['res'=>false],
            ];
            return  $this->failApi($res);
        }
    }
    /**
     * [checkData 数据检查]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    private function checkAddData(array $data)
    {
        $validator = Validator::make($data, [
            'tid'   => 'required|numeric',
            'sid'   => 'required|numeric',
            'title' => 'required|string|max:255',
            'content'=> 'required',
            'remarks'=> 'required|max:200',
            'imgurl'=> 'required|string',
            'userId'=> 'required|required',
        ]);
        return $validator;
    }
    /**
     * [getContentList 获取首页列表]
     * @return [type] [description]
     */
    public function getContentList(Request $request)
    {
        $params['tid'] = $request->input('tid','');//当前页数
        $params['sid'] = $request->input('sid','');//当前页数
        $params['page'] = $request->input('page',1);//当前页数
        $params['pageNum'] = $this->pageNum;//每页显示的条数
        //echo '<pre>';print_r($params);exit;
        $list = MsgContent::getMsgContentLists($params);
        //类型
        $tmpTypeList = MsgType::getMsgTypeALl();
        //获取学校
        $schoolList = MsgSchool::getMsgSchoolALl();
        $data = [
            'list'      =>$list,
            'tmplist'   =>$tmpTypeList,
            'schoollist'=>$schoolList,
            'params'    =>$params
        ];
        return $this->detailApi($data);
    }
    /**
     * [getContentInfoById 根据id获取发布的信息]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getContentDetail(Request $request)
    {
        $id = $request->input('id','');//当前页数
        $validator = $this->checkId($id);

        if ($validator->fails()) {
            $errMessage = $validator->errors()->first();
            $res = [
                'code'=>'50003',
                'msg'=>'操作失败',
                'msg'=>['res'=>$errMessage],
            ];
            return  $this->failApi($res);
        }

        $msgInfo = MsgContent::getMsgContentById($id);
        return $this->detailApi($msgInfo);
    }
    /**
     * [delContentInfo 删除]
     * @return [type] [description]
     */
    public function delContentInfo(Request $request)
    {
        //获取用户信息
        $token = $request->input('token');
        $userInfo = Cache::store('redis')->get($token);
        $userId = $userInfo['userId'];
        
        $id = $request->input('id','');//当前页数
        $validator = $this->checkId($id,$status);

        if ($validator->fails()) {
            $errMessage = $validator->errors()->first();
            $res = [
                'code'=>'50003',
                'msg'=>'操作失败',
                'msg'=>['res'=>$errMessage],
            ];
            return  $this->failApi($res);
        }
        $res = MsgContent::deleteMsgById($id,$userId);
        return $this->detailApi($res);
    }

    /**
     * [updateContentStatus 上下架]
     * @return [type] [description]
     */
    public function updateContentStatus(Request $request)
    {
        //获取用户信息
        $token = $request->input('token');
        $userInfo = Cache::store('redis')->get($token);
        $userId = $userInfo['userId'];

        $id = $request->input('id','');//当前页数
        $status = $request->input('status','');//当前页数
        $validator = $this->checkId($id,$status,$userId);
        if (!in_array($status, [0,1,2])) {
            $res = [
                'code'=>'50003',
                'msg'=>'操作失败',
                'msg'=>['res'=>'参数错误'],
            ];
            return  $this->failApi($res);
        }
        if ($validator->fails()) {
            $errMessage = $validator->errors()->first();
            $res = [
                'code'=>'50003',
                'msg'=>'操作失败',
                'msg'=>['res'=>$errMessage],
            ];
            return  $this->failApi($res);
        }
        
        $res = MsgContent::updateStatusContent($id,$status);
        return $this->detailApi($res);
    }

    /**
     * [checkId 检查id]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function checkId($id)
    {
        $validator = Validator::make(
            ['id'=>$id], 
            ['id' => 'required|numeric']
        );
        return $validator;
    }
    /**
     * [sendWechatTmp 加入消息队列]
     * @param  [type] $tids [description]
     * @return [type]       [description]
     */
    private function sendWechatTmp($data)
    {
        $res = $this->dispatch(new SendWechatTmp($data));
        Log::info('用户id为：'.$data['userId'].'发布的：“'.$data['title'].'”匹配的结果：'.json_encode($data['tids']).' 入队列结果: '.$res);
        return true;
    }


}
