/*
Navicat MySQL Data Transfer

Source Server         : 新腾讯云
Source Server Version : 50560
Source Host           : 118.126.105.155:3306
Source Database       : php39

Target Server Type    : MYSQL
Target Server Version : 50560
File Encoding         : 65001

Date: 2018-06-16 17:34:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for p39_admin
-- ----------------------------
DROP TABLE IF EXISTS `p39_admin`;
CREATE TABLE `p39_admin` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_admin
-- ----------------------------
INSERT INTO `p39_admin` VALUES ('1', 'admin', 'de48ad76dadd3d693eedeaa2713db7bb');

-- ----------------------------
-- Table structure for p39_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `p39_admin_role`;
CREATE TABLE `p39_admin_role` (
  `admin_id` mediumint(8) unsigned NOT NULL COMMENT '管理员ID',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色ID',
  KEY `admin_id` (`admin_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_admin_role
-- ----------------------------
INSERT INTO `p39_admin_role` VALUES ('3', '2');
INSERT INTO `p39_admin_role` VALUES ('3', '4');
INSERT INTO `p39_admin_role` VALUES ('4', '2');
INSERT INTO `p39_admin_role` VALUES ('6', '2');
INSERT INTO `p39_admin_role` VALUES ('6', '4');

-- ----------------------------
-- Table structure for p39_attribute
-- ----------------------------
DROP TABLE IF EXISTS `p39_attribute`;
CREATE TABLE `p39_attribute` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `attr_name` varchar(30) NOT NULL COMMENT '属性名称',
  `attr_type` enum('唯一','可选') NOT NULL COMMENT '属性类型',
  `attr_option_values` varchar(300) NOT NULL DEFAULT '' COMMENT '属性可选值',
  `type_id` mediumint(8) unsigned NOT NULL COMMENT '所属类型ID',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_attribute
-- ----------------------------
INSERT INTO `p39_attribute` VALUES ('2', '内存', '可选', '4G,8G,16G', '2');
INSERT INTO `p39_attribute` VALUES ('4', '颜色', '可选', '红色,黄色,蓝色,绿色', '5');
INSERT INTO `p39_attribute` VALUES ('5', 'cpu', '可选', '联发科,高通骁龙', '5');
INSERT INTO `p39_attribute` VALUES ('6', '品牌', '唯一', '', '2');
INSERT INTO `p39_attribute` VALUES ('7', '显卡', '唯一', '', '2');
INSERT INTO `p39_attribute` VALUES ('9', '网络制式', '唯一', '', '2');
INSERT INTO `p39_attribute` VALUES ('10', '硬盘', '可选', '500G,750G,1T,2T', '2');

-- ----------------------------
-- Table structure for p39_brand
-- ----------------------------
DROP TABLE IF EXISTS `p39_brand`;
CREATE TABLE `p39_brand` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `brand_name` varchar(150) NOT NULL COMMENT '品牌名称',
  `site_url` varchar(150) NOT NULL DEFAULT '' COMMENT '官方网址',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '品牌LOGO',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='品牌';

-- ----------------------------
-- Records of p39_brand
-- ----------------------------
INSERT INTO `p39_brand` VALUES ('1', '魅族', 'www.meizu.com', '/Public/Upload/2017-07-10/59625a1f7405b.jpg');
INSERT INTO `p39_brand` VALUES ('2', '小米', 'www.xiaomi.com', '/Public/Upload/2017-07-10/59625a2ae9ca2.jpg');
INSERT INTO `p39_brand` VALUES ('4', 'OPPO', 'www.oppo.com', '/Public/Upload/2017-07-10/59625a4411a22.jpg');

-- ----------------------------
-- Table structure for p39_cart
-- ----------------------------
DROP TABLE IF EXISTS `p39_cart`;
CREATE TABLE `p39_cart` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `attr_id` varchar(150) NOT NULL DEFAULT '' COMMENT '商品属性ID',
  `goods_number` mediumint(8) unsigned NOT NULL COMMENT '购买数量',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_cart
-- ----------------------------
INSERT INTO `p39_cart` VALUES ('2', '58', '177,178', '2', '4');

-- ----------------------------
-- Table structure for p39_category
-- ----------------------------
DROP TABLE IF EXISTS `p39_category`;
CREATE TABLE `p39_category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `cate_name` varchar(30) NOT NULL COMMENT '分类名称',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父ID，顶级ID为0',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_category
-- ----------------------------
INSERT INTO `p39_category` VALUES ('1', '家用电器', '0', '是');
INSERT INTO `p39_category` VALUES ('2', '手机、数码、京东通信', '0', '否');
INSERT INTO `p39_category` VALUES ('3', '电脑、办公', '0', '否');
INSERT INTO `p39_category` VALUES ('4', '家居、家具、家装、厨具', '0', '否');
INSERT INTO `p39_category` VALUES ('5', '男装、女装、内衣、珠宝', '0', '否');
INSERT INTO `p39_category` VALUES ('6', '个护化妆', '0', '否');
INSERT INTO `p39_category` VALUES ('8', '运动户外', '0', '否');
INSERT INTO `p39_category` VALUES ('9', '汽车、汽车用品', '0', '否');
INSERT INTO `p39_category` VALUES ('10', '母婴、玩具乐器', '0', '否');
INSERT INTO `p39_category` VALUES ('11', '食品、酒类、生鲜、特产', '0', '否');
INSERT INTO `p39_category` VALUES ('12', '营养保健', '0', '否');
INSERT INTO `p39_category` VALUES ('13', '图书、音像、电子书', '0', '否');
INSERT INTO `p39_category` VALUES ('14', '彩票、旅行、充值、票务', '0', '否');
INSERT INTO `p39_category` VALUES ('16', '大家电', '1', '是');
INSERT INTO `p39_category` VALUES ('17', '生活电器', '1', '是');
INSERT INTO `p39_category` VALUES ('18', '厨房电器', '1', '是');
INSERT INTO `p39_category` VALUES ('19', '个护健康', '1', '否');
INSERT INTO `p39_category` VALUES ('20', '五金家装', '1', '否');
INSERT INTO `p39_category` VALUES ('21', 'iphone', '2', '否');
INSERT INTO `p39_category` VALUES ('22', '冰箱123', '16', '否');
INSERT INTO `p39_category` VALUES ('23', '冰-1', '22', '否');
INSERT INTO `p39_category` VALUES ('24', '冰02', '23', '否');
INSERT INTO `p39_category` VALUES ('25', '测试ing', '22', '否');
INSERT INTO `p39_category` VALUES ('26', '我是车站', '16', '否');
INSERT INTO `p39_category` VALUES ('27', '再来一个', '16', '否');

-- ----------------------------
-- Table structure for p39_comment
-- ----------------------------
DROP TABLE IF EXISTS `p39_comment`;
CREATE TABLE `p39_comment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  `content` varchar(300) NOT NULL DEFAULT '' COMMENT '评论内容',
  `addtime` int(10) unsigned NOT NULL COMMENT '评论时间',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星级',
  `clicked` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '有用的次数',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_comment
-- ----------------------------
INSERT INTO `p39_comment` VALUES ('1', '63', '3', '不错', '1500520152', '5', '0');
INSERT INTO `p39_comment` VALUES ('2', '63', '3', '', '1500523909', '5', '0');
INSERT INTO `p39_comment` VALUES ('3', '63', '3', '', '1500523942', '5', '0');
INSERT INTO `p39_comment` VALUES ('4', '63', '3', '', '1500524465', '5', '0');
INSERT INTO `p39_comment` VALUES ('5', '63', '3', '', '1500524475', '4', '0');
INSERT INTO `p39_comment` VALUES ('6', '63', '3', '', '1500524482', '5', '0');
INSERT INTO `p39_comment` VALUES ('7', '63', '3', '', '1500524488', '3', '0');
INSERT INTO `p39_comment` VALUES ('8', '63', '3', '', '1500524547', '5', '0');
INSERT INTO `p39_comment` VALUES ('9', '63', '3', '', '1500524584', '5', '0');
INSERT INTO `p39_comment` VALUES ('10', '63', '3', '', '1500524591', '5', '0');
INSERT INTO `p39_comment` VALUES ('11', '63', '3', '', '1500524598', '4', '0');
INSERT INTO `p39_comment` VALUES ('12', '63', '3', '', '1500524605', '1', '0');
INSERT INTO `p39_comment` VALUES ('13', '63', '3', '', '1500524686', '5', '0');
INSERT INTO `p39_comment` VALUES ('14', '63', '3', '', '1500524725', '4', '0');
INSERT INTO `p39_comment` VALUES ('15', '63', '3', '', '1500524774', '5', '0');
INSERT INTO `p39_comment` VALUES ('16', '63', '3', '', '1500524809', '4', '1');
INSERT INTO `p39_comment` VALUES ('17', '63', '3', '', '1500524815', '5', '1');
INSERT INTO `p39_comment` VALUES ('18', '63', '3', '', '1500524822', '2', '0');
INSERT INTO `p39_comment` VALUES ('19', '63', '3', '', '1500524831', '1', '1');
INSERT INTO `p39_comment` VALUES ('20', '63', '3', '', '1500535652', '5', '1');
INSERT INTO `p39_comment` VALUES ('21', '62', '3', '', '1500535939', '5', '0');
INSERT INTO `p39_comment` VALUES ('22', '63', '3', '', '1500541058', '5', '2');
INSERT INTO `p39_comment` VALUES ('23', '63', '3', '这是一条测试数据', '1500541298', '5', '2');
INSERT INTO `p39_comment` VALUES ('24', '63', '3', '还行，可以的', '1500544587', '5', '2');
INSERT INTO `p39_comment` VALUES ('25', '63', '3', '不错的', '1500558537', '5', '2');
INSERT INTO `p39_comment` VALUES ('26', '63', '3', '还行啊', '1500558565', '5', '2');
INSERT INTO `p39_comment` VALUES ('27', '63', '3', '事实上', '1500558783', '5', '2');
INSERT INTO `p39_comment` VALUES ('28', '63', '3', '不错不错', '1500562059', '4', '2');
INSERT INTO `p39_comment` VALUES ('29', '63', '3', '便宜啊', '1500562077', '5', '2');
INSERT INTO `p39_comment` VALUES ('30', '63', '3', '最新评论', '1500564913', '5', '2');
INSERT INTO `p39_comment` VALUES ('31', '63', '3', '是', '1500610143', '5', '10');
INSERT INTO `p39_comment` VALUES ('32', '58', '3', '啊', '1500610382', '5', '0');

-- ----------------------------
-- Table structure for p39_comment_reply
-- ----------------------------
DROP TABLE IF EXISTS `p39_comment_reply`;
CREATE TABLE `p39_comment_reply` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `comment_id` mediumint(8) unsigned NOT NULL COMMENT '评论ID',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  `content` varchar(300) NOT NULL DEFAULT '' COMMENT '评论内容',
  `addtime` int(10) unsigned NOT NULL COMMENT '评论时间',
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_comment_reply
-- ----------------------------
INSERT INTO `p39_comment_reply` VALUES ('1', '1', '3', '说得好', '1500520152');
INSERT INTO `p39_comment_reply` VALUES ('2', '23', '3', '说得好', '1500541298');
INSERT INTO `p39_comment_reply` VALUES ('3', '29', '3', '是啊', '1500568425');
INSERT INTO `p39_comment_reply` VALUES ('4', '29', '3', '是啊', '1500568451');
INSERT INTO `p39_comment_reply` VALUES ('5', '29', '3', '不错啊', '1500606579');
INSERT INTO `p39_comment_reply` VALUES ('6', '28', '3', '不错啊', '1500606592');
INSERT INTO `p39_comment_reply` VALUES ('7', '28', '3', '还行', '1500606650');
INSERT INTO `p39_comment_reply` VALUES ('8', '28', '3', '王者', '1500606682');
INSERT INTO `p39_comment_reply` VALUES ('9', '27', '3', '事实', '1500606728');
INSERT INTO `p39_comment_reply` VALUES ('10', '27', '3', '事实', '1500606733');
INSERT INTO `p39_comment_reply` VALUES ('11', '28', '3', '是啊', '1500606954');
INSERT INTO `p39_comment_reply` VALUES ('12', '26', '3', '看看', '1500606964');
INSERT INTO `p39_comment_reply` VALUES ('13', '26', '3', '天气不错', '1500606974');
INSERT INTO `p39_comment_reply` VALUES ('14', '27', '3', '确实如此', '1500608467');
INSERT INTO `p39_comment_reply` VALUES ('15', '31', '3', '不错', '1500610150');
INSERT INTO `p39_comment_reply` VALUES ('16', '30', '3', '好的', '1500610760');
INSERT INTO `p39_comment_reply` VALUES ('17', '30', '3', '哈哈', '1500610833');
INSERT INTO `p39_comment_reply` VALUES ('18', '29', '3', '还行', '1500611109');
INSERT INTO `p39_comment_reply` VALUES ('19', '29', '3', '嘎嘎', '1500611125');
INSERT INTO `p39_comment_reply` VALUES ('20', '29', '3', '6665', '1500611142');
INSERT INTO `p39_comment_reply` VALUES ('21', '30', '3', '哇', '1500611176');
INSERT INTO `p39_comment_reply` VALUES ('22', '30', '3', '是', '1500611186');
INSERT INTO `p39_comment_reply` VALUES ('23', '30', '3', '123', '1500611243');
INSERT INTO `p39_comment_reply` VALUES ('24', '30', '3', '是', '1500611340');
INSERT INTO `p39_comment_reply` VALUES ('25', '30', '3', '是', '1500611354');
INSERT INTO `p39_comment_reply` VALUES ('26', '30', '3', '爱上', '1500611374');
INSERT INTO `p39_comment_reply` VALUES ('27', '27', '3', '阿萨德', '1500611400');
INSERT INTO `p39_comment_reply` VALUES ('28', '28', '3', '哈哈', '1500611489');
INSERT INTO `p39_comment_reply` VALUES ('29', '29', '3', '渣渣', '1500611567');
INSERT INTO `p39_comment_reply` VALUES ('30', '29', '3', '阿萨德', '1500611608');
INSERT INTO `p39_comment_reply` VALUES ('31', '29', '3', '阿萨德', '1500611619');
INSERT INTO `p39_comment_reply` VALUES ('32', '27', '3', '厄', '1500611689');
INSERT INTO `p39_comment_reply` VALUES ('33', '29', '3', '阿萨德', '1500611754');
INSERT INTO `p39_comment_reply` VALUES ('34', '22', '3', '测测', '1500611788');
INSERT INTO `p39_comment_reply` VALUES ('35', '31', '3', '阿萨德', '1500611887');
INSERT INTO `p39_comment_reply` VALUES ('36', '31', '3', '阿萨德', '1500611905');
INSERT INTO `p39_comment_reply` VALUES ('37', '31', '3', '可以了', '1500611943');
INSERT INTO `p39_comment_reply` VALUES ('38', '31', '3', '哇咔咔', '1500613487');
INSERT INTO `p39_comment_reply` VALUES ('39', '31', '3', '你谁', '1500719185');

-- ----------------------------
-- Table structure for p39_comment_useful
-- ----------------------------
DROP TABLE IF EXISTS `p39_comment_useful`;
CREATE TABLE `p39_comment_useful` (
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  `comment_id` mediumint(8) unsigned NOT NULL COMMENT '评论ID',
  KEY `member_id` (`member_id`),
  KEY `comment_id` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_comment_useful
-- ----------------------------
INSERT INTO `p39_comment_useful` VALUES ('3', '31');
INSERT INTO `p39_comment_useful` VALUES ('3', '30');
INSERT INTO `p39_comment_useful` VALUES ('3', '29');
INSERT INTO `p39_comment_useful` VALUES ('3', '28');
INSERT INTO `p39_comment_useful` VALUES ('3', '27');
INSERT INTO `p39_comment_useful` VALUES ('3', '25');
INSERT INTO `p39_comment_useful` VALUES ('3', '26');
INSERT INTO `p39_comment_useful` VALUES ('3', '24');
INSERT INTO `p39_comment_useful` VALUES ('3', '22');
INSERT INTO `p39_comment_useful` VALUES ('3', '23');
INSERT INTO `p39_comment_useful` VALUES ('3', '16');
INSERT INTO `p39_comment_useful` VALUES ('3', '17');
INSERT INTO `p39_comment_useful` VALUES ('3', '19');

-- ----------------------------
-- Table structure for p39_ext_category
-- ----------------------------
DROP TABLE IF EXISTS `p39_ext_category`;
CREATE TABLE `p39_ext_category` (
  `cat_id` mediumint(8) unsigned NOT NULL COMMENT '分类ID',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  KEY `cat_id` (`cat_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_ext_category
-- ----------------------------
INSERT INTO `p39_ext_category` VALUES ('17', '57');
INSERT INTO `p39_ext_category` VALUES ('0', '57');
INSERT INTO `p39_ext_category` VALUES ('0', '57');
INSERT INTO `p39_ext_category` VALUES ('6', '57');
INSERT INTO `p39_ext_category` VALUES ('0', '53');
INSERT INTO `p39_ext_category` VALUES ('23', '53');
INSERT INTO `p39_ext_category` VALUES ('19', '53');
INSERT INTO `p39_ext_category` VALUES ('2', '53');
INSERT INTO `p39_ext_category` VALUES ('22', '55');
INSERT INTO `p39_ext_category` VALUES ('24', '55');
INSERT INTO `p39_ext_category` VALUES ('18', '55');
INSERT INTO `p39_ext_category` VALUES ('1', '52');
INSERT INTO `p39_ext_category` VALUES ('23', '52');
INSERT INTO `p39_ext_category` VALUES ('16', '52');
INSERT INTO `p39_ext_category` VALUES ('5', '52');
INSERT INTO `p39_ext_category` VALUES ('0', '56');
INSERT INTO `p39_ext_category` VALUES ('16', '59');
INSERT INTO `p39_ext_category` VALUES ('23', '59');
INSERT INTO `p39_ext_category` VALUES ('0', '60');
INSERT INTO `p39_ext_category` VALUES ('1', '54');
INSERT INTO `p39_ext_category` VALUES ('24', '54');
INSERT INTO `p39_ext_category` VALUES ('17', '54');
INSERT INTO `p39_ext_category` VALUES ('0', '61');
INSERT INTO `p39_ext_category` VALUES ('0', '58');
INSERT INTO `p39_ext_category` VALUES ('25', '62');
INSERT INTO `p39_ext_category` VALUES ('22', '63');
INSERT INTO `p39_ext_category` VALUES ('16', '65');
INSERT INTO `p39_ext_category` VALUES ('1', '64');
INSERT INTO `p39_ext_category` VALUES ('1', '66');
INSERT INTO `p39_ext_category` VALUES ('23', '67');
INSERT INTO `p39_ext_category` VALUES ('1', '68');
INSERT INTO `p39_ext_category` VALUES ('24', '69');
INSERT INTO `p39_ext_category` VALUES ('23', '70');
INSERT INTO `p39_ext_category` VALUES ('3', '71');

-- ----------------------------
-- Table structure for p39_goods
-- ----------------------------
DROP TABLE IF EXISTS `p39_goods`;
CREATE TABLE `p39_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_name` varchar(150) NOT NULL COMMENT '商品名称',
  `market_price` decimal(10,2) NOT NULL COMMENT '市场价格',
  `shop_price` decimal(10,2) NOT NULL COMMENT '本店价格',
  `goods_desc` longtext COMMENT '商品描述',
  `is_on_sale` enum('是','否') NOT NULL DEFAULT '是' COMMENT '是否上架',
  `is_delete` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否删除',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `logo` varchar(150) NOT NULL DEFAULT '' COMMENT '原图',
  `sm_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '小图',
  `mid_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '中图',
  `big_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '大图',
  `mbig_logo` varchar(150) NOT NULL DEFAULT '' COMMENT '更大图',
  `brand_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主分类ID',
  `type_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `promote_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `promote_start` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销开始时间',
  `promote_end` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销结束时间',
  `is_new` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否是新品',
  `is_ret` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否是推荐',
  `is_hot` enum('是','否') NOT NULL DEFAULT '否' COMMENT '是否是热卖',
  `sort_num` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '权重',
  `is_floor` enum('是','否') NOT NULL DEFAULT '否',
  `is_updated` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否被修改 0未被修改 1被修改 用于sphinx',
  PRIMARY KEY (`id`),
  KEY `shop_price` (`shop_price`),
  KEY `addtime` (`addtime`),
  KEY `is_on_sale` (`is_on_sale`),
  KEY `brand_id` (`brand_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of p39_goods
-- ----------------------------
INSERT INTO `p39_goods` VALUES ('11', 'OPPO', '1200.00', '1000.00', '&lt;p&gt;&lt;img src=\"http://www.39.com/Public/umeditor/php/upload/20170709/14995867572222.jpg\" style=\"width: 267px; height: 186px;\"/&gt;&lt;/p&gt;&lt;p&gt;OPPO手机&lt;/p&gt;', '是', '否', '1499586776', '/Public/Upload/2017-07-09/59623f8d1b326.jpg', '/Public/Upload/2017-07-09/sm_59623f8d1b326.jpg', '/Public/Upload/2017-07-09/mid_59623f8d1b326.jpg', '/Public/Upload/2017-07-09/big_59623f8d1b326.jpg', '/Public/Upload/2017-07-09/mbig_59623f8d1b326.jpg', '0', '0', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('12', 'OPPO', '1200.00', '1000.00', '&lt;p&gt;&lt;img src=\"http://www.39.com/Public/umeditor/php/upload/20170709/14995867572222.jpg\" style=\"width: 267px; height: 186px;\"/&gt;&lt;/p&gt;&lt;p&gt;OPPO手机&lt;/p&gt;', '是', '否', '1499587301', '/Public/Upload/2017-07-09/59623f85e2e78.jpg', '/Public/Upload/2017-07-09/sm_59623f85e2e78.jpg', '/Public/Upload/2017-07-09/mid_59623f85e2e78.jpg', '/Public/Upload/2017-07-09/big_59623f85e2e78.jpg', '/Public/Upload/2017-07-09/mbig_59623f85e2e78.jpg', '4', '0', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('13', '123', '100.00', '100.00', '&lt;p&gt;123&lt;/p&gt;', '是', '否', '1499589265', '/Public/Upload/2017-07-09/59623f7da81ba.jpg', '/Public/Upload/2017-07-09/sm_59623f7da81ba.jpg', '/Public/Upload/2017-07-09/mid_59623f7da81ba.jpg', '/Public/Upload/2017-07-09/big_59623f7da81ba.jpg', '/Public/Upload/2017-07-09/mbig_59623f7da81ba.jpg', '4', '0', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('14', '小米', '2000.00', '1000.00', '&lt;p&gt;小米&lt;img src=\"http://www.39.com/Public/umeditor/php/upload/20170709/14996051141458.jpg\"/&gt;&lt;/p&gt;', '是', '否', '1499589896', '/Public/Upload/2017-07-09/59623f635105b.jpg', '/Public/Upload/2017-07-09/sm_59623f635105b.jpg', '/Public/Upload/2017-07-09/mid_59623f635105b.jpg', '/Public/Upload/2017-07-09/big_59623f635105b.jpg', '/Public/Upload/2017-07-09/mbig_59623f635105b.jpg', '2', '0', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('15', '魅族pro6', '3000.00', '2444.00', '&lt;p&gt;魅族手机&lt;/p&gt;', '否', '否', '1499590068', '/Public/Upload/2017-07-09/5961edb4a49fc.jpg', '/Public/Upload/2017-07-09/sm_5961edb4a49fc.jpg', '/Public/Upload/2017-07-09/mid_5961edb4a49fc.jpg', '/Public/Upload/2017-07-09/big_5961edb4a49fc.jpg', '/Public/Upload/2017-07-09/mbig_5961edb4a49fc.jpg', '1', '0', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('16', 'pro6', '2788.00', '2488.00', '&lt;p&gt;这是魅族手机&lt;img src=\"http://www.39.com/Public/umeditor/php/upload/20170710/14996534194611.jpg\"/&gt;&lt;/p&gt;', '是', '否', '1499653421', '/Public/Upload/2017-07-10/5962e52dbc72f.jpg', '/Public/Upload/2017-07-10/sm_5962e52dbc72f.jpg', '/Public/Upload/2017-07-10/mid_5962e52dbc72f.jpg', '/Public/Upload/2017-07-10/big_5962e52dbc72f.jpg', '/Public/Upload/2017-07-10/mbig_5962e52dbc72f.jpg', '1', '0', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('17', 'pro5', '1500.00', '1300.00', '', '是', '否', '1499663448', '/Public/Upload/2017-07-10/59630c5897cdb.jpg', '/Public/Upload/2017-07-10/sm_59630c5897cdb.jpg', '/Public/Upload/2017-07-10/mid_59630c5897cdb.jpg', '/Public/Upload/2017-07-10/big_59630c5897cdb.jpg', '/Public/Upload/2017-07-10/mbig_59630c5897cdb.jpg', '0', '24', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('51', '苹果', '5000.00', '4000.00', '&lt;p&gt;这是苹果&lt;/p&gt;', '是', '否', '1499678623', '/Public/Upload/2017-07-10/5963479f04bdc.jpg', '/Public/Upload/2017-07-10/sm_5963479f04bdc.jpg', '/Public/Upload/2017-07-10/mid_5963479f04bdc.jpg', '/Public/Upload/2017-07-10/big_5963479f04bdc.jpg', '/Public/Upload/2017-07-10/mbig_5963479f04bdc.jpg', '0', '22', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('52', '苹果', '5000.00', '4000.00', '&lt;p&gt;这是苹果手机啊&lt;img src=\"http://www.39.com/Public/umeditor/php/upload/20170711/1499746262561.jpg\"/&gt;&lt;/p&gt;', '是', '否', '1499746278', '/Public/Upload/2017-07-11/59644fe5f0ca3.jpg', '/Public/Upload/2017-07-11/sm_59644fe5f0ca3.jpg', '/Public/Upload/2017-07-11/mid_59644fe5f0ca3.jpg', '/Public/Upload/2017-07-11/big_59644fe5f0ca3.jpg', '/Public/Upload/2017-07-11/mbig_59644fe5f0ca3.jpg', '0', '2', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('53', '红米', '1000.00', '800.00', '', '是', '否', '1499756712', '', '', '', '', '', '0', '22', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('54', 'OPPO', '1000.00', '800.00', '', '是', '否', '1499756772', '/Public/Upload/2017-07-11/596478e4ceccb.jpg', '/Public/Upload/2017-07-11/sm_596478e4ceccb.jpg', '/Public/Upload/2017-07-11/mid_596478e4ceccb.jpg', '/Public/Upload/2017-07-11/big_596478e4ceccb.jpg', '/Public/Upload/2017-07-11/mbig_596478e4ceccb.jpg', '2', '2', '0', '0.00', '0', '0', '否', '否', '否', '100', '是', '0');
INSERT INTO `p39_goods` VALUES ('55', 'OPPO', '5000.00', '800.00', '', '是', '否', '1499851540', '/Public/Upload/2017-07-12/5965eb1494c09.jpg', '/Public/Upload/2017-07-12/sm_5965eb1494c09.jpg', '/Public/Upload/2017-07-12/mid_5965eb1494c09.jpg', '/Public/Upload/2017-07-12/big_5965eb1494c09.jpg', '/Public/Upload/2017-07-12/mbig_5965eb1494c09.jpg', '2', '22', '5', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('56', 'OPPO', '1000.00', '4000.00', '', '是', '否', '1499851690', '', '', '', '', '', '0', '16', '5', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('57', 'OPPO', '1000.00', '4000.00', '', '是', '否', '1499851717', '', '', '', '', '', '0', '16', '5', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('58', 'OPPO', '5000.00', '4000.00', '', '是', '否', '1499851831', '/Public/Upload/2017-07-12/5965ec374ae14.jpg', '/Public/Upload/2017-07-12/sm_5965ec374ae14.jpg', '/Public/Upload/2017-07-12/mid_5965ec374ae14.jpg', '/Public/Upload/2017-07-12/big_5965ec374ae14.jpg', '/Public/Upload/2017-07-12/mbig_5965ec374ae14.jpg', '0', '17', '5', '0.00', '0', '0', '是', '否', '是', '150', '是', '0');
INSERT INTO `p39_goods` VALUES ('59', 'OPPO', '1000.00', '4000.00', '', '是', '否', '1499852389', '', '', '', '', '', '0', '1', '2', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('60', '红米', '5000.00', '4000.00', '', '是', '否', '1499852655', '', '', '', '', '', '0', '16', '5', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('61', 'OPPO', '5000.00', '4000.00', '', '是', '否', '1500106604', '/Public/Upload/2017-07-15/5969cf6c6f25d.JPG', '/Public/Upload/2017-07-15/sm_5969cf6c6f25d.JPG', '/Public/Upload/2017-07-15/mid_5969cf6c6f25d.JPG', '/Public/Upload/2017-07-15/big_5969cf6c6f25d.JPG', '/Public/Upload/2017-07-15/mbig_5969cf6c6f25d.JPG', '1', '16', '0', '3000.00', '1500048000', '1500134340', '是', '是', '是', '100', '是', '0');
INSERT INTO `p39_goods` VALUES ('62', '苹果', '123.00', '100.00', '<p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170716/15001889293630.jpg\" alt=\"15001889293630.jpg\" /></p>', '是', '否', '1500107415', '/Public/Upload/2017-07-15/5969d29772e66.jpg', '/Public/Upload/2017-07-15/sm_5969d29772e66.jpg', '/Public/Upload/2017-07-15/mid_5969d29772e66.jpg', '/Public/Upload/2017-07-15/big_5969d29772e66.jpg', '/Public/Upload/2017-07-15/mbig_5969d29772e66.jpg', '0', '18', '0', '88.00', '1500048000', '1500134340', '是', '是', '是', '100', '是', '0');
INSERT INTO `p39_goods` VALUES ('63', '苹果', '1000.00', '800.00', '<p><span style=\"color:#ff0000;\">充电五分钟，拍照两小时</span></p><p><span style=\"color:#ff0000;\">欢迎购买</span></p><p><span style=\"color:#ff0000;\"></span></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170716/15001875895704.jpg\" alt=\"15001875895704.jpg\" /></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170716/1500187591483.jpg\" alt=\"1500187591483.jpg\" /></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170716/15001875955632.jpg\" alt=\"15001875955632.jpg\" /></p><p><span style=\"color:#ff0000;\"><br /></span><br /></p>', '是', '否', '1500108098', '/Public/Upload/2017-07-16/596b0afd2a19a.jpg', '/Public/Upload/2017-07-16/sm_596b0afd2a19a.jpg', '/Public/Upload/2017-07-16/mid_596b0afd2a19a.jpg', '/Public/Upload/2017-07-16/big_596b0afd2a19a.jpg', '/Public/Upload/2017-07-16/mbig_596b0afd2a19a.jpg', '0', '1', '2', '599.00', '1500134400', '1500220740', '是', '是', '是', '80', '是', '0');
INSERT INTO `p39_goods` VALUES ('64', '小米plus', '2000.00', '1999.00', '<p><strong><span style=\"color:#ff0000;\">小米小手机~<img src=\"http://img.baidu.com/hi/tsj/t_0002.gif\" alt=\"t_0002.gif\" /><img src=\"http://img.baidu.com/hi/jx2/j_0002.gif\" alt=\"j_0002.gif\" /><img src=\"http://img.baidu.com/hi/jx2/j_0002.gif\" alt=\"j_0002.gif\" /></span></strong></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170721/15006273657588.jpg\" alt=\"15006273657588.jpg\" /></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170721/15006273685622.jpg\" alt=\"15006273685622.jpg\" /></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170721/15006273704336.jpg\" alt=\"15006273704336.jpg\" /></p><p><br /></p>', '是', '否', '1500627409', '/Public/Upload/2017-07-21/5971c1d162afc.jpg', '/Public/Upload/2017-07-21/sm_5971c1d162afc.jpg', '/Public/Upload/2017-07-21/mid_5971c1d162afc.jpg', '/Public/Upload/2017-07-21/big_5971c1d162afc.jpg', '/Public/Upload/2017-07-21/mbig_5971c1d162afc.jpg', '0', '2', '5', '999.00', '1500566400', '1500652740', '是', '否', '否', '100', '是', '0');
INSERT INTO `p39_goods` VALUES ('65', '魅族pro7', '2999.00', '2888.00', '<p><span style=\"color:#ff0000;\">魅族手机~<img src=\"http://img.baidu.com/hi/jx2/j_0024.gif\" alt=\"j_0024.gif\" /><img src=\"http://img.baidu.com/hi/jx2/j_0024.gif\" alt=\"j_0024.gif\" /></span></p><p><span style=\"color:#ff0000;\"></span></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170721/15006277183750.jpg\" alt=\"15006277183750.jpg\" /></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170721/15006277201515.jpg\" alt=\"15006277201515.jpg\" /></p><p><img src=\"http://www.39.com/Public/umeditor/php/upload/20170721/1500627722555.jpg\" alt=\"1500627722555.jpg\" /></p><p><span style=\"color:#ff0000;\"><br /></span><br /></p>', '是', '否', '1500627743', '/Public/Upload/2017-07-21/5971c31f486b7.jpg', '/Public/Upload/2017-07-21/sm_5971c31f486b7.jpg', '/Public/Upload/2017-07-21/mid_5971c31f486b7.jpg', '/Public/Upload/2017-07-21/big_5971c31f486b7.jpg', '/Public/Upload/2017-07-21/mbig_5971c31f486b7.jpg', '0', '1', '5', '2000.00', '1500566400', '1500652740', '是', '是', '是', '100', '是', '0');
INSERT INTO `p39_goods` VALUES ('66', '小辣椒', '3000.00', '2000.00', '', '是', '否', '1500628074', '/Public/Upload/2017-07-22/59732bb8eb566.jpg', '/Public/Upload/2017-07-22/sm_59732bb8eb566.jpg', '/Public/Upload/2017-07-22/mid_59732bb8eb566.jpg', '/Public/Upload/2017-07-22/big_59732bb8eb566.jpg', '/Public/Upload/2017-07-22/mbig_59732bb8eb566.jpg', '0', '1', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('67', '坚果', '3000.00', '2000.00', '<p><img src=\"http://118.89.20.47/shoptp3/Public/umeditor/php/upload/20170722/15007214884037.jpg\" alt=\"15007214884037.jpg\" /></p><p><img src=\"http://118.89.20.47/shoptp3/Public/umeditor/php/upload/20170722/15007214903413.jpg\" alt=\"15007214903413.jpg\" /></p><p><br /></p>', '是', '否', '1500628310', '/Public/Upload/2017-07-22/59732bb06a289.jpg', '/Public/Upload/2017-07-22/sm_59732bb06a289.jpg', '/Public/Upload/2017-07-22/mid_59732bb06a289.jpg', '/Public/Upload/2017-07-22/big_59732bb06a289.jpg', '/Public/Upload/2017-07-22/mbig_59732bb06a289.jpg', '0', '1', '0', '0.00', '0', '0', '否', '否', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('68', '华丽的变身', '5000.00', '800.00', '<p><img src=\"http://118.89.20.47/shoptp3/Public/umeditor/php/upload/20170727/15011630573021.png\" alt=\"15011630573021.png\" /></p><p><img src=\"http://118.89.20.47/shoptp3/Public/umeditor/php/upload/20170727/15011630592343.png\" alt=\"15011630592343.png\" /></p><p><img src=\"http://118.89.20.47/shoptp3/Public/umeditor/php/upload/20170727/15011630618754.jpg\" alt=\"15011630618754.jpg\" /></p><p><br /></p>', '是', '否', '1501163063', '/Public/Upload/2017-07-27/5979ee378c147.png', '/Public/Upload/2017-07-27/sm_5979ee378c147.png', '/Public/Upload/2017-07-27/mid_5979ee378c147.png', '/Public/Upload/2017-07-27/big_5979ee378c147.png', '/Public/Upload/2017-07-27/mbig_5979ee378c147.png', '1', '1', '0', '0.00', '0', '0', '是', '是', '是', '100', '是', '0');
INSERT INTO `p39_goods` VALUES ('69', '好的完美', '600.00', '500.00', '<p><img src=\"http://shoptp3.guaosi.com.cn/Public/umeditor/php/upload/20170811/1502452579658.jpg\" alt=\"1502452579658.jpg\" /></p>', '是', '否', '1502452599', '/Public/Upload/2017-08-11/598d9b772ed6f.jpg', '/Public/Upload/2017-08-11/sm_598d9b772ed6f.jpg', '/Public/Upload/2017-08-11/mid_598d9b772ed6f.jpg', '/Public/Upload/2017-08-11/big_598d9b772ed6f.jpg', '/Public/Upload/2017-08-11/mbig_598d9b772ed6f.jpg', '0', '1', '2', '400.00', '1501516800', '1504108800', '否', '是', '是', '150', '否', '0');
INSERT INTO `p39_goods` VALUES ('70', '花见花开', '600.00', '500.00', '', '是', '否', '1502454100', '/Public/Upload/2017-08-11/598da154778ef.jpg', '/Public/Upload/2017-08-11/sm_598da154778ef.jpg', '/Public/Upload/2017-08-11/mid_598da154778ef.jpg', '/Public/Upload/2017-08-11/big_598da154778ef.jpg', '/Public/Upload/2017-08-11/mbig_598da154778ef.jpg', '0', '1', '0', '0.00', '0', '0', '否', '是', '否', '100', '否', '0');
INSERT INTO `p39_goods` VALUES ('71', '苹果', '5000.00', '4000.00', '<p>这是苹果啊<img src=\"http://shoptp3.guaosi.com/Public/umeditor/php/upload/20180615/15289958872357.jpg\" alt=\"15289958872357.jpg\" /></p>', '是', '否', '1528995912', '/Public/Upload/2018-06-15/5b22a04810b83.jpg', '/Public/Upload/2018-06-15/sm_5b22a04810b83.jpg', '/Public/Upload/2018-06-15/mid_5b22a04810b83.jpg', '/Public/Upload/2018-06-15/big_5b22a04810b83.jpg', '/Public/Upload/2018-06-15/mbig_5b22a04810b83.jpg', '4', '2', '5', '3000.00', '1528995840', '1530288000', '是', '是', '是', '100', '否', '0');

-- ----------------------------
-- Table structure for p39_goods_attr
-- ----------------------------
DROP TABLE IF EXISTS `p39_goods_attr`;
CREATE TABLE `p39_goods_attr` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `attr_value` varchar(300) NOT NULL DEFAULT '' COMMENT '属性值',
  `attr_id` mediumint(8) unsigned NOT NULL COMMENT '属性ID',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  PRIMARY KEY (`id`),
  KEY `attr_id` (`attr_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=210 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_goods_attr
-- ----------------------------
INSERT INTO `p39_goods_attr` VALUES ('1', '蓝色', '4', '55');
INSERT INTO `p39_goods_attr` VALUES ('2', '高通骁龙', '5', '55');
INSERT INTO `p39_goods_attr` VALUES ('100', '4G', '2', '59');
INSERT INTO `p39_goods_attr` VALUES ('101', '8G', '2', '59');
INSERT INTO `p39_goods_attr` VALUES ('102', '16G', '2', '59');
INSERT INTO `p39_goods_attr` VALUES ('177', '红色', '4', '58');
INSERT INTO `p39_goods_attr` VALUES ('178', '联发科', '5', '58');
INSERT INTO `p39_goods_attr` VALUES ('179', '红色', '4', '60');
INSERT INTO `p39_goods_attr` VALUES ('180', '黄色', '4', '60');
INSERT INTO `p39_goods_attr` VALUES ('181', '蓝色', '4', '60');
INSERT INTO `p39_goods_attr` VALUES ('182', '联发科', '5', '60');
INSERT INTO `p39_goods_attr` VALUES ('183', '高通骁龙', '5', '60');
INSERT INTO `p39_goods_attr` VALUES ('184', '4G', '2', '63');
INSERT INTO `p39_goods_attr` VALUES ('185', '8G', '2', '63');
INSERT INTO `p39_goods_attr` VALUES ('186', '16G', '2', '63');
INSERT INTO `p39_goods_attr` VALUES ('187', '联想', '6', '63');
INSERT INTO `p39_goods_attr` VALUES ('188', 'GTX1080', '7', '63');
INSERT INTO `p39_goods_attr` VALUES ('189', '三网通', '9', '63');
INSERT INTO `p39_goods_attr` VALUES ('190', '500G', '10', '63');
INSERT INTO `p39_goods_attr` VALUES ('191', '1T', '10', '63');
INSERT INTO `p39_goods_attr` VALUES ('192', '2T', '10', '63');
INSERT INTO `p39_goods_attr` VALUES ('193', '红色', '4', '64');
INSERT INTO `p39_goods_attr` VALUES ('194', '黄色', '4', '64');
INSERT INTO `p39_goods_attr` VALUES ('195', '蓝色', '4', '64');
INSERT INTO `p39_goods_attr` VALUES ('196', '联发科', '5', '64');
INSERT INTO `p39_goods_attr` VALUES ('197', '高通骁龙', '5', '64');
INSERT INTO `p39_goods_attr` VALUES ('198', '红色', '4', '65');
INSERT INTO `p39_goods_attr` VALUES ('199', '黄色', '4', '65');
INSERT INTO `p39_goods_attr` VALUES ('200', '蓝色', '4', '65');
INSERT INTO `p39_goods_attr` VALUES ('201', '绿色', '4', '65');
INSERT INTO `p39_goods_attr` VALUES ('202', '联发科', '5', '65');
INSERT INTO `p39_goods_attr` VALUES ('203', '高通骁龙', '5', '65');
INSERT INTO `p39_goods_attr` VALUES ('204', '4G', '2', '69');
INSERT INTO `p39_goods_attr` VALUES ('205', '750G', '10', '69');
INSERT INTO `p39_goods_attr` VALUES ('206', '红色', '4', '71');
INSERT INTO `p39_goods_attr` VALUES ('207', '黄色', '4', '71');
INSERT INTO `p39_goods_attr` VALUES ('208', '联发科', '5', '71');
INSERT INTO `p39_goods_attr` VALUES ('209', '高通骁龙', '5', '71');

-- ----------------------------
-- Table structure for p39_goods_number
-- ----------------------------
DROP TABLE IF EXISTS `p39_goods_number`;
CREATE TABLE `p39_goods_number` (
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `goods_number` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '库存量',
  `goods_attr_id` varchar(150) NOT NULL COMMENT '商品属性ID，如果有多个，就用程序拼成的字符串存到这个字段',
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='库存量';

-- ----------------------------
-- Records of p39_goods_number
-- ----------------------------
INSERT INTO `p39_goods_number` VALUES ('60', '1000', '179,182');
INSERT INTO `p39_goods_number` VALUES ('60', '200', '179,183');
INSERT INTO `p39_goods_number` VALUES ('60', '300', '180,182');
INSERT INTO `p39_goods_number` VALUES ('60', '400', '180,183');
INSERT INTO `p39_goods_number` VALUES ('60', '500', '181,182');
INSERT INTO `p39_goods_number` VALUES ('60', '600', '181,183');
INSERT INTO `p39_goods_number` VALUES ('63', '0', '184,190');
INSERT INTO `p39_goods_number` VALUES ('63', '199', '184,191');
INSERT INTO `p39_goods_number` VALUES ('63', '300', '184,192');
INSERT INTO `p39_goods_number` VALUES ('63', '43', '185,190');
INSERT INTO `p39_goods_number` VALUES ('63', '499', '185,191');
INSERT INTO `p39_goods_number` VALUES ('63', '600', '185,192');
INSERT INTO `p39_goods_number` VALUES ('63', '671', '186,190');
INSERT INTO `p39_goods_number` VALUES ('63', '800', '186,191');
INSERT INTO `p39_goods_number` VALUES ('63', '0', '186,192');
INSERT INTO `p39_goods_number` VALUES ('62', '88', '');
INSERT INTO `p39_goods_number` VALUES ('58', '194', '177,178');
INSERT INTO `p39_goods_number` VALUES ('69', '499', '204,205');

-- ----------------------------
-- Table structure for p39_goods_pic
-- ----------------------------
DROP TABLE IF EXISTS `p39_goods_pic`;
CREATE TABLE `p39_goods_pic` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pic` varchar(150) NOT NULL COMMENT '原图',
  `sm_pic` varchar(150) NOT NULL COMMENT '小图',
  `mid_pic` varchar(150) NOT NULL COMMENT '中图',
  `big_pic` varchar(150) NOT NULL COMMENT '大图',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_goods_pic
-- ----------------------------
INSERT INTO `p39_goods_pic` VALUES ('2', '/Public/Upload/2017-07-10/5963479f9e11c.jpg', '/Public/Upload/2017-07-10/sm_5963479f9e11c.jpg', '/Public/Upload/2017-07-10/mid_5963479f9e11c.jpg', '/Public/Upload/2017-07-10/big_5963479f9e11c.jpg', '51');
INSERT INTO `p39_goods_pic` VALUES ('3', '/Public/Upload/2017-07-10/5963479f9fe79.jpg', '/Public/Upload/2017-07-10/sm_5963479f9fe79.jpg', '/Public/Upload/2017-07-10/mid_5963479f9fe79.jpg', '/Public/Upload/2017-07-10/big_5963479f9fe79.jpg', '51');
INSERT INTO `p39_goods_pic` VALUES ('12', '/Public/Upload/2017-07-10/59635a676b9f0.jpg', '/Public/Upload/2017-07-10/sm_59635a676b9f0.jpg', '/Public/Upload/2017-07-10/mid_59635a676b9f0.jpg', '/Public/Upload/2017-07-10/big_59635a676b9f0.jpg', '57');
INSERT INTO `p39_goods_pic` VALUES ('13', '/Public/Upload/2017-07-10/59635a676c7ed.jpg', '/Public/Upload/2017-07-10/sm_59635a676c7ed.jpg', '/Public/Upload/2017-07-10/mid_59635a676c7ed.jpg', '/Public/Upload/2017-07-10/big_59635a676c7ed.jpg', '57');
INSERT INTO `p39_goods_pic` VALUES ('14', '/Public/Upload/2017-07-10/59635b219b376.jpg', '/Public/Upload/2017-07-10/sm_59635b219b376.jpg', '/Public/Upload/2017-07-10/mid_59635b219b376.jpg', '/Public/Upload/2017-07-10/big_59635b219b376.jpg', '58');
INSERT INTO `p39_goods_pic` VALUES ('15', '/Public/Upload/2017-07-10/59635b219c5c6.jpg', '/Public/Upload/2017-07-10/sm_59635b219c5c6.jpg', '/Public/Upload/2017-07-10/mid_59635b219c5c6.jpg', '/Public/Upload/2017-07-10/big_59635b219c5c6.jpg', '58');
INSERT INTO `p39_goods_pic` VALUES ('20', '/Public/Upload/2017-07-10/5963736a775eb.jpg', '/Public/Upload/2017-07-10/sm_5963736a775eb.jpg', '/Public/Upload/2017-07-10/mid_5963736a775eb.jpg', '/Public/Upload/2017-07-10/big_5963736a775eb.jpg', '51');
INSERT INTO `p39_goods_pic` VALUES ('21', '/Public/Upload/2017-07-10/5963737331a28.jpg', '/Public/Upload/2017-07-10/sm_5963737331a28.jpg', '/Public/Upload/2017-07-10/mid_5963737331a28.jpg', '/Public/Upload/2017-07-10/big_5963737331a28.jpg', '51');
INSERT INTO `p39_goods_pic` VALUES ('22', '/Public/Upload/2017-07-11/59644fe625f24.jpg', '/Public/Upload/2017-07-11/sm_59644fe625f24.jpg', '/Public/Upload/2017-07-11/mid_59644fe625f24.jpg', '/Public/Upload/2017-07-11/big_59644fe625f24.jpg', '52');
INSERT INTO `p39_goods_pic` VALUES ('23', '/Public/Upload/2017-07-11/59644fe626c7b.jpg', '/Public/Upload/2017-07-11/sm_59644fe626c7b.jpg', '/Public/Upload/2017-07-11/mid_59644fe626c7b.jpg', '/Public/Upload/2017-07-11/big_59644fe626c7b.jpg', '52');
INSERT INTO `p39_goods_pic` VALUES ('24', '/Public/Upload/2017-07-16/596b0570a28ff.jpg', '/Public/Upload/2017-07-16/sm_596b0570a28ff.jpg', '/Public/Upload/2017-07-16/mid_596b0570a28ff.jpg', '/Public/Upload/2017-07-16/big_596b0570a28ff.jpg', '63');
INSERT INTO `p39_goods_pic` VALUES ('25', '/Public/Upload/2017-07-16/596b0570a9236.jpg', '/Public/Upload/2017-07-16/sm_596b0570a9236.jpg', '/Public/Upload/2017-07-16/mid_596b0570a9236.jpg', '/Public/Upload/2017-07-16/big_596b0570a9236.jpg', '63');
INSERT INTO `p39_goods_pic` VALUES ('26', '/Public/Upload/2017-07-16/596b0570ad1e4.jpg', '/Public/Upload/2017-07-16/sm_596b0570ad1e4.jpg', '/Public/Upload/2017-07-16/mid_596b0570ad1e4.jpg', '/Public/Upload/2017-07-16/big_596b0570ad1e4.jpg', '63');
INSERT INTO `p39_goods_pic` VALUES ('27', '/Public/Upload/2017-07-16/596b0570af7c6.jpg', '/Public/Upload/2017-07-16/sm_596b0570af7c6.jpg', '/Public/Upload/2017-07-16/mid_596b0570af7c6.jpg', '/Public/Upload/2017-07-16/big_596b0570af7c6.jpg', '63');
INSERT INTO `p39_goods_pic` VALUES ('28', '/Public/Upload/2017-07-21/5971c32ab7720.jpg', '/Public/Upload/2017-07-21/sm_5971c32ab7720.jpg', '/Public/Upload/2017-07-21/mid_5971c32ab7720.jpg', '/Public/Upload/2017-07-21/big_5971c32ab7720.jpg', '65');
INSERT INTO `p39_goods_pic` VALUES ('29', '/Public/Upload/2017-07-21/5971c32abc3cd.jpg', '/Public/Upload/2017-07-21/sm_5971c32abc3cd.jpg', '/Public/Upload/2017-07-21/mid_5971c32abc3cd.jpg', '/Public/Upload/2017-07-21/big_5971c32abc3cd.jpg', '65');
INSERT INTO `p39_goods_pic` VALUES ('30', '/Public/Upload/2017-07-21/5971c32abf12f.jpg', '/Public/Upload/2017-07-21/sm_5971c32abf12f.jpg', '/Public/Upload/2017-07-21/mid_5971c32abf12f.jpg', '/Public/Upload/2017-07-21/big_5971c32abf12f.jpg', '65');
INSERT INTO `p39_goods_pic` VALUES ('31', '/Public/Upload/2017-07-22/597331406b32b.jpg', '/Public/Upload/2017-07-22/sm_597331406b32b.jpg', '/Public/Upload/2017-07-22/mid_597331406b32b.jpg', '/Public/Upload/2017-07-22/big_597331406b32b.jpg', '67');
INSERT INTO `p39_goods_pic` VALUES ('32', '/Public/Upload/2017-07-22/597331406bdd9.jpg', '/Public/Upload/2017-07-22/sm_597331406bdd9.jpg', '/Public/Upload/2017-07-22/mid_597331406bdd9.jpg', '/Public/Upload/2017-07-22/big_597331406bdd9.jpg', '67');
INSERT INTO `p39_goods_pic` VALUES ('33', '/Public/Upload/2017-08-11/598d9b775b8c8.jpg', '/Public/Upload/2017-08-11/sm_598d9b775b8c8.jpg', '/Public/Upload/2017-08-11/mid_598d9b775b8c8.jpg', '/Public/Upload/2017-08-11/big_598d9b775b8c8.jpg', '69');
INSERT INTO `p39_goods_pic` VALUES ('34', '/Public/Upload/2018-06-15/5b22a04822269.jpg', '/Public/Upload/2018-06-15/sm_5b22a04822269.jpg', '/Public/Upload/2018-06-15/mid_5b22a04822269.jpg', '/Public/Upload/2018-06-15/big_5b22a04822269.jpg', '71');

-- ----------------------------
-- Table structure for p39_member
-- ----------------------------
DROP TABLE IF EXISTS `p39_member`;
CREATE TABLE `p39_member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `face` varchar(150) NOT NULL DEFAULT '/Public/Home/images/user2.jpg' COMMENT '头像',
  `jifen` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_member
-- ----------------------------
INSERT INTO `p39_member` VALUES ('3', 'admin', 'de48ad76dadd3d693eedeaa2713db7bb', '/Public/Home/images/user2.jpg', '18933');

-- ----------------------------
-- Table structure for p39_member_level
-- ----------------------------
DROP TABLE IF EXISTS `p39_member_level`;
CREATE TABLE `p39_member_level` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `level_name` varchar(30) NOT NULL COMMENT '级别名称',
  `jifen_bottom` mediumint(8) unsigned NOT NULL COMMENT '积分下限',
  `jifen_top` mediumint(8) unsigned NOT NULL COMMENT '积分上限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_member_level
-- ----------------------------
INSERT INTO `p39_member_level` VALUES ('1', '注册会员', '0', '5000');
INSERT INTO `p39_member_level` VALUES ('2', '低级会员', '5001', '10000');
INSERT INTO `p39_member_level` VALUES ('3', '中级会员', '10001', '15000');
INSERT INTO `p39_member_level` VALUES ('4', '高级会员', '15001', '20000');

-- ----------------------------
-- Table structure for p39_member_price
-- ----------------------------
DROP TABLE IF EXISTS `p39_member_price`;
CREATE TABLE `p39_member_price` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `level_id` mediumint(8) unsigned NOT NULL COMMENT '级别id',
  `price` decimal(10,2) NOT NULL COMMENT '商品价格',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `level_id` (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_member_price
-- ----------------------------
INSERT INTO `p39_member_price` VALUES ('5', '16', '4', '100.00');
INSERT INTO `p39_member_price` VALUES ('11', '15', '1', '3000.00');
INSERT INTO `p39_member_price` VALUES ('12', '15', '2', '2000.00');
INSERT INTO `p39_member_price` VALUES ('13', '14', '1', '100.00');
INSERT INTO `p39_member_price` VALUES ('14', '14', '2', '200.00');
INSERT INTO `p39_member_price` VALUES ('15', '14', '3', '300.00');
INSERT INTO `p39_member_price` VALUES ('16', '14', '4', '400.00');
INSERT INTO `p39_member_price` VALUES ('49', '57', '1', '300.00');
INSERT INTO `p39_member_price` VALUES ('50', '57', '2', '400.00');
INSERT INTO `p39_member_price` VALUES ('51', '57', '3', '500.00');
INSERT INTO `p39_member_price` VALUES ('52', '57', '4', '600.00');
INSERT INTO `p39_member_price` VALUES ('85', '51', '1', '300.00');
INSERT INTO `p39_member_price` VALUES ('86', '51', '2', '200.00');
INSERT INTO `p39_member_price` VALUES ('87', '51', '3', '100.00');
INSERT INTO `p39_member_price` VALUES ('88', '51', '4', '50.00');
INSERT INTO `p39_member_price` VALUES ('89', '17', '1', '1200.00');
INSERT INTO `p39_member_price` VALUES ('90', '17', '2', '1100.00');
INSERT INTO `p39_member_price` VALUES ('91', '17', '3', '1000.00');
INSERT INTO `p39_member_price` VALUES ('92', '17', '4', '800.00');
INSERT INTO `p39_member_price` VALUES ('100', '52', '1', '3999.00');
INSERT INTO `p39_member_price` VALUES ('101', '52', '3', '3777.00');
INSERT INTO `p39_member_price` VALUES ('102', '52', '4', '3666.00');
INSERT INTO `p39_member_price` VALUES ('107', '56', '1', '1000.00');
INSERT INTO `p39_member_price` VALUES ('108', '56', '2', '2000.00');
INSERT INTO `p39_member_price` VALUES ('109', '56', '3', '300.00');
INSERT INTO `p39_member_price` VALUES ('110', '56', '4', '400.00');
INSERT INTO `p39_member_price` VALUES ('115', '59', '1', '1000.00');
INSERT INTO `p39_member_price` VALUES ('116', '59', '2', '2000.00');
INSERT INTO `p39_member_price` VALUES ('117', '59', '3', '3000.00');
INSERT INTO `p39_member_price` VALUES ('118', '59', '4', '4000.00');
INSERT INTO `p39_member_price` VALUES ('135', '58', '1', '300.00');
INSERT INTO `p39_member_price` VALUES ('136', '58', '2', '400.00');
INSERT INTO `p39_member_price` VALUES ('137', '58', '3', '500.00');
INSERT INTO `p39_member_price` VALUES ('138', '58', '4', '600.00');
INSERT INTO `p39_member_price` VALUES ('171', '63', '1', '800.00');
INSERT INTO `p39_member_price` VALUES ('172', '63', '2', '700.00');
INSERT INTO `p39_member_price` VALUES ('173', '63', '3', '600.00');
INSERT INTO `p39_member_price` VALUES ('174', '63', '4', '500.00');
INSERT INTO `p39_member_price` VALUES ('183', '65', '1', '2888.00');
INSERT INTO `p39_member_price` VALUES ('184', '65', '2', '2777.00');
INSERT INTO `p39_member_price` VALUES ('185', '65', '3', '2666.00');
INSERT INTO `p39_member_price` VALUES ('186', '65', '4', '2555.00');
INSERT INTO `p39_member_price` VALUES ('187', '64', '1', '1500.00');
INSERT INTO `p39_member_price` VALUES ('188', '64', '2', '1400.00');
INSERT INTO `p39_member_price` VALUES ('189', '64', '3', '1300.00');
INSERT INTO `p39_member_price` VALUES ('190', '64', '4', '1200.00');
INSERT INTO `p39_member_price` VALUES ('195', '69', '1', '600.00');
INSERT INTO `p39_member_price` VALUES ('196', '69', '2', '500.00');
INSERT INTO `p39_member_price` VALUES ('197', '69', '3', '400.00');
INSERT INTO `p39_member_price` VALUES ('198', '69', '4', '300.00');

-- ----------------------------
-- Table structure for p39_order
-- ----------------------------
DROP TABLE IF EXISTS `p39_order`;
CREATE TABLE `p39_order` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `member_id` mediumint(8) unsigned NOT NULL COMMENT '会员ID',
  `addtime` int(10) unsigned NOT NULL COMMENT '下单时间',
  `pay_status` enum('是','否') NOT NULL DEFAULT '否' COMMENT '支付状态',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `total_price` decimal(10,2) NOT NULL COMMENT '支付金额',
  `shr_name` varchar(30) NOT NULL COMMENT '收货人姓名',
  `shr_tel` varchar(11) NOT NULL COMMENT '收货人电话',
  `shr_prience` varchar(30) NOT NULL COMMENT '收货人省份',
  `shr_city` varchar(30) NOT NULL COMMENT '收货人城市',
  `shr_area` varchar(30) NOT NULL COMMENT '收货人地区',
  `shr_address` varchar(30) NOT NULL COMMENT '收货人详细地址',
  `post_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:未发货 1:已发货 2:已收货',
  `post_number` varchar(30) NOT NULL DEFAULT '' COMMENT '快递号',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_order
-- ----------------------------

-- ----------------------------
-- Table structure for p39_order_goods
-- ----------------------------
DROP TABLE IF EXISTS `p39_order_goods`;
CREATE TABLE `p39_order_goods` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` mediumint(8) unsigned NOT NULL COMMENT '订单ID',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `goods_attr_id` varchar(150) NOT NULL DEFAULT '' COMMENT '购买商品属性id',
  `goods_number` mediumint(9) NOT NULL COMMENT '购买数量',
  `price` mediumint(8) unsigned NOT NULL COMMENT '购买价格',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_order_goods
-- ----------------------------
INSERT INTO `p39_order_goods` VALUES ('4', '6', '63', '184,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('5', '6', '63', '185,190', '2', '600');
INSERT INTO `p39_order_goods` VALUES ('6', '7', '63', '184,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('7', '7', '62', '', '2', '100');
INSERT INTO `p39_order_goods` VALUES ('8', '8', '63', '185,190', '2', '600');
INSERT INTO `p39_order_goods` VALUES ('9', '8', '63', '186,190', '2', '600');
INSERT INTO `p39_order_goods` VALUES ('10', '8', '62', '', '2', '100');
INSERT INTO `p39_order_goods` VALUES ('11', '8', '58', '177,178', '2', '500');
INSERT INTO `p39_order_goods` VALUES ('12', '9', '58', '177,178', '4', '500');
INSERT INTO `p39_order_goods` VALUES ('13', '10', '63', '186,190', '7', '600');
INSERT INTO `p39_order_goods` VALUES ('14', '11', '63', '186,190', '8', '600');
INSERT INTO `p39_order_goods` VALUES ('15', '12', '63', '186,190', '8', '600');
INSERT INTO `p39_order_goods` VALUES ('16', '13', '63', '185,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('17', '14', '63', '186,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('18', '15', '63', '185,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('19', '16', '63', '185,190', '2', '600');
INSERT INTO `p39_order_goods` VALUES ('20', '17', '63', '186,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('21', '18', '63', '185,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('22', '19', '63', '186,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('23', '20', '63', '185,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('24', '20', '62', '', '1', '100');
INSERT INTO `p39_order_goods` VALUES ('25', '21', '63', '185,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('26', '22', '63', '185,190', '1', '600');
INSERT INTO `p39_order_goods` VALUES ('27', '23', '62', '', '1', '100');
INSERT INTO `p39_order_goods` VALUES ('28', '24', '62', '', '3', '100');
INSERT INTO `p39_order_goods` VALUES ('29', '25', '62', '', '1', '100');
INSERT INTO `p39_order_goods` VALUES ('30', '26', '63', '185,190', '1', '800');
INSERT INTO `p39_order_goods` VALUES ('31', '27', '63', '185,190', '1', '500');
INSERT INTO `p39_order_goods` VALUES ('32', '28', '63', '185,190', '1', '800');
INSERT INTO `p39_order_goods` VALUES ('33', '29', '63', '185,190', '2', '500');
INSERT INTO `p39_order_goods` VALUES ('34', '30', '63', '185,191', '1', '500');
INSERT INTO `p39_order_goods` VALUES ('35', '30', '62', '', '1', '100');
INSERT INTO `p39_order_goods` VALUES ('36', '31', '63', '184,191', '1', '500');
INSERT INTO `p39_order_goods` VALUES ('37', '32', '69', '204,205', '1', '300');
INSERT INTO `p39_order_goods` VALUES ('38', '33', '63', '186,190', '1', '500');
INSERT INTO `p39_order_goods` VALUES ('39', '34', '62', '', '1', '100');

-- ----------------------------
-- Table structure for p39_pri_role
-- ----------------------------
DROP TABLE IF EXISTS `p39_pri_role`;
CREATE TABLE `p39_pri_role` (
  `pri_id` mediumint(8) unsigned NOT NULL COMMENT '权限ID',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色ID',
  KEY `pri_id` (`pri_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_pri_role
-- ----------------------------
INSERT INTO `p39_pri_role` VALUES ('1', '4');
INSERT INTO `p39_pri_role` VALUES ('6', '4');
INSERT INTO `p39_pri_role` VALUES ('7', '4');
INSERT INTO `p39_pri_role` VALUES ('8', '4');
INSERT INTO `p39_pri_role` VALUES ('9', '4');
INSERT INTO `p39_pri_role` VALUES ('23', '4');
INSERT INTO `p39_pri_role` VALUES ('24', '4');
INSERT INTO `p39_pri_role` VALUES ('25', '4');
INSERT INTO `p39_pri_role` VALUES ('26', '4');
INSERT INTO `p39_pri_role` VALUES ('27', '4');
INSERT INTO `p39_pri_role` VALUES ('28', '4');
INSERT INTO `p39_pri_role` VALUES ('29', '4');
INSERT INTO `p39_pri_role` VALUES ('30', '4');
INSERT INTO `p39_pri_role` VALUES ('38', '4');
INSERT INTO `p39_pri_role` VALUES ('33', '4');
INSERT INTO `p39_pri_role` VALUES ('34', '4');
INSERT INTO `p39_pri_role` VALUES ('35', '4');
INSERT INTO `p39_pri_role` VALUES ('36', '4');
INSERT INTO `p39_pri_role` VALUES ('37', '4');
INSERT INTO `p39_pri_role` VALUES ('1', '2');
INSERT INTO `p39_pri_role` VALUES ('2', '2');
INSERT INTO `p39_pri_role` VALUES ('3', '2');
INSERT INTO `p39_pri_role` VALUES ('4', '2');
INSERT INTO `p39_pri_role` VALUES ('31', '2');
INSERT INTO `p39_pri_role` VALUES ('32', '2');
INSERT INTO `p39_pri_role` VALUES ('5', '2');
INSERT INTO `p39_pri_role` VALUES ('6', '2');
INSERT INTO `p39_pri_role` VALUES ('7', '2');
INSERT INTO `p39_pri_role` VALUES ('8', '2');
INSERT INTO `p39_pri_role` VALUES ('9', '2');
INSERT INTO `p39_pri_role` VALUES ('23', '2');
INSERT INTO `p39_pri_role` VALUES ('24', '2');
INSERT INTO `p39_pri_role` VALUES ('25', '2');
INSERT INTO `p39_pri_role` VALUES ('26', '2');
INSERT INTO `p39_pri_role` VALUES ('27', '2');
INSERT INTO `p39_pri_role` VALUES ('28', '2');
INSERT INTO `p39_pri_role` VALUES ('29', '2');
INSERT INTO `p39_pri_role` VALUES ('30', '2');
INSERT INTO `p39_pri_role` VALUES ('38', '2');

-- ----------------------------
-- Table structure for p39_privilege
-- ----------------------------
DROP TABLE IF EXISTS `p39_privilege`;
CREATE TABLE `p39_privilege` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID主键',
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL DEFAULT '' COMMENT '模块名称',
  `controller_name` varchar(30) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名称',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_privilege
-- ----------------------------
INSERT INTO `p39_privilege` VALUES ('1', '商品模块', '', '', '', '0');
INSERT INTO `p39_privilege` VALUES ('2', '商品列表', 'Admin', 'Goods', 'lst', '1');
INSERT INTO `p39_privilege` VALUES ('3', '添加商品', 'Admin', 'Goods', 'add', '2');
INSERT INTO `p39_privilege` VALUES ('4', '修改商品', 'Admin', 'Goods', 'edit', '2');
INSERT INTO `p39_privilege` VALUES ('5', '删除商品', 'Admin', 'Goods', 'delete', '2');
INSERT INTO `p39_privilege` VALUES ('6', '分类列表', 'Admin', 'Category', 'lst', '1');
INSERT INTO `p39_privilege` VALUES ('7', '添加分类', 'Admin', 'Category', 'add', '6');
INSERT INTO `p39_privilege` VALUES ('8', '修改分类', 'Admin', 'Category', 'edit', '6');
INSERT INTO `p39_privilege` VALUES ('9', '删除分类', 'Admin', 'Category', 'delete', '6');
INSERT INTO `p39_privilege` VALUES ('10', 'RBAC', '', '', '', '0');
INSERT INTO `p39_privilege` VALUES ('11', '权限列表', 'Admin', 'Privilege', 'lst', '10');
INSERT INTO `p39_privilege` VALUES ('12', '添加权限', 'Privilege', 'Admin', 'add', '11');
INSERT INTO `p39_privilege` VALUES ('13', '修改权限', 'Admin', 'Privilege', 'edit', '11');
INSERT INTO `p39_privilege` VALUES ('14', '删除权限', 'Admin', 'Privilege', 'delete', '11');
INSERT INTO `p39_privilege` VALUES ('15', '角色列表', 'Admin', 'Role', 'lst', '10');
INSERT INTO `p39_privilege` VALUES ('16', '添加角色', 'Admin', 'Role', 'add', '15');
INSERT INTO `p39_privilege` VALUES ('17', '修改角色', 'Admin', 'Role', 'edit', '15');
INSERT INTO `p39_privilege` VALUES ('18', '删除角色', 'Admin', 'Role', 'delete', '15');
INSERT INTO `p39_privilege` VALUES ('19', '管理员列表', 'Admin', 'Admin', 'lst', '10');
INSERT INTO `p39_privilege` VALUES ('20', '添加管理员', 'Admin', 'Admin', 'add', '19');
INSERT INTO `p39_privilege` VALUES ('21', '修改管理员', 'Admin', 'Admin', 'edit', '19');
INSERT INTO `p39_privilege` VALUES ('22', '删除管理员', 'Admin', 'Admin', 'delete', '19');
INSERT INTO `p39_privilege` VALUES ('23', '类型列表', 'Admin', 'Type', 'lst', '1');
INSERT INTO `p39_privilege` VALUES ('24', '添加类型', 'Admin', 'Type', 'add', '23');
INSERT INTO `p39_privilege` VALUES ('25', '修改类型', 'Admin', 'Type', 'edit', '23');
INSERT INTO `p39_privilege` VALUES ('26', '删除类型', 'Admin', 'Type', 'delete', '23');
INSERT INTO `p39_privilege` VALUES ('27', '属性列表', 'Admin', 'Attribute', 'lst', '23');
INSERT INTO `p39_privilege` VALUES ('28', '添加属性', 'Admin', 'Attribute', 'add', '27');
INSERT INTO `p39_privilege` VALUES ('29', '修改属性', 'Admin', 'Attribute', 'edit', '27');
INSERT INTO `p39_privilege` VALUES ('30', '删除属性', 'Admin', 'Attribute', 'delete', '27');
INSERT INTO `p39_privilege` VALUES ('31', 'ajax删除商品属性', 'Admin', 'Goods', 'ajaxDelGoodsAttr', '4');
INSERT INTO `p39_privilege` VALUES ('32', 'ajax删除商品相册图片', 'Admin', 'Goods', 'ajaxDelImage', '4');
INSERT INTO `p39_privilege` VALUES ('33', '会员管理', '', '', '', '0');
INSERT INTO `p39_privilege` VALUES ('34', '会员级别列表', 'Admin', 'MemberLevel', 'lst', '33');
INSERT INTO `p39_privilege` VALUES ('35', '添加会员级别', 'Admin', 'MemberLevel', 'add', '34');
INSERT INTO `p39_privilege` VALUES ('36', '修改会员级别', 'Admin', 'MemberLevel', 'edit', '34');
INSERT INTO `p39_privilege` VALUES ('37', '删除会员级别', 'Admin', 'MemberLevel', 'delete', '34');
INSERT INTO `p39_privilege` VALUES ('38', '品牌列表', 'Admin', 'Brand', 'lst', '1');

-- ----------------------------
-- Table structure for p39_role
-- ----------------------------
DROP TABLE IF EXISTS `p39_role`;
CREATE TABLE `p39_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `role_name` varchar(30) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_role
-- ----------------------------
INSERT INTO `p39_role` VALUES ('2', '商品管理员');
INSERT INTO `p39_role` VALUES ('4', '主管');

-- ----------------------------
-- Table structure for p39_sphinx
-- ----------------------------
DROP TABLE IF EXISTS `p39_sphinx`;
CREATE TABLE `p39_sphinx` (
  `id` mediumint(8) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_sphinx
-- ----------------------------
INSERT INTO `p39_sphinx` VALUES ('71');

-- ----------------------------
-- Table structure for p39_type
-- ----------------------------
DROP TABLE IF EXISTS `p39_type`;
CREATE TABLE `p39_type` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `type_name` varchar(30) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_type
-- ----------------------------
INSERT INTO `p39_type` VALUES ('2', '电脑');
INSERT INTO `p39_type` VALUES ('3', '书籍');
INSERT INTO `p39_type` VALUES ('5', '手机');

-- ----------------------------
-- Table structure for p39_yinxiang
-- ----------------------------
DROP TABLE IF EXISTS `p39_yinxiang`;
CREATE TABLE `p39_yinxiang` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` mediumint(8) unsigned NOT NULL COMMENT '商品ID',
  `yx_name` varchar(30) NOT NULL COMMENT '印象名称',
  `yx_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '印象次数',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of p39_yinxiang
-- ----------------------------
INSERT INTO `p39_yinxiang` VALUES ('1', '63', '屏幕大', '5');
INSERT INTO `p39_yinxiang` VALUES ('2', '63', '手感好', '5');
INSERT INTO `p39_yinxiang` VALUES ('3', '63', '画面细腻', '2');
INSERT INTO `p39_yinxiang` VALUES ('4', '63', '电量充足', '2');
INSERT INTO `p39_yinxiang` VALUES ('5', '63', '分辨率高', '4');
INSERT INTO `p39_yinxiang` VALUES ('6', '63', '看得见', '2');
INSERT INTO `p39_yinxiang` VALUES ('7', '63', '便宜', '2');
INSERT INTO `p39_yinxiang` VALUES ('8', '63', '值得购买', '1');
