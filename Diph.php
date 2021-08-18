<?php

namespace HexMakina\Diph;

class Diph
{

  public static function diph_int($original, $compare) : bool
  {
    return intval($original) !== intval($compare);
  }

  public static function diph_string($original, $compare) : bool
  {
    return strcasecmp(trim("$original"), trim("$compare")) !== 0;
  }

  public static function diph_array_of_strings($original, $compare) : array
  {
    $diph = array_merge($compare, $original);
    sort($diph);

		foreach($diph as $i => $value)
    {
			foreach($original as $io => $o_value)
      {
				if(self::diph_string($o_value, $value) === false)
        {
          unset($diph[$i]);
          continue;
        }
      }
    }

    return $diph;
  }

  // public static function diph_array_of_object($original, $compare, $interface='HexMakina\qivive\qiviveInterfaco') : array
  public static function diph_array_of_object($original, $compare) : array
  {
    if(empty($compare))
      return $original;

    if(empty($original))
    {
      foreach($compare as $o)
        $o->has_diph(true); // all new objects
      return $compare;
    }

    $ret = [];
    foreach($compare as $i => $c)
    {
      if(is_a($c, $interface) !== true)
        throw new \Exception(__FUNCTION__."::OBJECT DOES NOT IMPLEMENT $interface");

      $comparable_found = false;
      foreach($original as $j => $o)
      {
        if($comparable_found === false && $o->comparable($c))
        {
          $comparable_found = true;
          $ret[$i] = $o->diph($c);
        }
      }
      //nothing found to compare, must be new element, flag it and pack it.
      if($comparable_found === false)
      {
        $c->has_diph(true);
        $ret[$i]= $c;
      }
    }
    return $ret;
  }

  // return assoc with all keys from original and compare AND object cloned and flagged with has_diph() method
  public static function diph_assoc_of_objects($original, $compare) : array
  {
    $ret = [];

    $all_keys = array_unique(array_merge(array_keys($original), array_keys($compare)));

    foreach($all_keys as $key)
    {
      $ret[$key] = null; // OCD setting key

      if(!isset($original[$key])) // new object in compare
      {
        $clone = clone $compare[$key];
        $clone->has_diph(true);
        $ret[$key] = $clone;
      }
      elseif(!isset($compare[$key])) // object only in original
      {
        $ret[$key] = clone $original[$key];
      }
      else // object is present in both array, use class::diph
      {
        $ret[$key] = $original[$key]->diph($compare[$key]);
      }
    }

    return $ret;
  }

  public static function diph_assoc_of_array_of_objects($original, $compare) : array
  {
    $ret = [];
    $all_keys = array_unique(array_merge(array_keys($original),array_keys($compare)));
    foreach($all_keys as $section)
    {
      $ret[$section] = null;

      if(empty($original[$section]))
      {
        $ret[$section] = $compare[$section] ?? [];
      }
      elseif(empty($compare[$section]))
      {
        $ret[$section] = $original[$section];
      }
      else
      {
        $ret[$section] = self::diph_array_of_object($original[$section], $compare[$section], $interface);
      }
    }

    return $ret;
  }

}
