<?php

class files {
   /**
    *
    */
   function isDeletable ($file) {
      if (is_file ($file)) {
         return is_writable (dirname ($file));
      } 
      else if (is_dir ($file)) {
         return (is_writable (dirname ($file)));// && count (files::scandir ($file)) <= 2);
      }
   }

   /**
    *
    */
   function isWritable ($file) {
      return files::isDeletable ($file);
   }

   /**
    *
    */
   function writeFile ($file, $content, $mode = 'wt') {
      echo "écriture^^,";
      if ($fd = fopen ($file, $mode)) {
         fwrite ($fd, $content);
         fclose ($fd);
         echo "true;";
         return true;
      }
      return false;
   }

   /**
    *
    */
   function deleteFile ($file) {
      if (files::isDeletable ($file)) {
         unlink ($file);
         return true;
      }
      return false;
   }
  }
?>