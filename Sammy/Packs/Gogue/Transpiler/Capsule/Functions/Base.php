<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\Functions
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\Functions {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Functions\Base')){
  /**
   * @trait Base
   * Base internal trait for the
   * Gogue\Capsule\Functions module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * wich should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
   * \Samils\dir_boot ('./exts');
   */
  trait Base {
    function getCapsuleBlocks () {
      $re = '/^(Sammy\\\Packs\\\Gogue\\\Capsule\\\Block\\\_?)/i';
      $funcs = get_defined_functions ();
      $blocks = array();

      foreach ($funcs['user'] as $func) {
        if (!preg_match ($re, $func)) {
          continue;
        }
        array_push ($blocks, preg_replace ($re, '', $func));
      }

      return $blocks;
    }

    function getCapsuleCommands () {
      $re = '/^(Sammy\\\Packs\\\Gogue\\\Capsule\\\Command\\\_?)/i';
      $funcs = get_defined_functions ();
      $blocks = array();

      $idn = !$this->capsuleConfigs['commandIdentifier'] ? '' : (
        '@'
      );

      foreach ($funcs['user'] as $func) {
        if (!preg_match ($re, $func))
          continue;
        $key = $idn . preg_replace ($re, '', $func);

        $blocks[ $key ] = $func;
      }

      return $blocks;
    }

    function getCapsuleIfCommands () {
      $re = '/^(Sammy\\\Packs\\\Gogue\\\Capsule\\\Command\\\Ifc\\\_?)/i';
      $funcs = get_defined_functions ();
      $blocks = array();

      $idn = !$this->capsuleConfigs['commandIdentifier'] ? '' : (
        '@'
      );

      foreach ($funcs['user'] as $func) {
        if (!preg_match ($re, $func))
          continue;
        $key = $idn . preg_replace ($re, '', $func);

        $blocks[ $key ] = $func;
      }

      return $blocks;
    }
  }}
}
