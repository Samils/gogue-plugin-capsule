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
  if (!function_exists('Sammy\Packs\Sami\Cli\from')){
  /**
   * @function from
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
  function from ($signature, $obj) {
    $signature = preg_replace ('/^(@?from\s*)/i', '',
      trim ($signature)
    );

    $signature = preg_replace ('/\s{2,}/', ' ',
      $obj->decodeWrapperBlocks ($signature)
    );

    $import = null;

    $importCommandRe = '/(\s*import\s+(.+))$/i';

    $fromStrTest = preg_match ('/^[^\s]+/', $signature, $fromMatch);

    $from = trim ($fromMatch [0]);

    $signature = preg_replace (
      '/^(.+)import\s*/', '',
      $signature
    );

    #if (preg_match ($importCommandRe, $signature, $match)) {
    #  $signature = trim(preg_replace ($importCommandRe, '', $signature));
    #  $import = trim ($match[2]);

    #  echo "From => $signature\n\n\n";
    #}

    #exit ('foederrm => ' . $signature . "\n\n");



    return \Sammy\Packs\Gogue\Capsule\Extensions\import_from (
      $from, $signature, $obj
    );
  }}
}
