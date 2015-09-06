# YiiStudy
Yii框架的路由以安全方面的研究  
* 基础核心类共有`69`个
* 其中抽象类共有`10`个
* 接口类共有`15`个  
* 其余全为动态按需加载 详情见: <a href="#dongtaijiazai">动态加载原理</a>

框架加载与运行流程  
--------
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

Yii原理详解
-----------
### <a name="dongtaijiazai">动态按需加载

 
 
