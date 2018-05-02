<?php
namespace App\Http\Controllers\Api;
use URL;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\lib\UploadFile;
class UploadController extends Controller
{
    /**
     * [upload 图片上传测试]
     * @return [type] [description]
     */
    public function upload()
    {
        return view('index');
    }
    /**
     * [doupload 图片上传]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function doupload(Request $request)
    {
        $upload = new UploadFile();
        $upload->set("path",env('UPLOAD_PATH'));  
        $upload->set("maxsize",2000000); //kb  
        $upload->set("allowtype",array("png","jpg","jpeg"));//  
        $upload->set("israndname",false);//true:由系统命名；false：保留原文件名  
        if($upload->upload("pic")){
            //获取上传成功后文件名字  
            $imgurl = URL::asset('/').'img/'.$upload->getFileName();
            $data = [
                'imgurl'=>$imgurl,
            ];
            return $this->detailApi($data);
        } else {  
            $res = [
                'code'=>'50001',
                'msg'=>'上传失败',
                'msg'=>['res'=>$upload->getErrorMsg()],
            ];
            return  $this->failApi($res);
        }  
    }
}
