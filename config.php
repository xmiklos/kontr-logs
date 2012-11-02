<?php

  define("SQL_HOST","db.fi.muni.cz");
  define("SQL_DBNAME","dbxmiklos");
  define("SQL_USERNAME","xmiklos");
  define("SQL_PASSWORD","08021991");

mysql_connect(SQL_HOST, SQL_USERNAME, SQL_PASSWORD) or die("Chyba !!! - " . mysql_error());
      mysql_select_db(SQL_DBNAME) or die("Chyba !!! - " . mysql_error());

define("SQL_TABLE", "logs_stat");

?>
