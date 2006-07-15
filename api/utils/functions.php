<?php

  /**
   * Protect an input filed
   */
function protegeInput ($input) {
   if (!get_magic_quotes_gpc()) {
      $in=addslashes(trim($input));
   }
   $in = htmlspecialchars($input);
   return $input;
  }

function unProtegeInput ($input) {
   return htmlentities(stripslashes(trim($input)));
}


// Read the userfile for example '/etc/passwd'
// todo check security settings of php
function getUnixUsers () {
   global $blacklistUsers;
   if ($fh = @fopen('/etc/passwd', 'r')) {
      while (($line = fgets($fh,4096))) {
         $data = explode(':', $line);
         $user = trim($data[0]);
         $user_id = trim($data[2]);
      
         if ($user[0] != '#' && strlen($user) != 0 && strlen($user_id) != 0) {
            $unixUsers[$user_id] = $user;
         }
      }
      fclose($fh);
   }
   else {
      return false;
   }

   $unixUsers = array_diff($unixUsers, $blacklistUsers);
   uasort($unixUsers, 'strnatcasecmp');
   return ($unixUsers);
}

// Read the groupfle for example '/etc/groups'
function getUnixGroups () {
   global $blacklistGroups;
   if ($fh = @fopen('/etc/group', 'r')) {
      while (($line = fgets($fh,4096))) {
         $data = explode(':', $line);
         $group = trim($data[0]);
         $groupId = trim($data[2]);
      
         if ($group[0] != '#' && strlen($group) != 0 && strlen($groupId) != 0) {
            $unixGroups[$groupId] = $group;
         }
      }
      fclose($fh);
   }
   else {
      return false;
   }

   $unixGroups = array_diff($unixGroups, $blacklistGroups);
   uasort($unixGroups, 'strnatcasecmp');
   return ($unixGroups);
}


function  selectOptions($values, $default) {
   while (list($key,$val) = each($values)) {
      $selected = '';
      if ($key == $default) {
         $selected = ' selected="selected" ';
      }
      echo "<option value=\"$key\" $selected>$val</option>\n";
   }
}
?>