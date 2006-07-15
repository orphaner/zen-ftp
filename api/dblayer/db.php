<?php
/**
 * @author Nicolas LASSALLE
 */
class DB {


   /**
    * Lien vers la base de données
    * @access private
    */
   var $dbLink;
   
   /**
    * Nombre de requêtes effectuées
    * @access private
    */
   var $nb_sql;
   
   /**
    * Hôte du serveur Mysql
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
    * Nom de la base de données
    * @access private
    */
   var $dbDatabase;

   /**
    * Constructeur obligatoire de la classe
    * @param string $dbHost Hôte du serveur Mysql
    * @param string $dbLogin Login de l'utilisateur mysql
    * @param string $dbPassword Mot de passe de l'utilisateur
    * @param string $dbDatabase Nom de la base de données
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
    * Getter du nombre de requêtes effectuées
    * @return integer le nombre de requêtes SQL effectuées
    */
   function getNbSql () {
      return $this->nb_sql;
   }

   function DbClose () {
      $this->dbLink = null;
      $instance = null;
   }
}