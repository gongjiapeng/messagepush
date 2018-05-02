<?php
namespace App\lib;
/**
 * 文件上传类
 */
class UploadFile
{
    private $path = "./uploads";          //上传文件保存的路径  
    private $allowtype = ['image/jpg','image/gif','image/png','image/jpeg']; //设置限制上传文件的类型  
    private $maxsize = 5000000;           //限制文件上传大小（字节)
    private $newFileName;              //新文件名  
    public function set($key, $val)
    {  
        $key = strtolower($key);   
        if( array_key_exists( $key, get_class_vars(get_class($this) ) ) ){  
            $this->setOption($key, $val);  
        }  
        return $this;  
    }
    /**
     * [upload 上传]
     * @param  [type] $fileField [description]
     * @return [type]            [description]
     */
    public function upload($fileField)
    { //echo '<pre>';print_r($_FILES);exit;
        $file = $_FILES[$fileField]['name'];  
        $root = env('UPLOAD_PATH');  
        $pre = rand(999,9999).time();  
        $ext = strrchr($file,'.');  
        $newName = $pre.$ext;

        $im = $_FILES[$fileField]['tmp_name']; //上传图片资源  
        $maxwidth="120"; //设置图片的最大宽度  
        $maxheight="120"; //设置图片的最大高度  
        $imgname = $root.'/'.$newName;  //图片存放路径 根据自己图片路径而定  
        $fileType=$_FILES[$fileField]['type'];//图片类型
        
        $fileSize = $_FILES[$fileField]['size'];//图片大小
 
        if (!$this->checkFileType($fileType)) {
            $out = ['msg'=>'上传格式错误','status'=>'error','img_url'=>'']; 
            return  $out;
        }

        if (!$this->checkFileSize($fileSize)) {
            $out = ['msg'=>'上传过大le','status'=>'error','img_url'=>'']; 
            //echo '<pre>';print_r($out);exit;
            return $out;
        }
        
        if( !$this->checkFilePath() ) {  
            $out = ['msg'=>'路径不存在','status'=>'error','img_url'=>''];  
            return  $out;
        }
       //压缩保存
        $result = $this->thumbImage($im,$maxwidth,$maxheight,$imgname,$fileType);  
        if($result){  
            $out = ['msg'=>'上传成功','status'=>'success','img_url'=>$newName]; 
        }else{  
            $out = ['msg'=>'上传失败','status'=>'error','img_url'=>'']; 
        }
        return $out;
    }
     /* 为单个成员属性设置值 */  
    private function setOption($key, $val) {  
        $this->$key = $val;  
    } 
    /**
     * [checkFileSize 检查图片大小]
     * @param  [type] $fileSize [description]
     * @return [type]           [description]
     */
    private function checkFileSize($fileSize)
    {
        if ($fileSize > $this->maxsize) {
            return false;
        }
        return true;
    }
    /**
     * [checkFileType 检查图片格式]
     * @param  [type] $fileType [description]
     * @return [type]           [description]
     */
    private function checkFileType($fileType)
    {
        if (in_array($fileType, $this->allowtype)) {
            return true;
        }
        return false;
    }
     /* 检查是否有存放上传文件的目录 */ 
    private function checkFilePath() {  
        if(empty($this->path)){  
            $this->setOption('errorNum', -5);  
            return false;  
        }  
        //echo file_exists($this->path);exit;
        //echo $this->path;exit;
        if (!file_exists($this->path) || !is_writable($this->path)) {  
            if (!@mkdir($this->path, 0755)) {  
                $this->setOption('errorNum', -4);  
                return false;  
            }  
        }  
        return true;  
    }
    /**
     * [thumbImage 压缩并保存]
     * @param  [type] $im        [description]
     * @param  [type] $maxwidth  [description]
     * @param  [type] $maxheight [description]
     * @param  [type] $name      [description]
     * @param  [type] $filetype  [description]
     * @return [type]            [description]
     */
    public function thumbImage($im,$maxwidth,$maxheight,$name,$filetype)  
    {  
        $im = $this->__createImageByType($filetype,$im);
        
        $resizewidth_tag = $resizeheight_tag = false;  
        $pic_width = imagesx($im);  
        $pic_height = imagesy($im); 
        if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))  {  
            $resizewidth_tag = $resizeheight_tag = false;  
  
            if($maxwidth && $pic_width>$maxwidth) {  
                $widthratio = $maxwidth / $pic_width;  
                $resizewidth_tag = true;  
            }  
            if($maxheight && $pic_height>$maxheight) {  
                $heightratio = $maxheight / $pic_height;  
                $resizeheight_tag = true;  
            }  
            if($resizewidth_tag && $resizeheight_tag) {  
                if($widthratio < $heightratio)  
                 $ratio = $widthratio;  
                else  
                 $ratio = $heightratio;  
            }  
            if($resizewidth_tag && !$resizeheight_tag)  
            $ratio = $widthratio;  
            if($resizeheight_tag && !$resizewidth_tag)  
            $ratio = $heightratio;  
            $newwidth = $pic_width * $ratio;  
            $newheight = $pic_height * $ratio;  
            if(function_exists("imagecopyresampled")) {  
                $newim = imagecreatetruecolor($newwidth,$newheight);//PHP图片处理系统函数  
                imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP图片处理系统函数  
            }  else {  
                $newim = imagecreate($newwidth,$newheight);  
                imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);  
            }
            switch ($filetype) {       
                case 'image/jpg' :       
                case 'image/jpeg' :       
                    $result = imagejpeg($newim,$name);     
                    break;       
                case 'image/gif' :       
                    $result = imagegif($newim,$name);     
                    break;       
                case 'image/png' :       
                    $result = imagepng($newim,$name);      
                    break;  
                case 'image/wbmp' :       
                    $result = imagewbmp($newim,$name);      
                    break;               
            }   

            imagedestroy($newim);  
        } else {
            switch ($filetype) {       
                case 'image/pjpeg' :       
                case 'image/jpeg' :       
                    $result = imagejpeg($im,$name);     
                    break;       
                case 'image/gif' :       
                    $result = imagegif($im,$name);     
                    break;       
                case 'image/png' :       
                    $result = imagepng($im,$name);      
                    break;  
                case 'image/wbmp' :       
                    $result = imagewbmp($im,$name);      
                    break;               
            }  
        }  
        return $result;
    } 
    /**
     * [__createImageByType 图片上传]
     * @param  [type] $filetype [description]
     * @param  [type] $img      [description]
     * @return [type]           [description]
     */
    private function __createImageByType($filetype,$img)
    {
        switch ($filetype)
            {
                case 'image/png':
                    $im = imagecreatefrompng($img);
                    break;
                case 'image/bmp':
                    $im = imagecreatefromwbmp($img);
                    break;
                case 'image/gif':
                    $im = imagecreatefromgif($img);
                    break;
                case 'image/jpg':
                    $im = imagecreatefromjpeg($img);
                    break;
                case 'image/jpeg':
                    $im = imagecreatefromjpeg($img);
                    break;
              
                default:
                    $im = imagecreatefrompng($img);
                    break;
            }
        return $im;
    }
}