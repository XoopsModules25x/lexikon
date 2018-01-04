# phpMyAdmin SQL Dump
# version 2.8.2.4
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: 19:45 Sunday, September 2nd 2007
# Server version: 3.23.56
# PHP Version: 5.1.6
# 
# Database : `xoopsv3a`
# 

# --------------------------------------------------------

#
# Table structure for table `lxcategories`
#

CREATE TABLE `lxcategories` (
  `categoryID`  TINYINT(4)   NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL DEFAULT '',
  `description` TEXT         NOT NULL,
  `total`       INT(11)      NOT NULL DEFAULT '0',
  `weight`      INT(11)      NOT NULL DEFAULT '1',
  `logourl`     VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY columnID (`categoryID`)
)
  ENGINE = MyISAM;

INSERT INTO lxcategories (categoryID, name, description, total, weight, logourl) VALUES (1, 'Xoops',
                                                                                         'XOOPS is an extensible, OO (Object Oriented), easy to use dynamic web content management system written in PHP. XOOPS is the ideal tool for developing small to large dynamic community websites, intra company portals, corporate portals, weblogs and much more.',
                                                                                         0, 1, '');

#
# Dumping data for table `lxcategories`
#

# --------------------------------------------------------

#
# Table structure for table `lxentries`
#

CREATE TABLE `lxentries` (
  `entryID`    INT(8)           NOT NULL AUTO_INCREMENT,
  `categoryID` TINYINT(4)       NOT NULL DEFAULT '0',
  `term`       VARCHAR(255)     NOT NULL DEFAULT '0',
  `init`       VARCHAR(1)       NOT NULL DEFAULT '0',
  `definition` TEXT             NOT NULL,
  `ref`        TEXT             NOT NULL,
  `url`        VARCHAR(255)     NOT NULL DEFAULT '0',
  `uid`        INT(6)                    DEFAULT '1',
  `submit`     INT(1)           NOT NULL DEFAULT '0',
  `datesub`    INT(11)          NOT NULL DEFAULT '1033141070',
  `counter`    INT(8) UNSIGNED  NOT NULL DEFAULT '0',
  `html`       INT(11)          NOT NULL DEFAULT '0',
  `smiley`     INT(11)          NOT NULL DEFAULT '0',
  `xcodes`     INT(11)          NOT NULL DEFAULT '0',
  `breaks`     INT(11)          NOT NULL DEFAULT '1',
  `block`      INT(11)          NOT NULL DEFAULT '0',
  `offline`    INT(11)          NOT NULL DEFAULT '0',
  `notifypub`  INT(11)          NOT NULL DEFAULT '0',
  `request`    INT(11)          NOT NULL DEFAULT '0',
  `comments`   INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `item_tag`   TEXT,
  PRIMARY KEY (`entryID`),
  UNIQUE KEY entryID (`entryID`),
  FULLTEXT KEY definition (`definition`)
)
  ENGINE = MyISAM;
