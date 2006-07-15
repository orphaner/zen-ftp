<?php

  /**
   * Classe 'UserBean'
   * BEAN Utilisateur
   * @author Jonathan Oyer
   **/

class  UserBean {
   
   //login du client ftp
   var $name;
   //Password du client ftp
   var $password;
   //Uid 
   var $uid;
   //Gid
   var $gid;
   //Repertoire de transfert du client
   var $dir;
   //QuotaFiles
   var $quotaFiles;
   //QuotasSize
   var $quotaSize;
   //ULBandwidth
   var $ulBandwidth;
   //DLBandwidth
   var $dlBandwidth;
   //Ipaddress
   var $ipAddress;
   //Comment
   var $comment;

   /**
    * Status
    * 1: unlock | 0: lock
    */
   var $status;
   //ULRation
   var $ulRatio;
   //DLRatio
   var $dlRatio;

   function UserBean($name='', $password='', $uid='', $gid='', $dir='', $quotaFiles='', $quotaSize='', $ulBandwidth='', $dlBandwidth='', $ipAddress='', $comment='', $status='', $ulRatio='', $dlRatio='') {
      $this->name = $name;
      $this->password = $password;
      $this->uid = $uid;
      $this->gid = $gid;
      $this->dir = $dir;
      $this->quotaFiles = $quotaFiles;
      $this->quotaSize = $quotaSize;
      $this->ulBandwidth = $ulBandwidth;
      $this->dlBandwidth = $dlBandwidth;
      $this->ipAddress = $ipAddress;
      $this->comment = $comment;
      $this->status = $status;
      $this->ulRatio = $ulRatio;
      $this->dlRatio = $dlRatio;
   }

   function getName() {
      return unProtegeInput($this->name);
   }

   function getPassword() {
      return unProtegeInput($this->password);
   }

   function getUid() {  
      return unProtegeInput($this->uid);
   }

   function getGid() {
      return unProtegeInput($this->gid);
   }
   function getDir() {
      return unProtegeInput($this->dir);
   }

   function getQuotaFiles() {
      return unProtegeInput($this->quotaFiles);
   }

   function getQuotaSize() {
      return unProtegeInput($this->quotaSize);
   }

   function getUlBandwidth() {
      return unProtegeInput($this->ulBandwidth);
   }

   function getDlBandwidth() {
      return unProtegeInput($this->dlBandwidth);
   }

   function getIpAddress() {
      return unProtegeInput($this->ipAddress);
   }

   function getComment() {
      return unProtegeInput($this->comment);
   }

   function getStatus() {
      return unProtegeInput($this->status);
   }

   function getUlRatio() {
      return unProtegeInput($this->ulRatio);
   }

   function getDlRatio() {
      return unProtegeInput($this->dlRatio);
   }

   function error() {}
  }

?>
