<?php
namespace App\Http\Controllers\Wap;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\MsgOpinion;
use URL;
use Cache;
use Validator;
use App\lib\common;
class MsgOpinionController extends Controller
{
    private $sendCount = 5;//每天发送次数
    /**
     * [addOpinion 添加意见]
     * @param Request $Request [description]
     */
    public function addOpinion(Request $request)
    {
        //$userInfo = ['nickname'=>'YOU','userId'=>60];
        $redirectUrl = URL('wap/userlogin');
        $token = $request->session()->get('token');
        $userInfo = Cache::store('redis')->get($token);
        if(empty($userInfo['userId'])) {
            return redirect($redirectUrl);
        }
        $data['content'] = htmlspecialchars($request->input('content'));
        $data['userName'] = $userInfo['nickname'];
        $data['userId'] = $userInfo['userId'];
        //数据校验
        $validator = $this->checkAddData($data);

        if ($validator->fails()) {
            $errMessage = $validator->errors()->first();
            //echo '<pre>';print_r($validator->errors());
            $res = [
                'code'=>'50011',
                'msg' => '操作失败',
                'data'=>['res'=>$errMessage],
            ];
            return  $this->failApi($res);
        }
         //敏感词检查
        $checkWorld = common::checkWorld('',$data['content'],'');

        if (!$checkWorld) {
            $res = [
                'code'=>'50008',
                'data'=>['res'=>'您吐槽的信息含有敏感词！'],
            ];
            return  $this->failApi($res);
        }
        $count = MsgOpinion::getMsgCountByUserId($userInfo['userId']);
       
        if ($count >= $this->sendCount) {
            $res = [
                'code'=>'50012',
                'msg' => '不好意思！',
                'data'=>['res'=>'孩子，您已超过今天的吐槽次数，明天再来吧！'],
            ];
            return  $this->failApi($res);
        }
        $result = MsgOpinion::saveMsgOpinion($data);
        if ($result) {
            $res = [
                    'msg'=>'吐槽成功',
                    'data'=>['redirect'=>URL('wap/index')],
            ];
            return $this->detailApi($res);
        } else {
             $res = [
                'code'=>'50013',
                'data'=>['res'=>'不好意思'],
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
            'userId'   => 'required|numeric',
            'content'=> 'required|max:5',
        ]);
        return $validator;
    }
   
}