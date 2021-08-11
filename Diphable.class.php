<?php

namespace HexMakina\Diph;

interface Diphable
{
  public function diph_interface();
  public function diph($compare, $interface=null);
  public function comparable($compare) : bool;
  public function has_diph($setter=null) : bool;
}
