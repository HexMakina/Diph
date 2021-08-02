<?php

namespace HexMakina\Diph;

trait Diffability
{
  private $has_diff = null;

  public function has_diff($valoro=null) : bool
  {
    if($valoro === true || $valoro === false)
      $this->has_diff = $valoro;
      
    return $this->has_diff ?? false;
  }  
}
