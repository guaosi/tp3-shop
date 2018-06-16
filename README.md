基于ThinkPHP3.2.3制作电子商城,支持PHP7.
===============
> 🚀 搜索使用`Sphinx`,页面开启缓存,加快电子商城的基础速度.
## 特性

- 使用Sphinx作为搜索引擎

- 搜索结果可以根据商品筛选条件再次筛选

- 接入支付宝，实现在线支付

- 回复支持楼层内回复

- 根据商品属性购买商品

- 后台完整的RBAC权限控制

- 支持商品属性库存量设定

- 无限级分类

- 支持商品扩展分类

> ThinkPHP3的运行环境要求PHP5.3以上。

## 体验地址

> http://shoptp3.guaosi.com

## 要求

| 依赖 | 说明 |
| -------- | -------- |
| PHP| >=`5.3` |
| Thinkphp| `3.2.3` |
| MySQL| >=`5.5` |
| nginx |用于网址代理解析|
| 集成环境[可选的] | LNMP`>=1.5` |

## 注意

1. 自行导入数据库。
2. 必须安装Sphinx，否则搜索没有结果。
3. PHP7内置了`SphinxClient`,PHP5版本需要修改。
4. 做了一次网站迁移上传的,应该没有什么问题,有关问题下面会说明.

## 安装

通过[Github](https://github.com/guaosi/tp3-shop),fork到自己的项目下
```
git clone git@github.com:<你的用户名>/tp3-shop.git
```

## 支付宝配置

个人用户可以使用支付宝沙箱进行测试。
修改 /alipay2/config.php
根据字段要求自行填写

## 数据库配置

修改 /Application/Common/conf/config.php
自行写入数据库账号密码

> 创建数据库名为php39，自行导入php39.sql

## 缓存配置
使用的是ThinkPHP3.2.3自带的缓存,默认开启`商城首页`与`商品详情页`的缓存，如果修改请到如下文件自行修改
> /Application/Home/Conf/config.php

## PHP5修改
因为PHP7内置了Sphinx的API,所以没有加载 `SphinxClient` 类,需要手动添加。
找到  `/Application/Home/Controller/SearchController.php` 中的 `key_search` 方法，打开 `require_once './sphinxapi.php';` 注释即可.

## sphinx
### 安装
1. 根据下面网址进行安装

> http://www.bkjia.com/Linuxjc/938671.html 

其中的 `phinx-1.3.2.tgz` 替换为 `sphinx-339e123.tar.gz` 支持php7(php5不用替换)

2. 安装过程中,若在出现报错
```
cd csft-3.2.14
./configure
make&&make install
```
报错内容:
> undefinedreferenceto`libiconv_open’

解决办法:
```
vi /usr/local/coreseek-3.2.14/csft-3.2.14/configure
将
#define USE_LIBICONV 1
改为
define USE_LIBICONV 0
```

### 配置
安装完成后进行文件迁移

>将 /linux下的sphinx/update.sh 复制到 /usr/local/coreseek下

> /linux下的sphinx/sphinx.conf 复制到 /usr/local/coreseek/etc下

修改sphinx.conf文件,自行写入数据库账号密码

然后执行

```
 /usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/sphinx.conf goods
```
如果出现如下错误

> Can't connect to local MySQL server through socket '/var/lib/mysql/mysql.sock

解决方法如下:

```
vim /usr/local/coreseek/etc/sphinx.conf
在每个source中加入 sql_sock=/tmp/mysql.sock
```

添加定时任务,自动刷新商品索引

```
crontab -e
加入
*/5 * * * * /usr/local/coreseek/update.sh
```

## 使用

后台开启(每次重启服务器都要开启，自动后台)
```
/usr/local/coreseek/bin/searchd -c /usr/local/coreseek/etc/sphinx.conf
```
余下的都是参考说明
```
初次增加索引
/usr/local/coreseek/bin/indexer -c /usr/local/coreseek/etc/sphinx.conf goods
后续增加索引
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf   goods_del  --rotate
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf --merge goods goods_del  --merge-dst-range is_updated 0 0 --rotate
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf   goods_zl  --rotate
/usr/local/coreseek/bin/indexer -c  /usr/local/coreseek/etc/sphinx.conf --merge goods goods_zl --merge-dst-range is_updated 0 0 --rotate
```

## Nginx配置
这个需求应该不大，就不写了.

## 测试账号与密码
以上都完成后
前后台登录账号密码

> admin  a123654

后台地址:
> http://网址/Admin/login/login.html
