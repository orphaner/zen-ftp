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
    * Singleton qui se connecte � la base de donn�es et assure l'unicit� de 
    * la connection � la base.
    * @param $dbHost Hote de connection � la base
    * @param $dbLogin Le nom d'utilisateur de la abse
    * @param $dbPassword Le mot de passe de l'utilisateur de la base
    * @param $dbDatabase Le nom de la base de donn�e
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
    *  Connection au serveur et � la base de donn�e
    * @access public
    */
   function DbConnect () {
      $this->db_link = pg_connect($this->dsn);

      if (!$this->db_link) {
         die ('Connexion � la base de donn�es impossible !! : '. pg_last_notice($this->db_link));
         exit;
      }
   }

   /**
    * Teste une connection au serveur et � la base de donn�es.
    * Il est conseill� d'apeller la fonction mysqlErr () dans la foul�e pour
    * avoir un message d'erreur d�taill�.
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
    * Effectue une requ�te
    * @param string $squery Requete SQL � effectuer
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
    * Fonction racourci pour compter le nombre de r�sultat.
    * La requ�te doit �tre de type SELECT count (*) FROM ...
    * @param string $squery Requete SQL count(*) � effectuer
    * @access public
    * @return Le nombre d'enregistrements compt�s
    */
   function DbCount ($query) {
      $result = $this->DbQuery ($query);
      $row = $this->DbNextRow($result);
      return $row[0];
   }

   /**
    * Parcours le r�sultat d'une requ�te et retourne un tableau de la ligne
    * courante. Retourne false si il n'y a plus de r�sultats.
    * @access public
    * @param resource $result R�sultat d'un DbQuery
    * @return array Returns an array that corresponds to the fetched row, or
    * FALSE  if there are no more rows.
    */
   function DbNextRow ($result) {
      return pg_fetch_array ($result);
   }

   /**
    * Compte le nombre de r�sultats d'une requ�te
    * @access public
    * @return integer Nombre de r�sultats d'une requ�te
    */
   function DbNumRows ($result) {
      return pg_num_rows ($result);
   }

   /**
    * Ferme la connection � la base de donn�es
    * @access public
    */
   function DbClose () {
      parent::DbClose();
      @pgclose();
   }
   
   /**
    * Retourne l'id auto g�n�r� lors d'une requ�te INSERT
    * @access public
    * @return integer id auto g�n�r�
    */
   function DbGetInsertId ($result) {
      return  pg_last_oid ($result);
   }

   /**
    * Prot�ge les caract�res sp�ciaux d'une commande SQL
    * @param string $string la cha�ne � prot�ger
    * @return string la cha�ne prot�g�e
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
    * Prot�ge une valeur d'un champ de type set d'une commande SQL
    * @param string $set la valeur du champ � prot�ger
    * @return string la cha�ne prot�g�e
    */
   function escapeSet ($set) {
      return "'" . $set . "'";
   }

   function DbAffectedRows ($result) {
      return pg_affected_rows ($result);
   }
}


?>
