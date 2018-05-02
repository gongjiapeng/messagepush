<?php
namespace App\lib;
/**
 * 敏感词过滤
 */
class Banned
{
    private $data;//数据
    private $match_banned;//违禁词规则
    private $match_mingan;//敏感词规则
    private $match_field;//字符类型规则
    private $finish_path=array();//已完成的路径
    private $finish_table=array();//已完成的表
    private $logtime;
    private  $path;
    private  $check_sub_dir;
    private  $check_type;
    private  $banned_from;
    private  $clean;
    private  $write_html;
    private  $write_log;
    private  $fornum;
    private  $bannedword;
    public   $document_root;
    public $_config=array(
        "path"=>'',
        "check_sub_dir"=>array('web'),
        'check_type'=>'file',
        'banned_from'=>'table',
        'clean'=>false,
        'fornum'=>20,
        'write_html'=>false,
        'write_log'=>false,
        'bannedword'=>true,);
 
 
    /**
     *初始化方法
     */
    public function __construct($config=array())
    {
 
        if(!empty($config)) {
            $this->_config = array_merge($this->_config, $config);
        }
        $this->BannedInit();
    }
 
    /**
     * 设置key
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->_config[$key] = $value;
    }
 
    /**
     * 读取key
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->_config[$key];
    }
 
    /**
     * 初始化违禁词和敏感词
     */
    private function BannedInit(){
        $this->logtime=date("YmdHis");
        $this->document_root=$_SERVER['DOCUMENT_ROOT'];
 
         //初始化加载违禁词 否则默认读取文件中的违禁词
       
        //敏感词正则规则
        $mingan_words= $this->getMinganWords();
        $this->match_mingan=$this->generateRegularExpression($mingan_words);
 
        //违禁词正则规则
        $banned_words= $this->getBannedWords();
        $this->match_banned=$this->generateRegularExpression($banned_words);
    
    }
 
    /**
     * 检测所有内容
     */
    public function checkAll(){
          //判断类型是数据库
         if($this->_config['check_type']=="file"){
           return  $this->checkFileAll();//检测文件
         }
    }
 
     
 
    /**
     * 检查文件
     */
    public function checkFileAll(){
        $path=$this->_config['path'];
        if(!$path){
            $path=$this->document_root."";
        }
 
        $laststr= substr($path, -1);
        if($laststr!="/"){
            $path.="/";
        }
        $dir_list=  $this->getDir($path);
        //判断是否包含子目录
        if(count($dir_list)>0){
            foreach ($dir_list as $dirkey=>$dirvalue){
                if(empty($dirvalue)){
                    continue;
                }
                //判断是否是允许检测的子目录
              //  if(in_array($dirvalue,$this->_config['check_sub_dir'])){
                    $subpath=$path.$dirvalue.'/';
 
                    $this->check_sub_dir($subpath);
             //   }
            }
        }
        return true;
    }
 
     
    /**
     * 检测单条内容
     */
    public function check($content){
       
        if($this->_config['check_type']=="file"){
            return  $this->check_file($content);//检测文件
        }
    }
 
     
    /**
     * 检测单条文本内容
     * @param $content
     */
    public function check_text($content){
        $res_mingan=array();$res_banned=array();
        $this->__check_content($content,$res_mingan,$res_banned);
        $data['mingan']=$res_mingan;
        $data['banned']=$res_banned;
        return $data;
    }
 
    /**
     * 检测单条文件内容
     * @param $content
     */
    public function check_file($content){
       $res=  $this->__check_file($content);
       $this->set_finish_path($content);
       return $res;
    }
 
 
    /**
     * 迭代检查子文件目录
     * @param $path
     */
    function check_sub_dir($path){
        $file_list=  $this->getFile($path);//获取文件目录
        if(count($file_list)>0){
            foreach ($file_list as $filekey=>$filevalue){
                if(empty($filevalue))
                    continue;
                $this->__check_file($filevalue);//执行检查文件
            }
        }
        $dir_list=  $this->getDir($path);//获取文件夹目录
        if(count($dir_list)>0){
            foreach ($dir_list as $dirkey=>$dirvalue){
                if(empty($dirvalue)){
                    continue;
                }
                $subpath=$path.$dirvalue.'/';
                $this->check_sub_dir($subpath);
            }
        }
    }
 
    /**
     * 验证单个文件
     * @param $filepath
     */
    function __check_file($filepath){
        //判断文件是否已经完成检查，如果已经完成则不需要检查
        if(!$filepath) return;
 
        if(in_array($filepath,$this->finish_path))
            return;
 
        if(!file_exists($filepath)) return;
 
        if(stripos($filepath,"mingan_words.txt")>0 || stripos($filepath,"banned_words.txt")>0 ){
            return ;
        }
        //判断文件如果是 违禁词或敏感词文件则跳过不处理
        $content =  file_get_contents($filepath);
        $res_mingan=array();$res_banned=array();
        $this->__check_content($content,$res_mingan,$res_banned);
        $data=array();
        if($res_mingan || $res_banned){//如果有敏感词或违禁词则写日志
 
            $this->write_log($filepath,$res_mingan,$res_banned);
            $this->write_html($filepath,$res_mingan,$res_banned);
            $data['mingan']=$res_mingan;
            $data['banned']=$res_banned;
        }
        //执行保存文件路径
        return $data;
 
    }
 
    /**
     * 检查内容
     * @param $content
     * @param $res_mingan
     * @param $res_banned
     */
    private function __check_content($content,&$res_mingan,&$res_banned){
        //检查敏感词
        $res_mingan=$this->check_words($this->match_mingan,$content);
 
        //检查违禁词
        $res_banned=$this->check_words($this->match_banned,$content);
    }
 
    /**
     * 检查敏感词
     * @param $banned
     * @param $string
     * @return bool|string
     */
    private function check_words($banned,$string)
    {    $match_banned=array();
        //循环查出所有敏感词

        $new_banned=strtolower($banned);
        $i=0;
        do{
            $matches=null;
            if (!empty($new_banned) && preg_match($new_banned, $string, $matches)) {
                $isempyt=empty($matches[0]);
                if(!$isempyt){
                    $match_banned = array_merge($match_banned, $matches);
                    $matches_str=strtolower($this->generateRegularExpressionString($matches[0]));
                    $new_banned=str_replace("|".$matches_str."|","|",$new_banned);
                    $new_banned=str_replace("/".$matches_str."|","/",$new_banned);
                    $new_banned=str_replace("|".$matches_str."/","/",$new_banned);
                }
            }
            $i++;
            if($i>$this->_config['fornum']){
                $isempyt=true;
                break;
            }
        }while(count($matches)>0 && !$isempyt);
 
        //查出敏感词
        if($match_banned){
            return $match_banned;
        }
        //没有查出敏感词
        return array();
    }
    /**
     * @describe 生成正则表达式
     * @param array $words
     * @return string
     */
    private function generateRegularExpression($words)
    {
        $regular = implode('|', array_map('preg_quote', $words));
        return "/$regular/i";
    }
    /**
     * @describe 生成正则表达式
     * @param array $words
     * @return string
     */
    private function generateRegularExpressionString($string){
          $str_arr[0]=$string;
          $str_new_arr=  array_map('preg_quote', $str_arr);
          return $str_new_arr[0];
    }
 
    
    /**
     * 写日志
     * @param $path
     * @param $content
     */
   private function write_log($location,$contentarr,$weijinciarr){
        if($this->_config['write_log']) {
            if (!$contentarr && !$weijinciarr) {
                return;
            }
            $content = $location;
            if (count($contentarr) > 0) {
                $content .= "," . count($contentarr) . "," . implode('|', $contentarr);
            } else {
                $content .= ",,";
            }
            if (count($weijinciarr) > 0) {
                $content .= "," . count($weijinciarr) . "," . implode('|', $weijinciarr);
            } else {
                $content .= ",,";
            }
            $content .= "\r\n";
            $filename =$this->document_root."/logs/file" . $this->logtime . "/file_bannwords.csv";
            /* 文件日志路径 */
        //    $file = './' . $filename;
            $file = $filename;
            if (!file_exists($file)) {
                $pathdir = dirname($file);
                if (!is_dir($pathdir)) {
                    mkdir($pathdir, 0775, true);
                }
                $content_title = "位置,敏感词数量,敏感词,违禁词数量,违禁词" . "\r\n";
                error_log(iconv('UTF-8', 'GB2312', $content_title), 3, $file);
            }
            error_log(iconv('UTF-8', 'GB2312', $content), 3, $file);
        }
    }
 
    /**
     * 打印到页面上
     * @param $filepath
     * @param $res_mingan
     * @param $res_banned
     */
   private function write_html($location,$res_mingan,$res_banned){
        if($this->_config['write_html']){
            print_r(iconv('GB2312','UTF-8',$location));
            if($res_mingan){
                print_r("  <font color='red'>敏感词（".count($res_mingan)."）：</font>".implode('|',$res_mingan));
            }
            if($res_banned){
                print_r("  <font color='red'>违禁词（".count($res_banned)."）：</font>".implode('|',$res_banned));
            }
            echo "<br>";
        }
    }
    /**
     * 保存已完成文件
     * @param $path
     */
   private function set_finish_path($path){
        if(!$path){
            return;
        }
        $content =$path. "\r\n";
       $filename=$this->document_root."/logs/file" . $this->logtime . "/banned_finish_path.txt";
        /* 文件日志路径 */
       // $file ='./' . $filename;
       $file = $filename;
        if (!file_exists($file)) {
            mkdir(dirname($file), 0775, true);
        }
        error_log(iconv('GB2312','UTF-8',$content), 3, $file);
    }
 
 
    //重置已完成文件
/*    function clean_finish_file(){
        $filename=$this->document_root."/logs/banned_finish_path.txt";
        file_put_contents($filename,'');
    }
*/
 
 
    //获取文件目录列表,该方法返回数组
    private  function getDir($dir) {
        $dirArray[]=NULL;
        if (is_dir($dir)) {
            try{
                if (false != ($handle = @opendir($dir))) {
                    $i = 0;
                    while (false !== ($file = @readdir($handle))) {
                        //去掉"“.”、“..”以及带“.xxx”后缀的文件
                        if ($file != "." && $file != ".." && !strpos("*" . $file, ".")) {
                            $dirArray[$i] = $file;
                            $i++;
                        }
                    }
                    //关闭句柄
                    @closedir($handle);
                }
            }catch (Exception $ex){
 
            }
        }
        return $dirArray;
    }
 
    //获取文件列表
    private  function getFile($dir) {
        $fileArray[]=NULL;
        if (false != ($handle = @opendir ( $dir ))) {
            $i=0;
            while ( false !== ($file = @readdir ( $handle )) ) {
                //去掉"“.”、“..”以及带“.xxx”后缀的文件
                if ($file != "." && $file != ".." && (strpos($file,".php") || strpos($file,".html"))) {
                    $fileArray[$i]=$dir.$file;
                    if($i==1000){//当同一个文件下超出1000个文件则跳出循环
                        break;
                    }
                    $i++;
                }
            }
            //关闭句柄
            @closedir ( $handle );
        }
        return $fileArray;
    }
    //获取敏感词文件
    private   function getMinganWords(){
 
        $shehuangwords=file_get_contents($this->document_root."/words/forbidden.txt");
       // $shehuangwords=iconv("GBK","UTF-8",$shehuangwords);
        $shehuangword_arr=explode("、",$shehuangwords);
        return $shehuangword_arr;
    }
    //获取违禁词文件
    private  function getBannedWords(){
        if($this->_config['bannedword']){
            $guanggaowords=file_get_contents($this->document_root."/words/banned.txt");
           // $guanggaowords=iconv("GBK","UTF-8",$guanggaowords);
            $guanggaowords_arr=explode("、",$guanggaowords);
            return $guanggaowords_arr;
        }else{
            return array();
        }
    }
 
}