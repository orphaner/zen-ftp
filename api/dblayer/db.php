<?php
/**
 * @author Nicolas LASSALLE
 */
class DB {


   /**
    * Lien vers la base de donn�es
    * @access private
    */
   var $dbLink;
   
   /**
    * Nombre de requ�tes effectu�es
    * @access private
    */
   var $nb_sql;
   
   /**
    * H�te du serveur Mysql
    * @access private
    */
   var $dbHost;
     
   /**
    * Login de l'utilisateur mysql
    * @access private
    */
   var $dbLogin;
   
   /**
    * Mot de passe de l'utilisateur
    * @access private
    */
   var $dbPassword;
   
   /**
    * Nom de la base de donn�es
    * @access private
    */
   var $dbDatabase;

   /**
    * Constructeur obligatoire de la classe
    * @param string $dbHost H�te du serveur Mysql
    * @param string $dbLogin Login de l'utilisateur mysql
    * @param string $dbPassword Mot de passe de l'utilisateur
    * @param string $dbDatabase Nom de la base de donn�es
    */
   function DB ($dbHost, $dbLogin, $dbPassword, $dbDatabase) {
      $this->nb_sql = 0;
      $this->dbLink = null;
      $this->result = null;
      $this->dbHost = $dbHost;
      $this->dbLogin = $dbLogin;
      $this->dbPassword = $dbPassword;
      $this->dbDatabase = $dbDatabase;
   }
      
   /**
    * Getter du nombre de requ�tes effectu�es
    * @return integer le nombre de requ�tes SQL effectu�es
    */
   function getNbSql () {
      return $this->nb_sql;
   }

   function DbClose () {
      $this->dbLink = null;
      $instance = null;
   }
}