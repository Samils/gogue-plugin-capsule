<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Capsule\Command
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Capsule\Command {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Sami\Cli\import')){
  /**
   * @function import
   * Base internal function for the
   * Capsule command 'import'.
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
  function import ($signature, $obj) {
    $signature = preg_replace (
      '/^(@?import\s*)/i', '',
      trim ($signature)
    );

    #exit ($signature);

    $signature = preg_replace (
      '/\s{2,}/', ' ',
      $obj->decodeWrapperBlocks (
        $signature
      )
    );

    $from = null;

    $fromCommandRe = '/(\s*from\s+(.+))$/i';

    if (preg_match ($fromCommandRe, $signature, $match)) {
      $signature = trim(preg_replace ($fromCommandRe, '', $signature));
      $from = trim ($match[2]);
    }

    return \Sammy\Packs\Gogue\Capsule\Extensions\import_from (
      $from, $signature, $obj
    );
  }}
}
