<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Capsule\Extensions
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Capsule\Extensions {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\strip_wrapper')){
  /**
   * @function strip_wrapper
   * Base internal function for the
   * Capsule command 'strip_wrapper'.
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
   * -
   */
  function strip_wrapper ($signature) {
    $signature = preg_replace (
      '/^((\(|\{)+\s*)/', // pattern
      '', // replacement
      preg_replace ( // subject
        '/(\s*(\)|\})+)$/', // pattern
        '', // replacement
        $signature // subject
      )
    );

    return trim ($signature);
  }}
}
