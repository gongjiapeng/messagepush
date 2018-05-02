<form action="{{url('/wap/doupload')}}" method="post" enctype="multipart/form-data" >  
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />  
    up pic:<input type="file" name="pic" value=""><br/>  
    
      
    <input type="submit" value="upload" /><br/>  
</form>  