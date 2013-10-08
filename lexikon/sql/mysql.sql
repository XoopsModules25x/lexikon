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
	`categoryID` tinyint(4) NOT NULL auto_increment,
	`name` varchar(100) NOT NULL default '',
	`description` text NOT NULL,
	`total` int(11) NOT NULL default '0',
	`weight` int(11) NOT NULL default '1',
	`logourl` varchar(150) NOT NULL default '',
	PRIMARY KEY  (`categoryID`),
	UNIQUE KEY columnID (`categoryID`)
) ENGINE=MyISAM;	

INSERT INTO lxcategories (categoryID, name, description, total, weight, logourl ) VALUES (1, 'Xoops', 'XOOPS is an extensible, OO (Object Oriented), easy to use dynamic web content management system written in PHP. XOOPS is the ideal tool for developing small to large dynamic community websites, intra company portals, corporate portals, weblogs and much more.', 0, 1, '');

#
# Dumping data for table `lxcategories`
#

# --------------------------------------------------------

#
# Table structure for table `lxentries`
#

CREATE TABLE `lxentries` (	
	`entryID` int(8) NOT NULL auto_increment,
	`categoryID` tinyint(4) NOT NULL default '0',
	`term` varchar(255) NOT NULL default '0',
	`init` varchar(1) NOT NULL default '0',
	`definition` text NOT NULL,
	`ref` text NOT NULL,
	`url` varchar(255) NOT NULL default '0',
	`uid` int(6) default '1',
	`submit` int(1) NOT NULL default '0',
	`datesub` int(11) NOT NULL default '1033141070',
	`counter` int(8) unsigned NOT NULL default '0',
	`html` int(11) NOT NULL default '0',
	`smiley` int(11) NOT NULL default '0',
	`xcodes` int(11) NOT NULL default '0',
	`breaks` int(11) NOT NULL default '1',
	`block` int(11) NOT NULL default '0',
	`offline` int(11) NOT NULL default '0',
	`notifypub` int(11) NOT NULL default '0',
	`request` int(11) NOT NULL default '0',
	`comments` int(11) unsigned NOT NULL default '0',
	`item_tag` TEXT NOT NULL,
	PRIMARY KEY  (`entryID`),
	UNIQUE KEY entryID (`entryID`),
	FULLTEXT KEY definition (`definition`)
) ENGINE=MyISAM;	
