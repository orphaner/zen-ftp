<?php

include_once ('db.php');

/**
 * @author Nicolas LASSALLE
 */
class mysqlDB extends DB {
   
   /**
    * Constructeur de classe. Ne doit pas être utilisé directement. 
    * Il faut passer par la fonction getInstance qui est un singleton.
    * @param $dbHost Hote de connection à la base
    * @param $dbLogin Le nom d'utilisateur de la abse
    * @param $dbPassword Le mot de passe de l'utilisateur de la base
    * @param $dbDatabase Le nom de la base de donnée
    * @access private
    */
   function mysqlDB ($dbHost, $dbLogin, $dbPassword, $dbDatabase) {
      parent::DB($dbHost, $dbLogin, $dbPassword, $dbDatabase);
   }

   /**
    * Singleton qui se connecte à la base de données et assure l'unicité de 
    * la connection à la base.
    * @param $dbHost Hote de connection à la base
    * @param $dbLogin Le nom d'utilisateur de la abse
    * @param $dbPassword Le mot de passe de l'utilisateur de la base
    * @param $dbDatabase Le nom de la base de donnée
    * @access private
    * @return Une instance de l'objet mysqlDB
    */
   function &getInstance($dbHost, $dbLogin, $dbPassword, $dbDatabase) {
      static $instance ;
      if (!$instance) {
         $instance = new mysqlDB ($dbHost, $dbLogin, $dbPassword, $dbDatabase);
         $instance->DbConnect();
      }
      return $instance;
   }

   /**
    *  Connection au serveur et à la base de donnée
    * @access public
    */
   function DbConnect () {
      $this->dbLink = @mysql_connect ($this->dbHost , $this->dbLogin , $this->dbPassword) 
         or die('Connexion à la base de données impossible !! : '.$this->mysqlErr());
      @mysql_select_db ($this->dbDatabase) 
         or die('Sélection de la table impossible !!'.$this->mysqlErr());
   }
   
   /**
    * Teste une connection au serveur et à la base de données.
    * Il est conseillé d'apeller la fonction mysqlErr () dans la foulée pour
    * avoir un message d'erreur détaillé.
    * @access public
    * @return boolean true / false 
    */
   function testDbConnect () {
      if (!$this->dbLink = @mysql_connect ($this->dbHost , $this->dbLogin , $this->dbPassword)) {
         return false;
      }
      if (!@mysql_select_db ($this->dbDatabase)) {
         return false;
      }
      return true;
   }

   /**
    * Effectue une requête
    * @param string $squery Requete SQL à effectuer
    * @access public
    * @return resource
    */
   function DbQuery ($query) {
      $this->nb_sql++;
      $result = mysql_query ($query, $this->dbLink) 
         or die ('<br /><strong>ERREUR</strong> '.($this->mysqlErr()).'<br /><strong>Requete</strong>: '.$query); 
      return $result;
   }

   /**
    * Fonction racourci pour compter le nombre de résultat.
    * La requête doit être de type SELECT count (*) FROM ...
    * @param string $squery Requete SQL count(*) à effectuer
    * @access public
    * @return Le nombre d'enregistrements comptés
    */
   function DbCount ($query) {
      $result = $this->DbQuery ($query);
      return mysql_result ($result, 0, "COUNT(*)");
   }

   /**
    * Parcours le résultat d'une requête et retourne un tableau de la ligne
    * courante. Retourne false si il n'y a plus de résultats.
    * @access public
    * @param resource $result Résultat d'un DbQuery
    * @return array Returns an array that corresponds to the fetched row, or
    * FALSE  if there are no more rows.
    */
   function DbNextRow ($result) {
      return mysql_fetch_array ($result);
   }

   /**
    * Compte le nombre de résultats d'une requête
    * @access public
    * @return integer Nombre de résultats d'une requête
    */
   function DbNumRows ($result) {
      return mysql_num_rows ($result);
   }

   /**
    * Ferme la connection à la base de données
    * @access public
    */
   function DbClose () {
      parent::DbClose();
      @mysql_close();
   }
   
   /**
    * Retourne l'id auto généré lors d'une requête INSERT
    * @access public
    * @return integer id auto généré
    */
   function DbGetInsertId ($res='') {
      return mysql_insert_id();
   }
   
   /**
    * Retourne la liste des tables de la base sélectionnée
    * @access public
    * @return resource
    */
   function getTableList () {
      return $this->DbQuery('SHOW TABLES FROM '.$this->mysqlmaindb);
   }
   
   /**
    * Retourne un message d'erreur plus parlant que celui par défaut de la
    * fonction mysql_error()
    * @access public
    * @return string message d'erreur
    */
   function mysqlErr() {
      $partie = explode('\'', mysql_error() ); // On découpe le message d'erreur retourné par mysql_error()   
     
      switch   (mysql_errno() ) { // On cherche quel N° d'erreur SQL à été retouné
         case 1040 : // Too many connections
            return 'Trop de connections simultanées. Merci de revenir dans quelques minutes ou de recharger la page';
         case 1044 : // Access denied for user: 'login' to database 'nombase'
            return 'La base de données "'.$this->DbHost.'" n\'a pas été trouvée.';
         case 1045 : // Access denied for user: 'login' (Using password: YES)
            return 'L\'utilisateur désigné "'.$this->dbLogin.'" n\'a pas été trouvé. ' .
                  'Le mot de passe est peut être incorrect.';
         case 1046 : // No Database Selected
            return 'Aucune base de données n\'à été sélectionnée.</p>';
         case 1052 : // Column: 'champ' in where clause is ambiguous
            return 'La clause WHERE est ambiguë pour la colonne '.$partie[1].'.';
         case 1053 : // Server shutdown in progress
            return 'Le serveur SQL à été arrêté. Essayez de recharger la page ou revenez dans quelques minutes.';
         case 1054 :
            switch   ($partie[3]) { // On cherche quelle chaine est retournée par $morceau[3]
               case 'field list' : // Unknown column 'nomChamp' in 'field list'
                  return 'Le champ "'.$partie[1].'" précisé dans la liste des champs n\'a pas été trouvé.';
               case 'where clause' : // Unknown column 'nomChamp' in 'where clause'
                  return 'Le champ "'.$partie[1].'" précisé dans la clause WHERE n\'a pas été trouvé.';
               default :
                  return mysql_error();
            }
            break;
         case 1064 : // You have an error in your SQL syntax. Check the manual that corresponds to your MySQL server version for the right syntax to use near 'requete'
            return 'Une erreur de syntaxe SQL se trouve dans la requête "'.$partie[1].'".';         
         case 1065 : // Query was empty
            return 'Aucune requête SQL n\'à été trouvée.';         
         case 1109 : // Unknown table 'nom_table' in where clause
         case 1146 :
            return 'La table "'.$partie[1].'" n\'a pas été trouvée dans la clause WHERE.';         
         case 2002 : // Can't connect to local MySQL server through socket 'chemnin d'accès' (2)
            return 'Échec lors de la connection au serveur SQL.';
         case 2005 : // Unknown MySQL Server Host 'serveur' (2)
            return 'Le serveur SQL "'.$partie[1].'" n\'a pas été trouvé.';
         case 2013 : // Lost connection to MySQL server during query
            return 'La connection au serveur SQL à été perdue lors de la requête. Essayez de recharger la page pour résoudre le problème.';
         default :
            return mysql_error();
      }
   }
   
   /**
    * Getter du nombre de requêtes effectuées
    * @return integer le nombre de requêtes SQL effectuées
    */
   function getNbSql () {
      return $this->nb_sql;
   }
   
   /**
    * Protège les caractères spéciaux d'une commande SQL
    * @param string $string la chaîne à protéger
    * @return string la chaîne protégée
    */
   function escapeString ($string) {
      // Stripslashes
      if (get_magic_quotes_gpc()) {
         $string = stripslashes($string);
      }
      // Protection si ce n'est pas un entier
      if (!is_numeric($string)) {
         $string = "'" . mysql_real_escape_string($string) . "'";
      }
      return $string;
   }
   
   /**
    * Protège une valeur d'un champ de type set d'une commande SQL
    * @param string $set la valeur du champ à protéger
    * @return string la chaîne protégée
    */
   function escapeSet ($set) {
      return "'" . $set . "'";
   }

   function DbAffectedRows ($res = '') {
      return mysql_affected_rows ($this->dbLink);
   }
}

?>