<?php
namespace App\Views;
use App\Core\BaseView;

class _MyView extends BaseView {
  public function element($fname,$arg = []){
        return view("/elements/".$fname,$arg);
    }
}
?>