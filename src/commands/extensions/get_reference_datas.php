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
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\get_reference_datas')){
  /**
   * @function get_reference_datas
   * Base internal function for the
   * Capsule command 'get_reference_datas'.
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
  function get_reference_datas ($reference) {
    $asCommandRe = '/(\s+as\s+(.+))$/i';

    if (preg_match ($asCommandRe, $reference, $match)) {
      $referenceName = trim (@$match [ 2 ]);
      $reference = preg_replace (
        $asCommandRe, '',
        $reference
      );
    } else {
      $refs = preg_split ('/\./', $reference);
      $referenceName = $refs [ -1 + count ($refs) ];
    }

    return [ $referenceName, $reference ];
  }}
}
