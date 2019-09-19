DROP TABLE IF EXISTS `ims_vip_activity_user`;CREATE TABLE `ims_vip_activity_user` (    `id`          INT(10) NOT NULL AUTO_INCREMENT COMMENT 'user id 用户ID',    `openid`      VARCHAR(255) COMMENT 'openid openid',    `nickname`    VARCHAR(255) COMMENT 'user nickname 用户昵称',    `avatar`      VARCHAR(255) COMMENT 'user avatar 用户头像',    `authorize_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'authorize time 用户授权时间',    PRIMARY KEY (`id`));DROP TABLE IF EXISTS `ims_vip_activity`;CREATE TABLE `ims_vip_activity` (    `id`          INT(10) NOT NULL AUTO_INCREMENT COMMENT 'activity id 活动表id',    `img_id`      INT(10) COMMENT 'cover img id 分类封面表id',    `openid`      VARCHAR(255) COMMENT 'openid openid',    `title`       VARCHAR(255) COMMENT 'activity title 活动标题',    `description` VARCHAR(255) COMMENT 'activity description 活动描述',    `date`        VARCHAR(255) COMMENT 'date 活动日期',    `time`        VARCHAR(255) COMMENT 'time 活动时间',    `address`     VARCHAR(255) COMMENT 'address 活动地址',    `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'create time 活动创建时间',    `update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time 修改时间',    PRIMARY KEY (`id`));DROP TABLE IF EXISTS `ims_vip_activity_apply`;CREATE TABLE `ims_vip_activity_apply` (    `id`         INT(10) NOT NULL AUTO_INCREMENT COMMENT 'activity apply id 活动报名表id',    `aid`        INT(10) COMMENT 'activity id 活动表id',    `openid`     VARCHAR(255) COMMENT 'openid openid',    `apply_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'apply time 报名时间',    PRIMARY KEY (`id`));DROP TABLE IF EXISTS `ims_vip_activity_category`;CREATE TABLE `ims_vip_activity_category` (    `id`          INT(10) NOT NULL AUTO_INCREMENT COMMENT 'activity category id 活动分类id',    `name`        VARCHAR(255) COMMENT 'category name 分类名称',    `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'create time 分类创建时间',    PRIMARY KEY (`id`));DROP TABLE IF EXISTS `ims_vip_activity_category_cover`;CREATE TABLE `ims_vip_activity_category_cover` (    `id`      INT(10) NOT NULL AUTO_INCREMENT COMMENT 'category cover id 活动分类封面表id',    `cid`     INT(10) COMMENT 'category id 活动分类表id',    `img_url` VARCHAR(255) COMMENT 'category img url 分类封面图地址',    PRIMARY KEY (`id`));