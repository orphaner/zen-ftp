 CREATE TABLE `users` (
 `User` varchar(16) NOT NULL default '',
 `Password` varchar(32) character set latin1 collate latin1_bin NOT NULL default '',
 `Uid` int(11) NOT NULL default '14',
 `Gid` int(11) NOT NULL default '5',
 `Dir` varchar(128) NOT NULL default '',
 `QuotaFiles` int(10) NOT NULL default '500',
 `QuotaSize` int(10) NOT NULL default '30',
 `ULBandwidth` int(10) NOT NULL default '80',
 `DLBandwidth` int(10) NOT NULL default '80',
 `Ipaddress` varchar(15) NOT NULL default '*',
 `Comment` tinytext,
 `Status` tinyint(1) NOT NULL default '1',
 `ULRatio` smallint(5) NOT NULL default '1',
 `DLRatio` smallint(5) NOT NULL default '1',
 PRIMARY KEY  (`User`),
 UNIQUE KEY `User` (`User`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
