# Face To Face

## 项目简介

face to face 是基于laravel 实现的校园面对面交易的二手市场，不支持在线交易。

## 搭建方式

把.env里面的配置改成你自己的就可以，数据表导入库(sql.txt)

## 功能介绍
项目主要是基于微信公众号开发的信息发布和信息订阅，假如你订阅了一个标签，别人发布了这个标签的商品，你立刻就可以收到一条信息，然后双方线下交易。
亮点就是你不需要时时关注，只需要关注公众号，就会收到你想要的信息

##接口信息
```
管理后台：注释路由代码里面都有
messagepush/routes/web.php/admin/  首页
messagepush/routes/web.php/admin/contentlist 
messagepush/routes/web.php/admin/addcontent
messagepush/routes/web.php/admin/savecontent
messagepush/routes/web.php/admin/updatecontent 
messagepush/routes/web.php/admin/deletecontent
messagepush/routes/web.php/admin/opinionlist
messagepush/routes/web.php/admin/getopinion 
messagepush/routes/web.php/admin/delopinion 
messagepush/routes/web.php/admin/msgtypelist 
messagepush/routes/web.php/admin/addmsgtype 
messagepush/routes/web.php/admin/savemsgtype 
messagepush/routes/web.php/admin/updatemsgtype 
messagepush/routes/web.php/admin/delmsgtype 
messagepush/routes/web.php/admin/schoollist 
messagepush/routes/web.php/admin/addschool 
messagepush/routes/web.php/admin/saveschool 
messagepush/routes/web.php/admin/updateschool 
messagepush/routes/web.php/admin/delschool
messagepush/routes/web.php/admin/weichatmenulist
messagepush/routes/web.php/admin/weichataddmenu

h5 数据接口
messagepush/routes/web.php/wap/index
messagepush/routes/web.php/wap/schoollist
messagepush/routes/web.php/wap/msgtypelist
messagepush/routes/web.php/wap/addcontent
messagepush/routes/web.php/wap/usercenter
messagepush/routes/web.php/wap/usercontentlist
messagepush/routes/web.php/wap/userajaxcontentlist
messagepush/routes/web.php/wap/usermsgtypelist
messagepush/routes/web.php/wap/cancelmsgtype
messagepush/routes/web.php/wap/wechatlogin
messagepush/routes/web.php/wap/userlogin
messagepush/routes/web.php/wap/login
messagepush/routes/web.php/wap/upload
messagepush/routes/web.php/wap/doupload
messagepush/routes/web.php/wap/savecontent
messagepush/routes/web.php/wap/contentlist
messagepush/routes/web.php/wap/ajaxcontentlist
messagepush/routes/web.php/wap/contentdetail
messagepush/routes/web.php/wap/delcontent
messagepush/routes/web.php/wap/updatecontentstatus
messagepush/routes/web.php/wap/doaddopinion
messagepush/routes/web.php/wap/addopinion
messagepush/routes/web.php/wap/wechatback
messagepush/routes/web.php/wap/wechatfollow
messagepush/routes/web.php/wap/wechatsubscribe
messagepush/routes/web.php/wap/wechatsendtmp


接口信息：
messagepush/routes/api.php/里面（注释里面都有）
```
### 消息队列
```
用户订阅了标签 别人发布改标签信息的时候触发 需要配置redis
redis的配置在.env里面
job目录为：messagepush/Jobs/SendWechatTmp.php

执行命令 php artisan queue:listen(常驻进程)
```

### h5转小程序
```
1：需要修改授权登陆那块
2：把web下的接口转移到api下即可（api下面已经有一部分）
```








