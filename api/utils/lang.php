<?php

function _gl ($key) {
   global $lang;
   return $lang[$key];
}

function _l($key) {
   global $lang;
   echo $lang[$key];
}
?>