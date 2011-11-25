-- 
-- Table `tl_oi_files`
-- 

CREATE TABLE `tl_oi_files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `enabled` int(1) unsigned NOT NULL default '0',
  `jumpTo` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `oi_reader_rewrite_title` int(1) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `oi_objectid` varchar(255) NOT NULL default ''
  `oi_random_object` int(1) unsigned NOT NULL default '0',
  `oi_objects_map_size_crop` varchar(7) NOT NULL default '130x115',
  `oi_objects_map_size_full` varchar(7) NOT NULL default '512x512',
  `oi_searchform_jumpto` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------