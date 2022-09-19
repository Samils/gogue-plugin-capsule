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
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_sending_list')){
  /**
   * @function parse_argument_sending_list
   * Base internal function for the
   * Capsule command 'parse_argument_sending_list'.
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
   * @param array $args
   * list of sent arguments to the
   * current cli command.
   */
  function parse_argument_sending_list ($argument_list) {
    /**
     * make sure '$argument_list' is
     * not an empty string
     */
    if (empty(trim($argument_list))) {
      return '[]';
    }

    $argList = '[';

    $argument_list_slices = preg_split ('/\s*,\s*/',
      $argument_list
    );

    foreach ($argument_list_slices as $slice) {
      if (preg_match ('/^\$([a-zA-Z0-9_]+)$/', trim($slice))) {
        $s = trim (preg_replace ('/^\$/', '', trim ($slice)));

        $argList .= "'{$s}' => $slice, ";
      } else {
        $argList .= ($slice . ', ');
      }
    }

    $argList = \Sammy\Packs\Gogue\Capsule\Block\Extension\rewriteVariableReferences (
      $argList
    );

    return preg_replace ('/(\s*,\s*)$/', '', $argList) . ']';
  }}
}
