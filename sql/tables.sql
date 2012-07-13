CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL auto_increment,
  `table_id` int(11) NOT NULL,
  `black_sid` varchar(32) NOT NULL,
  `red_sid` varchar(32) NOT NULL,
  `red_time` int(11) NOT NULL,
  `black_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MEMORY;