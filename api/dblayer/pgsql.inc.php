<?php


  /**
   * @author Nicolas LASSALLE
   */
class pgsqlDB extends DB {
   var $dsn = '';

   function pgsqlDB ($dbHost, $dbLogin, $dbPassword, $dbDatabase) {
      parent::DB($dbHost, $dbLogin, $dbPassword, $dbDatabase);
      $this->dsn = sprintf('host=%s port=5432 dbname=%s user=%s password=%s',
                           $this->dbHost, $this->dbDatabase, $this->dbLogin, $this->dbPassword);
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
         $instance = new pgsqlDB ($dbHost, $dbLogin, $dbPassword, $dbDatabase);
         $instance->DbConnect();
      }
      return $instance;
   }

   /**
    *  Connection au serveur et à la base de donnée
    * @access public
    */
   function DbConnect () {
      $this->db_link = pg_connect($this->dsn);

      if (!$this->db_link) {
         die ('Connexion à la base de données impossible !! : '. pg_last_notice($this->db_link));
         exit;
      }
   }

   /**
    * Teste une connection au serveur et à la base de données.
    * Il est conseillé d'apeller la fonction mysqlErr () dans la foulée pour
    * avoir un message d'erreur détaillé.
    * @access public
    * @return boolean true / false 
    */
   function testDbConnect () {
      if (!$this->db_link = pg_connect($this->dsn)) {
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
      $result = pg_query ($this->db_link, $query) 
         or die ('<br /><strong>ERREUR</strong> '.(pg_last_notice($this->db_link)).'<br /><strong>Requete</strong>: '.$query); 
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
      $row = $this->DbNextRow($result);
      return $row[0];
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
      return pg_fetch_array ($result);
   }

   /**
    * Compte le nombre de résultats d'une requête
    * @access public
    * @return integer Nombre de résultats d'une requête
    */
   function DbNumRows ($result) {
      return pg_num_rows ($result);
   }

   /**
    * Ferme la connection à la base de données
    * @access public
    */
   function DbClose () {
      parent::DbClose();
      @pgclose();
   }
   
   /**
    * Retourne l'id auto généré lors d'une requête INSERT
    * @access public
    * @return integer id auto généré
    */
   function DbGetInsertId ($result) {
      return  pg_last_oid ($result);
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
         $string = "'" .pg_escape_string($string) . "'";
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

   function DbAffectedRows ($result) {
      return pg_affected_rows ($result);
   }
}


?>
