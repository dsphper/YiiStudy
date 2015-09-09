# YiiStudy
Yii框架的路由以安全方面的研究  
* 基础核心类共有`69`个
* 其中抽象类共有`10`个
* 接口类共有`15`个  
* 其余全为动态按需加载 详情见: <a href="#user-content-dongtaijiazai">动态加载原理</a>   

### Yii框架目录结构
```php
.
├── base // 底层核心类库
├── caching // 所有的缓冲存放的位置
├── cli // 项目命令行生成脚本
├── collections // PHP语言构造的数据存储单元.队列,栈,哈希等等......
├── console // Yii 框架控制台相关PHP代码.
├── db // 数据库相关操作类库
├── gii // 代码生成器 (脚手架, 可以生成模型, 控制器, 视图等代码)
├── i18n // 国际化(就是让网站实现多语言访问)
├── logging // 日志功能相关类库
├── messages // 存放的是与国际化相关的语言包
├── test // 用于单元测试的文件夹
├── utils // 单元测试相关类库
├── validators // 存放着各种自动验证的相关类库
├── vendors // 第三方文档 / 资料
├── views // 包含了Yii 框架自带的错误页面,日志,配置文件的多语言视图.
├── web // Yii 所有与开发相关的文件
└── zii // 用于前端开发的各种插件 

```
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
这么写是与上面的`QueryString`  方式的传参是完全等价的, 至于这里的如果有想深入的同学可以去研究下`http协议规范`.  
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
在这里我简单的实现了一个框架[传送门](https://github.com/dsphper/YiiStudy/blob/master/framework.php)  
![流程图](http://i3.tietuku.com/c579bc76a3de85e9.png)  

上面的流程图, 简单的展示了框架是如何分配控制器以及方法的.  
如果看懂了上面的逻辑图, 那么对咱们下面学习如何处理Path参数的学习,会更加快速.  
如上图所示的无论用户传入的是怎么样美化过的URL最终都需要被转化为$_GET的参数.  
大家都知道, $_GET这个全局变量里面的参数实际是PHP帮咱们进行`QueryString`的拆分. 
并且PHP只支持`QueryString`的自动处理.
如果大家直接以"http://www.xxxxx.com/index.php/admin/admin/index/id/1231"  这种方式访问.  
你去$_GET || $_POST || $_REQUEST 这几个全局变量里面是都无法获取到index.php往后的URI参数的.  
那么这里该怎么获取呢?????????  
别担心, 世界上最好的语言已经为我们准备好解决方案了.
```php
<?php
var_dump($_SERVER['PATH_INFO']);
// 直接打印这个函数, 将获得以下结果.
string(26) "/admin/admin/index/id/1231"
```
看到这里是不是
<img src="http://img4q.duitang.com/uploads/item/201501/07/20150107194411_VQEAy.thumb.700_0.jpeg" width="200" height="200"/>  

大家是不是知道应该怎么做了?
下面这个函数就展了如何将Path参数转换为QueryString 存放到GET里面
```php
//PathInfo 路由规则
function PathInfo()
{
	$_GET['m'] = empty($_GET['m']) ? 'index' : $_GET['m'];
	$_GET['c'] = empty($_GET['c']) ? 'index' : $_GET['c'];
	!empty($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'];
	if (!empty($_SERVER['PATH_INFO'])) {
		$path = $_SERVER['PATH_INFO'];
		$get  = explode('/', $path);
		unset($get[0]);
		foreach ($get as $key => $value) {
			if ($key > 0) {
				if (count($get) == 1) {
					$_GET['c'] = $get[1];
				} else if (count($get) == 2) {
					$_GET['c'] = $get[1];
					$_GET['a'] = $get[2];
				} else if (count($get) > 2) {
					$_GET['m'] = $get[1];
					$_GET['c'] = $get[2];
					$_GET['a'] = $get[3];
				}
			}
			if (count($get) > 4) {
				$param = array_slice($get, 3);
				$param = array_filter($param);
				for ($i = 0; $i < count($param); $i += 2) {
					if ($param[$i] != 'm' && $param[$i] != 'a' && $param[$i] != 'c' && !empty($param[$i + 1])) {
						$_GET[$param[$i]] = $param[$i + 1];
					}
				}
			}
		}
	}
}
```
到这里核心原理已经讲解的差不多了, 后面其他的一些功能比如rules的控制, 自定义url美化规则......等等都可以在这个函数的基础上继续添加只需要最后保证参数m与参数c与参数a最终能获得正确的值就可以.
咱们接下来看一下Yii框架为咱们提供了那些Route功能.

##### Yii框架Url 配置项
```php
'urlManager'=>array(
	'urlFormat'=>'path', // 设置路由访问模式
	'urlSuffix' => '.html', // 伪静态后缀
	'showScriptName' => false, // 去掉 index.php (True | False)
	'rules'=>array(  // URL 规则定义 非常的强大 可以随心所欲的自定义自己的URL规则
		'post/<id:\d+>/<title:.*?>'=>'post/view',
		'jun/<id:\d+>/<title:.*?>'=>'post/jun',
		'haha/haha' => [
			'post/jun',
			'urlSuffix' => '.html', // 仅对生成url时有效
			'defaultParams' => ['a'=>'v'], // 默认注入参数
			'caseSensitive' => false, // 是否区分大小写
			'verb' => 'GET' // 设置当前规则适用的场景 如GET POST PUT DELETE等等......
		],
		'posts/<tag:.*?>'=>'post/index',
		'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
	),
),
```
可以看到上面存在了很多配置项, 下面咱们就一一探究每个配置项对应的功能是什么.
在Yii框架中默认支持两种URL访问模式  

###### GET 方式
```url
index.php?r=/post/jun/jun
```
###### Path 方式
```url
index.php/post/jun/jun
```
如果想切换路由模式很简单只需要在配置项 `urlFormat` 这一项中将对应的 value 更改成 get || path 就可以了. (这里只支持输入小写字母)
#### <a name="dongtaijiazai"></a>动态按需加载

 
  
