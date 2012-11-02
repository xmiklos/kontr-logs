#!/packages/run/php/bin/php
<?php

include "config.php";
mysql_query("CREATE TABLE " .SQL_TABLE. " (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(540) collate latin2_czech_cs default NULL,
  `datum` varchar(540) collate latin2_czech_cs default NULL,
  `cas` varchar(540) collate latin2_czech_cs default NULL,
  `agent` varchar(800) collate latin2_czech_cs default NULL,
  `browser` varchar(1000) collate latin2_czech_cs default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin2 COLLATE=latin2_czech_cs AUTO_INCREMENT=0")or die("<blockquote>Nelze vykonat definiční dotaz: " . mysql_error() . "<blockquote>");
echo "HOTOVO !";
?>
