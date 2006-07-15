<?php

  /**
   * Classe 'UserBean'
   * BEAN Utilisateur provenant d'un formulaire
   * @author Nicolas LASSALLE
   **/
class UserBeanForm extends UserBean {
   var $password2 = '';
   var $errors = array();

   function getPassword2() {
      return unprotegeInput($this->password2);
   }

   function UserBeanForm($name='', $password='', $uid='', $gid='', $dir='', 
                         $quotaFiles=0, $quotaSize=0, $ulBandwidth=0, 
                         $dlBandwidth=0, $ipAddress='*', $comment='', $status=1, 
                         $ulRatio=0, $dlRatio=0) {
      parent::UserBean($name, $password, $uid, $gid, $dir, $quotaFiles, 
                       $quotaSize, $ulBandwidth, $dlBandwidth, $ipAddress, 
                       $comment, $status, $ulRatio, $dlRatio);

   }
   
   function fillFromPost () {

      // Remplissage à partir du formulaire
      while (list ($var,$val) = each ($_POST)) {
         if ($var == 'status' && $val == 'on') {
            $this->status = 1;
         }
         else if (isset($this->{$var})) {
            $this->{$var} = protegeInput(trim($val));
         }
      }
      reset ($_POST);
   }

   function verifUser ($passwordNeeded=false) {
      $neededFields = array ('name', 'uid', 'gid', 'dir');
      if ($passwordNeeded) {
         $neededFields[] = 'password';
         $neededFields[] = 'password2';
      }
      while (list(, $field) = each ($neededFields)) {
         if ($this->{$field} == '') {
            $this->errors[$field] = _gl('ERR_EMPTY');
         }
      }

      $numericFields = array ('quotaFiles', 'quotaSize', 'ulBandwidth', 'dlBandwidth', 
                              'ulRatio', 'dlRatio');
      while (list(, $field) = each ($numericFields)) {
         if ($this->{$field} != '' && !is_numeric($this->{$field})) {
            $this->errors[$field] = _gl('ERR_NUMERIC');
         }
      }

      if ($this->password != $this->password2) {
         $this->errors['password'] = _gl('ERR_PASSWORD');
      }

      return (count($this->errors) == 0);
   }

   function error ($champ) {
      if (isset($this->errors[$champ])) {
         return '<br /><span class="error">' . $this->errors[$champ] . '</span>';
      }
      return '';
   }
  }

?>
