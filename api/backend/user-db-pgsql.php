<?php

  /** Classe UPostGres
   * @author Jonathan Oyer
   **/

class UPgSQL extends AbstractUDb {


   /**
    * Constructeur de la class
    * Initialise la connexion
    */
   function UPgSQL($DBHost, $DBLogin, $DBPassword, $DBName) {
      $this->DB =& pgsqlDB::getInstance($DBHost, $DBLogin, $DBPassword, $DBName);
   }

   /**
    * @return requete update format PostGres
    **/
   function getUpdateUserSql () {
      return ' UPDATE users SET  "Password" = %s, "Uid" = %s, "Gid" = %s, "Dir" = %s,  "QuotaFiles" = %s, "QuotaSize" = %s, "ULBandwidth" =  %s, "DLBandwidth" = %s, "ULRatio" = %s, "DLRatio" = %s,  "Status" = %s,  "Ipaddress" = %s,  "Comment" = %s  WHERE "User" = %s ';
   }

   /**
    * @return requete update user format PostGres
    **/
   function getAddUserSql () {
      return "INSERT INTO users VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
   }

   /**
    * @return requete delete user format PostGres
    **/
   function getDeleteUserSql () {
      return 'DELETE FROM users WHERE "User" = %s';
   }

   /**
    * @return requete select user format PostGres
    **/
   function getGetUserSql () {
      return 'SELECT * FROM users WHERE "User" = %s ORDER BY "User" ASC';
   }

   /**
    * @return requete select all users format PostGres
    **/
   function getGetAllUserSql () {
      return 'SELECT * FROM users ORDER BY "User" ASC';
   }

   /**
    * @return requete update status format PostGres
    **/
   function getUpdateStatusUserSql () {
      return 'UPDATE users SET "Status" = %s WHERE "User" = %s';
   }

   /**
   * @return requete select password format mySQL
   **/
   function getPasswordSql () {
      return 'SELECT "Password" FROM users WHERE "User"=%s';
   }


  }

?>
