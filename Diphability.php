<?php

namespace HexMakina\Diph;

trait Diphability
{
  private $has_diph = null;

  public function has_diph($valoro=null) : bool
  {
    if($valoro === true || $valoro === false)
      $this->has_diph = $valoro;
      
    return $this->has_diph ?? false;
  }
}
