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
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Block\Extension\useBlockEnd')){
  /**
   * @function useBlockEnd
   * Base internal function for the
   * Capsule command 'useBlockEnd'.
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
  function useBlockEnd ($options) {
    $validOptions = ( boolean ) (
      is_array ($options) &&
      isset ($options ['blockEndCode']) &&
      is_string ($options ['blockEndCode'])
    );

    if ( $validOptions ) {
      return $options ['blockEndCode'];
    }

    return '';
  }}
}
