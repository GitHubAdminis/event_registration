DROP TABLE IF EXISTS `apply_vip_activity`;
CREATE TABLE `apply_vip_activity`
(
    `id`                    int(4) NOT NULL AUTO_INCREMENT COMMENT 'activity id',
    `title`                 varchar(255) COMMENT 'activity title',
    `content`               varchar(255) COMMENT 'activity content',
    `price`                 varchar(255) COMMENT 'activity price',
    `authorize_time`        varchar(255) COMMENT 'authorize_time',
    `custom_share_icon`     varchar(255) COMMENT 'custom_share_icon',
    `custom_share_title`    varchar(255) COMMENT 'custom_share_title',
    `custom_share_describe` varchar(255) COMMENT 'custom_share_describe',
    `custom_share_website`  varchar(255) COMMENT 'custom_share_website',
    `creation_time`         varchar(255) COMMENT 'creation_time',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
    COMMENT = 'activity table'
  DEFAULT CHARSET = utf8;