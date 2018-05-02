<?php
namespace App\Http\Controllers\Wap;
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
        $upload->set("maxsize",5000000); 
        $res = $upload->upload("file");
        if($res['status']=='success'){
            //获取上传成功后文件名字  
            $imgurl = URL::asset('/').'img/'.$res['img_url'];
            $res = [
                'msg'=>'上传成功',
                'data'=>['imgurl'=>$imgurl],
            ];
            return $this->detailApi($res);
        } else {  
            $res = [
                'code'=>'50001',
                'msg'=> $res['msg'],
                'data'=>['res'=>$res],
            ];
            return  $this->failApi($res);
        }  
    }
}
