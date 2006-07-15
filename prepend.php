<?php

error_reporting(E_ALL);
include ('config.php');

include ('api/dblayer/db.php');
include ('api/dblayer/mysql.inc.php');
include ('api/dblayer/pgsql.inc.php');

include ('api/user.php');
include ('api/backend/user-db.php');
include ('api/backend/user-purepw.php');
include ('api/backend/user-db-pgsql.php');
include ('api/backend/user-db-mysql.php');

include ('api/userbean.php');
include ('api/userbeanform.php');

include ('api/utils/functions.php');
include ('api/utils/files.php');
include ('api/utils/lang.php');

include ('lang/' . LANG . '.php');


switch (CFG_BACKEND) {
   case 'purepw':
      $userMgr = new UPurepw();
      break;

   case 'mysql':
      $userMgr = new UMySQL(CFG_DB_HOST, CFG_DB_LOGIN, CFG_DB_PASSWORD, CFG_DB_DATABASE);
      break;


   case 'pgsql':
      $userMgr = new UPgsql(CFG_DB_HOST, CFG_DB_LOGIN, CFG_DB_PASSWORD, CFG_DB_DATABASE);
      break;
   default:
      die ('CFG_BACKEND incorrect');
}
?>
