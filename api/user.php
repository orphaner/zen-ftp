<?php

  /**
   * Classe 'user'
   * @author Jonathan Oyer
   **/

class  AbstractUser {
  
   var $currentUser;

   /**
    * Ajoute un nouveau client ftp
    * @return true | false
    **/
   function addUser($user) {}

   /**
    * Supprime un client ftp
    * @return true | false
    **/ 
   function deleteUser($userName) {}

   /**
    * Modifie le status du client ( bloqué ou non)
    * @return true | false
    **/
   function setStatusUser($userName) {}

   /**
    * Modifie données client
    * @return true | false
    **/
   function updateUser($user) {}

   /**
    * @Return un client en fonction de son nom
    **/
   function getUser($userName) {}

   /**
    * initialise la liste des utilisateurs
    * fonctionne avec nextUser()
    **/
   function getAllUsers() {}

   /**
    * @return l'utilisateur suivant de la liste
    * fonctionne avec getAllUser()
    **/
   function nextUser () {}

  }

?>