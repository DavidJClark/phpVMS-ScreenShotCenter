CREATE TABLE IF NOT EXISTS `phpvms_screenshots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(200) NOT NULL,
  `file_description` varchar(150) NOT NULL,
  `pilot_id` int(5) NOT NULL,
  `date_uploaded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `file_approved` int(1) NOT NULL DEFAULT '0',
  `views` int(4) NOT NULL DEFAULT '0',
  `rating` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `phpvms_screenshots_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pilot_id` int(11) NOT NULL,
  `ss_id` int(11) NOT NULL,
  `comment` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `phpvms_screenshots_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ss_id` int(4) NOT NULL,
  `pilot_id` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
