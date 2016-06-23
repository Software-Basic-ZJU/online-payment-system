### 在线支付系统

#### 技术选型
##### 前端:
* 系统管理：react 
* 个人账户处理、支付事务处理系统、在线订购系统：angular 
* 使用了轻量的服务器live-server做前后端分离

##### 后端:
统一使用PHP

#### How to run this version
1. git clone
2. 安装npm (也可直接安装node.js,自带了npm.具体请自行百度)
3. npm install -g bower live-server
4. bower install
5. liver-server --port=9999(可选)

关于安装出现的问题：
mac下全局安装会出现问题。请执行以下指令：
* export PATH=/usr/local/lib/node_modules/bower/bin:$PATH
* export PATH=/usr/local/lib/node_modules/live-server:$PATH

bower install 无权限问题:
* sudo bower install --allow-root 
或者
* sudo chown -R xxx:xxx /Users/Andreas/.config/configstore/

打开方式：
首页url:  运行live-server后会在浏览器中自动打开。
应用页：点击登录按钮直接登录即可。