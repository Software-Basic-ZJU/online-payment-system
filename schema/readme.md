# 数据库设计说明

## 概览
|表名|说明|备注|模块|
|-|-|-|
|auditor|审计员信息|实体集|
|booking_admin|预订管理员信息|实体集|
|cancel|用户取消订单的事件|联系集|
|card|用户的银行卡|联系集|
|comment|用户对房间的评论|联系集|
|commodity|商品信息|实体集|
|complaint|用户对订单提起的申诉|事件: 用户-订单|
|flight|航班信息|实体集|
|hotel|宾馆信息|实体集|
|logistics|物流信息|联系: 订单状态|
|order_records|买方向卖方订货产生的订单|买方-卖方-货物 三元联系集|
|payment|交易记录|联系: 买方-订单|
|prepaid_card|预付卡|实体集|
|refund|退款记录|买方-订单|
|room|宾馆房间信息|实体集|
|room_time|宾馆房间占用信息||
|system_admin|系统管理员信息|实体集|
|transact_flow|各种事务的记录|联系集|
|user|用户信息|实体集|

## 实体集



