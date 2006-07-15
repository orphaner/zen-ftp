<?php

include('prepend.php');

$affUser = null;
$password1 = '';
$password2 = '';

$f = '';
if (isset ($_GET['f'])) {
   $f = $_GET['f'];
}
$valid = '';
if (isset ($_GET['valid'])) {
   $valid = $_GET['valid'];
}
$id = '';
if (isset ($_GET['id'])) {
   $id = $_GET['id'];
}

/**------------------------------------------
 * Edition d'un utilisateur
 */
if ($f == 'edite') {
   $checkUser = $userMgr->getUser($id);
   if ($checkUser == null) {
      $message = 'USER_NOT_FOUND';
   }
   else {
      $actionForm = '?f=edite&amp;valid=1&amp;id='.$id;
      $legend = 'LEGEND_MODIFY';
      $buttonValue = 'BUTTON_MODIFY';
      if ($valid == 1) {
         $affUser = new UserBeanForm();
         $affUser->fillFromPost();
         if ($affUser->verifUser()) {
            $userMgr->updateUser ($affUser);
            $message = 'USER_MODIFIED';
         }
         else {
            $password1 = $affUser->getPassword();
            $password2 = $affUser->getPassword2();
         }
      }
      else {
         $affUser = $checkUser;
      }
   }
}

/**------------------------------------------
 * Ajout d'un utilisateur
 */
else if ($f == 'ajout') {
   $actionForm = '?f=ajout&amp;valid=1';
   $legend = 'LEGEND_ADD';
   $buttonValue = 'BUTTON_ADD';
   if ($valid == 1) {
      $affUser = new UserBeanForm();
      $affUser->fillFromPost();
      $checkUser = $userMgr->getUser($affUser->getName());
      if ($checkUser != null) {
         $affUser->errors['name'] = _gl('USER_ALREADY_EXISTS');
         $password1 = $affUser->getPassword();
         $password2 = $affUser->getPassword2();
      }
      else if ($affUser->verifUser(true)) {
         $userMgr->addUser ($affUser);
         $message = 'USER_ADDED';
      }
      else {
         $password1 = $affUser->getPassword();
         $password2 = $affUser->getPassword2();
      }
   }
   else {
      $affUser = new UserBeanForm();
      $currentUid = CFG_DEFAULT_UID;
      $currentGid = CFG_DEFAULT_GID;
   }
}

/**------------------------------------------
 * Suppression d'un utilisateur
 */
else if ($f == 'suppr') {
   if ($id != '') {
      $userMgr->deleteUser($_GET['id']);
   }
   $f = '';
}

/**------------------------------------------
 * Désactivation d'un utilisateur
 */
else if ($f == 'lock') {
   $checkUser = $userMgr->getUser($id);
   if ($checkUser == null) {
      $message = 'USER_NOT_FOUND';
   }
   else {
      $userMgr->setStatusUser($id, 0);
   }
   $f = '';
}

/**------------------------------------------
 * Activation d'un utilisateur
 */
else if ($f == 'unlock') {
   $checkUser = $userMgr->getUser($id);
   if ($checkUser == null) {
      $message = 'USER_NOT_FOUND';
   }
   else {
      $userMgr->setStatusUser($id, 1);
   }
   $f = '';
}


// Récupère tous les utilisateurs pour l'affichage du tableau
$userMgr->getAllUsers();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="MSSmartTagsPreventParsing" content="TRUE" />
    <link rel="stylesheet" href="style/default.css" type="text/css" />
    <title>The Pure Admin</title>
  </head>

  <body>
    <div id="page">
      <h1 id="list"><?php _l('H1_USER_LIST');?></h1>
      <div id="actions">
        <ul><li><a href="?f=ajout"><?php _l('ADD_USER');?></a></li></ul>
      </div>
      <table id="userList">
        <tr class="column_name_select_user">
          <th><?php _l('TH_NAME');?></th>
          <th><?php _l('TH_UID');?></th>
          <th><?php _l('TH_GID');?></th>
          <th><?php _l('TH_DIR');?></th>
          <th><?php _l('TH_DL');?></th>
          <th><?php _l('TH_UL');?></th>
          <th><?php _l('TH_CONFIG');?></th>
        </tr>
        <?php while (($cUser = $userMgr->nextUser())): 
                 if ($cUser->getStatus() == 1) {
                    $class = "select_user";
                    $prefix = '';
                    $lockAction = 'lock';
                 }
                 else {
                    $class = "select_locked_user";
                    $prefix = 'lock_';
                    $lockAction = 'unlock';
                 }
        ?>
        <tr class="<?=$class;?>">
          <td>
            <a href="?f=edite&amp;id=<?=$cUser->getName();?>" title="<?php _l('ALT_MODIFY');?>">
              <img src="images/<?=$prefix;?>usericon.png" style="margin:0px 0px 4px ; vertical-align:middle" alt="<?php _l('ALT_MODIFY');?>"/>
            </a>
            <a href="?f=edite&amp;id=<?=$cUser->getName();?>" title="<?php _l('ALT_MODIFY');?>">
              <?=$cUser->getName();?>
            </a>
          </td>
          <td><?=$cUser->getUid(); ?></td>
          <td><?=$cUser->getGid(); ?></td>
          <td><?=$cUser->getDir(); ?></td>
          <td><?=$cUser->getDlBandwidth(); ?></td>
          <td><?=$cUser->getUlBandwidth(); ?></td>
          <td>
            <a href="?f=edite&amp;id=<?=$cUser->getName();?>" title="<?php _l('ALT_MODIFY');?>">
              <img src="images/<?=$prefix;?>edit.png" alt="<?php _l('ALT_MODIFY');?>"/>
            </a>
            <a href="javascript:if(window.confirm('<?php _l('DELETE_CONFIRM');?>')) window.location='?f=suppr&amp;id=<?=$cUser->getName();?>'" title="<?php _l('ALT_DELETE');?>">
              <img src="images/<?=$prefix;?>trash.png" alt="<?php _l('ALT_DELETE');?>"/>
            </a>
            <a href="?f=<?=$lockAction;?>&amp;id=<?=$cUser->getName();?>" title="<?php _l('ALT_LOCK');?>">
              <img src="images/<?=$prefix;?>lock.png" alt="<?php _l('ALT_LOCK');?>" />
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>

      <?php
         if (isset($message) && $f != '') {
            _l ($message);
         }
         else if($f != ''): ?>
      <h1><?php _l('H1_ACTION');?><?php _l($legend); ?></h1>
      <div id="theForm">
        <form method="post" action="<?=$actionForm;?>">
          <fieldset>
            <legend><?php _l($legend); ?></legend>

            <div id="theFields">
              <p class="color1">
                <label class="float" for="name"><strong><?php _l('LABEL_NAME');?> (*)</strong></label>
                <input type="text" name="name" id="name" value="<?=$affUser->getName(); ?>" />
                <?=$affUser->error('name'); ?>
              </p>

              <p class="color2">
                <label class="float" for="password"><strong><?php _l('LABEL_PASSWORD');?> (*)</strong></label>
                <input type="password" name="password" id="password" value="<?=$password1; ?>"/>
                <?=$affUser->error('password'); ?>
              </p>

              <p class="color1">
                <label class="float" for="password2"><strong><?php _l('LABEL_PASSWORD2');?> (*)</strong></label>
                <input type="password" name="password2" id="password2" value="<?=$password2; ?>"/>
                <?=$affUser->error('password2'); ?>
              </p>

              <p class="color2">
                <label class="float" for="uid"><strong><?php _l('LABEL_UID');?> (*)</strong></label>
                <?php
                $unixUsers = getUnixUsers();
                if ($unixUsers == false) {
                  echo '<input type="text" name="uid" id="uid" value="'.$affUser->getUid().'"/>';
                }
                else {
                   echo '<select name="uid" id="uid">';
                   selectOptions($unixUsers, $affUser->getUid());
                   echo '</select>';
                }
                echo $affUser->error('uid');
                ?>
              </p>

              <p class="color1">
                <label class="float" for="gid"><strong><?php _l('LABEL_GID');?> (*)</strong></label>
                <?php
                $unixGroup = getUnixGroups();
                if ($unixGroup == false) {
                  echo '<input type="text" name="gid" id="gid" value="'.$affUser->getGid().'"/>';
                }
                else {
                   echo '<select name="gid" id="gid">';
                   selectOptions($unixGroup, $affUser->getGid());
                   echo '</select>';
                }
                echo $affUser->error('gid');
                ?>
              </p>

              <p class="color2">
                <label class="float" for="dir"><strong><?php _l('LABEL_DIR');?> (*)</strong></label>
                <input type="text" name="dir" id="dir" value="<?=$affUser->getDir(); ?>"/>
                <?=$affUser->error('dir'); ?>
              </p>

              <p class="color1">
                <label class="float" for="dlBandwidth"><?php _l('LABEL_DLBANDWIDTH');?></label>
                <input type="text" name="dlBandwidth" id="dlBandwidth" value="<?=$affUser->getDlBandwidth(); ?>"/>
                <?=$affUser->error('dlBandwidth'); ?>
              </p>

              <p class="color2">
                <label class="float" for="ulBandwidth"><?php _l('LABEL_ULBANDWIDTH');?></label>
                <input type="text" name="ulBandwidth" id="ulBandwidth" value="<?=$affUser->getUlBandwidth(); ?>"/>
                <?=$affUser->error('ulBandwidth'); ?>
              </p>

              <p class="color1">
                <label class="float" for="dlRatio"><?php _l('LABEL_DLRATIO');?></label>
                <input type="text" name="dlRatio" id="dlRatio" value="<?=$affUser->getDlRatio(); ?>"/>
                <?=$affUser->error('dlRatio'); ?>
              </p>

              <p class="color2">
                <label class="float" for="ulRatio"><?php _l('LABEL_ULRATIO');?></label>
                <input type="text" name="ulRatio" id="ulRatio" value="<?=$affUser->getUlRatio(); ?>"/>
                <?=$affUser->error('ulRatio'); ?>
              </p>

              <p class="color1">
                <label class="float" for="quotaFiles"><?php _l('LABEL_QUOTAFILES');?></label>
                <input type="text" name="quotaFiles" id="quotaFiles" value="<?=$affUser->getQuotaFiles(); ?>"/>
                <?=$affUser->error('quotaFiles'); ?>
              </p>

              <p class="color2">
                <label class="float" for="quotaSize"><?php _l('LABEL_QUOTASIZE');?></label>
                <input type="text" name="quotaSize" id="quotaSize" value="<?=$affUser->getQuotaSize(); ?>"/>
                <?=$affUser->error('quotaSize'); ?>
              </p>

              <p class="color1">
                <label class="float" for="ipAddress"><?php _l('LABEL_IPADDRESS');?></label>
                <input type="text" name="ipAddress" id="ipAddress" value="<?=$affUser->getIpAddress(); ?>"/>
              </p>

              <p class="color2">
                <?php
                   $checked='';
                   if ($affUser->getStatus() == 1 || $affUser->getStatus() =='') {
                      $checked = 'checked="checked"';
                   }
                ?>
                <label class="float" for="status"><?php _l('LABEL_STATUS');?></label>
                <input type="checkbox" name="status" id="status" <?=$checked; ?>/>
              </p>
            </div>
            <p>
              <strong>(*)</strong> <?php _l('STAR_MESSAGE');?>
            </p>
            <p>
              <input type="submit" value="<?php _l($buttonValue);?>"/>
              <input type="submit" value="<?php _l('BUTTON_RESET');?>"/>
            </p>
          </fieldset>
        </form>
      </div>
      <?php endif; ?>
    </div>
  </body>
</html>
