<?php

namespace HexMakina\Diph;

interface Diffable
{
  public function diff_interface();
  public function diff($compare, $interface=null);
  public function comparable($compare) : bool;
  public function has_diff($setter=null) : bool;
}


