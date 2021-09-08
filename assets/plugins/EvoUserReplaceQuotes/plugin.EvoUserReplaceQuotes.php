<?php
include_once MODX_BASE_PATH.'assets/lib/MODxAPI/modUsers.php';
$modUsers = new modUsers($modx);
$e = &$modx->event;

if ($e->name == 'OnWUsrFormSave') {
  $modUsers->edit((int)$id);

  $result = $modx->db->query('SELECT * FROM '.$modx->getFullTableName('web_user_attributes').' WHERE internalKey = '.(int)$id);
  while($row = $modx->db->getRow($result)) {
    if(!empty($row)) {
      foreach($row as $item_key => $item) {
        $item = str_replace('\'','"',$item); // замена одинарных кавычек на двойные
        $item = str_replace('"', '»', preg_replace('/((^|\s)"(\w))/um', '\2«\3', $item)); // замена двойных кавычек на «ёлочки»
        $modUsers->set($item_key,$item);
      }
    }
  }
  $modUsers->save(false,false);
  $modUsers->close();
}

unset($modUsers);