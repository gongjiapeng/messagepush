<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\MsgOpinion;
use URL;
use Cache;
use Validator;
class MsgOpinionController extends Controller
{
    /**
     * [addOpinion 添加意见]
     * @param Request $Request [description]
     */
    public function addOpinion(Request $request)
    {
        $redirectUrl = URL('api/v01/userlogin');
        $token = $request->input('token','ecc7469cf4c091c8ad954520d510abb4');
        $userInfo = Cache::store('redis')->get($token);
        if(empty($userInfo)) {
            return redirect($redirectUrl);
        }
        $data['content'] = htmlspecialchars($request->input('content'));
        $data['userName'] = $userInfo['nickname'];
        $data['userId'] = $userInfo['userId'];
        //数据校验
        $validator = $this->checkAddData($data);

        if ($validator->fails()) {
            $errMessage = $validator->errors()->first();
            $res = [
                'code'=>'50003',
                'msg'=>'操作失败',
                'msg'=>['res'=>$errMessage],
            ];
            return  $this->failApi($res);
        }
        $res = MsgOpinion::saveMsgOpinion($data);
        //echo '<pre>';print_r($res);exit;
        return $this->detailApi(['res'=>$res]);
        
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
            'content'=> 'required|max:255',
        ]);
        return $validator;
    }
   
}