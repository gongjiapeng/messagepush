<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * [detailApi 接口成功返回值]
     * @param  [type] $mContent [description]
     * @return [type]           [description]
     */
    public function detailApi($mContent)
    {
        if (is_object($mContent) && method_exists($mContent, 'toArray')) {
            $mContent = $mContent->toArray();
        }
        if (isset($mContent['msg'])) {
            $msg = $mContent['msg'];
        } else {
            $msg = '操作成功';
        }
        $res = [
            'code' => 0,
            'msg' => $msg,
            'data'    => $mContent['data'],
        ];
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }
    /**
     * 失败api返回
     */
    public  function failApi($mContent)
    {
        if (is_object($mContent) && method_exists($mContent, 'toArray')) {
            $mContent = $mContent->toArray();
        }
        if (isset($mContent['msg'])) {
            $msg = $mContent['msg'];
        } else {
            $msg = '操作失败';
        }
        $res =  [
            'code'=>$mContent['code'],
            'msg'=>$msg,
            'data'=>$mContent['data']
        ];
        return json_encode($res,JSON_UNESCAPED_UNICODE);
    }
}
