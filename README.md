###"亿颗赛艇"在线支付系统 for 软件工程基础
####version 2.0

###目录结构
* client_system: 客户端系统 [传送门](http://tx.zhelishi.cn:8090)
	* 个人账户管理系统.
	* 订单管理、支付事务处理系统. 
	* 酒店、机票订购系统
* payment_audit: 审计系统 [传送门](http://tx.zhelishi.cn/audit)
* ops-system-administration: 系统管理系统 [传送门](https://tx.zhelishi.cn:3000)

###技术选型:
####前端:
* HTML+CSS+Javascript
* 用户系统: Angular.js模块化，基于ui.router开发SPA.
* 管理系统: React.js.

####后端:
* 用户系统：PHP.
* 审计系统: PHP.
* 管理系统：PHP + Node.js.

####How to Run in localhost
#####请务必阅读各子系统的read.me
1、方案一：在Apache配置文件http.conf中，配置Apache监听端口及默认根目录至项目目录下。再将/frontend/index.js文件中的"http://localhost:63342/onlinePayment/” 删掉。
<br>2、方案二：在phpstorm的工程目录下直接启用live-server（或其他轻量级服务器），且不需要改/frontend/index.js。
<br>3、以上两个方案是已验证的可实现Ajax同源加载的行为，推荐方案二。

####Tips for Test
1、给各位添加了拥有233333RMB的测试用账户。账号：testadmin；密码：123456。
<br>2、邮件有时会发送延迟，有时会被当做垃圾邮件，请测试邮箱功能时注意留意垃圾箱。
<br>3、测试用例：
	* 航班：杭州到柳州、杭州到上海，时间为2016-6-24.
	* 酒店：浙江大学、杭州、上海，时间为2016-6-24
<br>4、出现Bug请立刻报告。
