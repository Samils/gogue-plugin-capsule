<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Capsule\Block\Extension
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Capsule\Block\Extension {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope before creating
   * it.
   */
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Block\Extension\rewriteVariableReferences')){
  /**
   * @function rewriteVariableReferences
   * Base internal function for the
   * Capsule command 'rewriteVariableReferences'.
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
   * -
   */
  function rewriteVariableReferences ($code) {
    $re = '/\$[a-zA-Z_]([a-zA-Z0-9_]*)(->)?([a-zA-Z0-9_]*)/';

    $matchCallback = function ( $match ) {
      $varName = preg_replace ('/^(\$)/', '', $match[0]);

      # Prevent from rewriting '$this' variable
      # when getting the variable name.
      if (preg_match ('/^((scope|this)(->(.+)|))$/i', $varName)) {
        return $match [ 0 ];
      }

      return '$scope->' . $varName;
    };

    return preg_replace_callback ($re, $matchCallback, $code);
  }}
}
