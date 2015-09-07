# YiiStudy
Yii框架的路由以安全方面的研究  
* 基础核心类共有`69`个
* 其中抽象类共有`10`个
* 接口类共有`15`个  
* 其余全为动态按需加载 详情见: <a href="#user-content-dongtaijiazai">动态加载原理</a> 

### 框架加载与运行流程  
##### Step1:WebApp 初始化与运行  
1.1、 加载 `YiiBase.php`,安装 `autoload` 方法;加载用户的配置文件;  
1.2、 创建 `WebApp` 应用,并对 `App` 进行初始化,加载部分组件,最后执行 `WebApp`  
##### Step2:控制器初始化与运行  
2.1、 加载 `request 组`件,加载 `Url 管理组件`,获得路由信息 `route=ControllerId/ActionId`  
2.2、 创建出控制器实例,并运行控制器  
##### Step3:控制器初始化与运行  
3.1、 根据路由创建出 `Action`  
3.2、 根据配置,创建出该 `Action` 的 `Filter`;  
3.3、 执行 `Filter` 和 `Action`  
##### Step4:渲染阶段  
4.1、 渲染部分视图和渲染布局视图  
4.2、 渲染注册的 javascript 和 css  

### Yii原理详解
#### <a name="route"></a>路由原理
##### 概念讲述 啰嗦一会
```
Web开发中不可避免的要使用到URL。用得最多的，就是生成一个指向应用中其他某个页面的URL了。  
开发者需要一个简洁的、集中的、统一的方法来完成这一过程。  
而开发中最常用的架构就为`MVC`说到`MVC架构`, 就一定离不开`Route`这个概念;   
```
在之前访问一个网站的时候常常会出现以`QueryString` 方式出现的`url` 访问方式 ;   

比如: `"http://www.xxxxx.com/index.php?m=index&c=index&a=login"` ;   
这种方式是采用`QueryString`方式去访问后端的指定模块&控制器&方法,这种方法虽然操作简单但是参数多了就比较臃肿最主要的是不利于`SEO`. 为了解决这种访问模式又有童鞋想出了`美化URL`的方法, 就是 `Path 参数传递`;   
这种方法不但美观并且利于`SEO优化`(和伪静态异曲同工);  
如: `"http://www.xxxx.com/index.php/index/index/login"`  
这种`URL`的访问得到的结果和上面是一模一样的.  
到这里就有一个问题了原来`QueryString` 方式访问可以往后台传参数现在的`Path`方式如何往后台传参数呢?  
其实聪明的同学应该已经明白了, 那是不是能再后面加上`QueryString` 方式的参数呢?  
如: `"index.php/index/index/login?admin=admin&xxx=xxx"`  
这样写其实是可以的,但是如果这么写那么`Path`方式的路由也就没有什么意义了.  
按照之前的规律, 咱们可以这么写 `"index.php/index/index/login/admin/admin/xxx/xxx"`  
这么写是与上面的`QueryString`  
方式的传参是完全等价的, 至于这里的如果有想深入的同学可以去研究下`http协议规范`.  
这里附上3个网站 
> * http://www.opendl.com/openxml/w3/
> * http://www.w3china.org/index.htm
> * http://www.ietf.org/rfc/rfc2616.txt  

##### 不废话了 进入正题  
那么上面的那些访问模式是怎么实现的呢?
首先我们知道现在的框架绝大部分都为单入口模式, 这种单入口模式的程序相对于多入口的程序有什么好处呢?  其实就是为了方便管理与控制(单入口模式配合路由那真是天造地设的一对呀! )   .

这里咱们回归到上面提到的3个参数, 把这三个参数代表的意思搞明白, 才能方便咱们继续向下学习.
```php
+ m  -> module      -> 模块
+ c  -> controller  -> 控制器
+ a  -> action      -> 方法
```
在这里我简单的实现了一个框架
[传送门](https://github.com/dsphper/YiiStudy/blob/master/framework.php)
#### <a name="dongtaijiazai"></a>动态按需加载

 
  
