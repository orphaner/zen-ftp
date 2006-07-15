<?php


define('PW_USER', 0);
define('PW_PASSWORD', 1);
define('PW_UID', 2);
define('PW_GID', 3);
define('PW_DIR', 5);
define('PW_QUOTAFILES', 11);
define('PW_QUOTASIZE', 12);
define('PW_ULBANDWIDTH', 6);
define('PW_DLBANDWIDTH', 7);
define('PW_IPADDRESS', 15);
define('PW_COMMENT', '');
define('PW_STATUS', 13);
define('PW_ULRATIO', 8);
define('PW_DLRATIO', 9);



/**
 * @author Nicolas LASSALLE
 * @date 12/04/2006
 */
class  UPurepw extends AbstractUser {
   var $passwdFile;

   function UPurepw () {
      if (is_file (CFG_PW_PASSWD_FILE) && !files::isWritable (CFG_PW_PASSWD_FILE)) {
         die ('ERREUR:' . CFG_PW_PASSWD_FILE . ' doit être accessible en écriture');
      }
      if (is_file (CFG_PW_PDB_FILE) && !files::isWritable (CFG_PW_PDB_FILE)) {
         die ('ERREUR:' . CFG_PW_PDB_FILE . ' doit être accessible en écriture');
      }
   }

   /**
    * Destructeur de classe, permet de fermer les fichiers ouverts
    */
   function _UPurepw () {
      if ($this->passwdFile != null) {
         @fclose ($this->passwdFile);
      }
   }

   /**
    * Exécute une commande
    */
   function execCmd ($cmd) {
      if (system($cmd) == false) {
         return false;
      }
      return true;
   }

   /**
    * Ajoute un nouveau client ftp
    * @return true | false
    **/
   function addUser($user) {
      if ($this->existUser($user->getName())) {
         return false;
      }

      $retour = true;
      $tab = $this->userToTab ($user);
      $line = $this->tabToLine ($tab);
      if (!$file = fopen(CFG_PW_PASSWD_FILE, 'a')) {
         return false;
      }
      if (fwrite ($file, $line) == false) {
         $retour = false;
      }
      @fclose ($file);
      return $this->execCmd('pure-pw mkdb');
   }

   /**
    * Supprime un client ftp
    * @return true | false
    **/ 
   function deleteUser($userName) {
      $cmd = 'pure-pw userdel ' .$userName. ' -m';
      return $this->execCmd($cmd);
   }

   /**
    * Modifie le status du client ( bloqué ou non)
    * @return true | false
    **/
   function setStatusUser($userName) {
   }

   /**
    * Modifie données client
    * @return true | false
    **/
   function updateUser($user) {
      $passwdFile = @fopen (CFG_PW_PASSWD_FILE, 'r+');
      if ($passwdFile == null) {
         return false;
      }
      $fileContent = '';
      while ($line = fgets ($passwdFile)) {
         $tab = explode (':', trim($line));
         if ($tab[PW_USER] == $user->getName()) {
            $tab = $this->userToTab ($user);
            $line = $this->tabToLine ($tab);
            $fileContent .= $line . "\n";
         }
         else {
            $fileContent .= $line;
         }
      }
      @fclose ($passwdFile);
      files::deleteFile (CFG_PW_PASSWD_FILE);
      files::writeFile (CFG_PW_PASSWD_FILE, $fileContent);
      return $this->execCmd('pure-pw mkdb');
   }

   /**
    * @return un client en fonction de son nom
    **/
   function getUser($userName) {
      $passwdFile = @fopen (CFG_PW_PASSWD_FILE, 'r+');
      if ($passwdFile == null) {
         return false;
      }
      while ($line = fgets ($passwdFile)) {
         $line = trim ($line);
         $tab = explode (':', $line);
         if ($tab[PW_USER] == $userName) {
            @fclose ($passwdFile);
            return $this->fillUserFromTab ($tab);
         }
      }
      return false;
   }

   /**
    * @return true | false si un user existe ou non
    */
   function existUser ($userName) {
      $passwdFile = @fopen (CFG_PW_PASSWD_FILE, 'r+');
      if ($passwdFile == null) {
         return false;
      }
      while ($line = fgets ($passwdFile)) {
         $line = trim ($line);
         $tab = explode (':', $line);
         if ($tab[PW_USER] == $userName) {
            @fclose ($passwdFile);
            return true;
         }
      }
      return false;
   }

   /**
    * initialise la liste des utilisateurs
    * fonctionne avec nextUser()
    **/
   function getAllUsers() {
      // Si le fichier est déjà ouvert, on le referme
      if ($this->passwdFile != null) {
         @fclose($this->passwdFile);
      }

      // ouverture du fichier
      $this->passwdFile = @fopen (CFG_PW_PASSWD_FILE, 'r+');
      if ($this->passwdFile == null) {
         return false;
      }
   }

   /**
    * @return l'utilisateur suivant de la liste
    * fonctionne avec getAllUser()
    **/
   function nextUser () {
      if ($this->passwdFile != null) {
         while ($line = fgets ($this->passwdFile)) {
            if (trim($line) != '') {
               $line = trim ($line);
               $tab = explode (':', $line);
               $this->currentUser = $this->fillUserFromTab ($tab);
               return $this->currentUser;
            }
         }
      }
      return false;
   }

   /**
    * Cré un UserBean avec un tableau contenant les infos d'une ligne du fichier .passwd
    */
   function fillUserFromTab ($tab) {
      return new UserBean ($tab[PW_USER], 
                           crypt($tab[PW_PASSWORD]), 
                           $tab[PW_UID], 
                           $tab[PW_GID], 
                           $tab[PW_DIR], 
                           $tab[PW_QUOTAFILES], 
                           $tab[PW_QUOTASIZE], 
                           $tab[PW_ULBANDWIDTH], 
                           $tab[PW_DLBANDWIDTH], 
                           $tab[PW_IPADDRESS], 
                           '', 
                           1, 
                           $tab[PW_ULRATIO], 
                           $tab[PW_DLRATIO]);
   }

   /**
    *
    */
   function tabToLine ($tab) {
      $line = '';
      for ($i = 0 ; $i < 16 ; $i++) {
         $line .= $tab[$i] . ':';
      }
      $line .= $tab[16];
      $line .= "\n";
      return $line;
   }

   /**
    * Transforme un UserBean en un tableau de ligne pure-pw
    */
   function userToTab ($user) {
      $tab = array();
      for ($i = 0 ; $i < 17 ; $i++) {
         $tab[$i] = '';
      }
      $tab[PW_USER] = $user->getName(); 
      $tab[PW_PASSWORD] = crypt($user->getPassword()); 
      $tab[PW_UID] = $user->getUid(); 
      $tab[PW_GID] = $user->getGid(); 
      $tab[PW_DIR] = $user->getDir(); 
      $tab[PW_QUOTAFILES] = $user->getQuotaFiles(); 
      $tab[PW_QUOTASIZE] = $user->getQuotaSize(); 
      $tab[PW_ULBANDWIDTH] = $user->getUlBandwidth(); 
      $tab[PW_DLBANDWIDTH] = $user->getDlBandwidth(); 
      $tab[PW_IPADDRESS] = $user->getIpAddress(); 
      $tab[PW_ULRATIO] = $user->getUlRatio(); 
      $tab[PW_DLRATIO] = $user->getDlRatio();
      return $tab;
   }
}
?>
