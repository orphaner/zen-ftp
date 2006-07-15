<?php

  /** Classe UMySQL
   * @author Jonathan Oyer
   **/
class AbstractUDb extends AbstractUser {

   var $DB;
   var $res;

   function getUpdateUserSql () {}

   function getAddUserSql () {}

   function getDeleteUserSql () {}

   function getGetUserSql () {}

   function getGetAllUserSql () {}

   function getUpdateStatusUserSql () {}

   function getPasswordSql() {}


   function UDb($DBHost, $DBLogin, $DBPassword, $DBName) {
   }

   /**
    * Ajoute un nouveau client ftp
    * @return true | false
    **/
   function addUser($user){
      return $this->DB->DbQuery(sprintf($this->getAddUserSql(),
                                        $this->DB->escapeString($user->getName()),
                                        $this->DB->escapeString(md5($user->getPassword())),
                                        $this->DB->escapeString($user->getUid()),
                                        $this->DB->escapeString($user->getGid()),
                                        $this->DB->escapeString($user->getDir()),
                                        $this->DB->escapeString($user->getQuotaFiles()),
                                        $this->DB->escapeString($user->getQuotaSize()),
                                        $this->DB->escapeString($user->getUlBandwidth()),
                                        $this->DB->escapeString($user->getDlBandwidth()),
                                        $this->DB->escapeString($user->getIpAddress()),
                                        $this->DB->escapeString($user->getComment()),
                                        $this->DB->escapeString($user->getStatus()),
                                        $this->DB->escapeString($user->getUlRatio()),
                                        $this->DB->escapeString($user->getDlRatio())));
   }

   /**
    * Supprime un client ftp
    * @return true | false
    */
   function deleteUser($userName){
      return $this->DB->DbQuery(sprintf($this->getDeleteUserSql(), $this->DB->escapeString($userName)));
   }

   /**
    * Modifie le status du client ( bloqué ou non)
    * @return true | false
    **/
   function setStatusUser($userName, $status){
      return $this->DB->DbQuery(sprintf($this->getUpdateStatusUserSql(),
                                        $this->DB->escapeString($status),
                                        $this->DB->escapeString($userName)));
   }
   
   /**
    * Modifie données client
    * @return true | false
    **/
   function updateUser($user){
      if (trim($user->getPassword()) == '') {
         $sql = sprintf($this->getPasswordSql(), $this->DB->escapeString($user->getName()));
         $res = $this->DB->DbQuery($sql);
         $row = $this->DB->DbNextRow($res);
         $password = $row['Password'];
      }
      else {
         $password = md5($user->getPassword());
      }
      return $this->DB->DbQuery(sprintf($this->getUpdateUserSql(),
                                        $this->DB->escapeString($password),
                                        $this->DB->escapeString($user->getUid()),
                                        $this->DB->escapeString($user->getGid()),
                                        $this->DB->escapeString($user->getDir()),
                                        $this->DB->escapeString($user->getQuotaFiles()),
                                        $this->DB->escapeString($user->getQuotaSize()),
                                        $this->DB->escapeString($user->getUlBandwidth()),
                                        $this->DB->escapeString($user->getDlBandwidth()),
                                        $this->DB->escapeString($user->getUlRatio()),
                                        $this->DB->escapeString($user->getDlRatio()),
                                        $this->DB->escapeString($user->getStatus()),
                                        $this->DB->escapeString($user->getIpAddress()),
                                        $this->DB->escapeString($user->getComment()),
                                        $this->DB->escapeString($user->getName())));
   }

   /**
    * @Return un client en fonction de son nom | null si le client n'existe pas
    **/
   function getUser($userName){
      $res  = $this->DB->DbQuery(sprintf($this->getGetUserSql(), $this->DB->escapeString($userName)));
      if ($this->DB->DbNumRows($res) == 0) {
         return null;
      }
      $row = $this->DB->DbNextRow($res);
      $this->currentUser = $this->getUserFromRow ($row);
      return $this->currentUser;
   }
   
   /**
    * initialise la liste des utilisateurs
    * fonctionne avec nextUser()
    **/
   function getAllUsers(){
      $this->res = $this->DB->DbQuery($this->getGetAllUserSql());
   }

   /**
    * @return l'utilisateur suivant de la liste
    * fonctionne avec getAllUser()
    **/
   function nextUser () {
      if ($row = $this->DB->DbNextRow($this->res)) {
         return $this->getUserFromRow($row);
      }
      return false;
   }

   function getUserFromRow ($row) {
      return new UserBean($row['User'],
                          $row['Password'],
                          $row['Uid'],
                          $row['Gid'],
                          $row['Dir'],
                          $row['QuotaFiles'],
                          $row['QuotaSize'],
                          $row['ULBandwidth'],
                          $row['DLBandwidth'],
                          $row['Ipaddress'],
                          $row['Comment'],
                          $row['Status'],
                          $row['ULRatio'],
                          $row['DLRatio']);
   }
  }

?>
