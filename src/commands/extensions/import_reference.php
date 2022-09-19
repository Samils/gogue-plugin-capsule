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
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\import_reference')){
  /**
   * @function import_from
   * Base internal function for the
   * Capsule command 'import_from'.
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
  function import_reference ($signature, $obj, $openPHP = true) {
    $commaRe = '/\s*,\s*/';
    $prefix = '';
    $finalCode = "";

    if (is_array ($signature)) {
      list ($signature, $prefix) = (array)$signature;
    }

    $signatureSlices = preg_split ($commaRe, $signature);

    foreach ($signatureSlices as $signatureSlice) {
      if (is_group_block (trim ($signatureSlice))) {

        $signatureBody = [
          decode_blocks ($signatureSlice, $obj),
          $prefix
        ];

        $finalCode .= import_reference (
          $signatureBody,
          $obj,
          false
        );

        continue;
      }

      if (is_block (trim ($signatureSlice))) {

        $signatureBody = [
          decode_blocks ($signatureSlice, $obj, 'block'),
          $prefix
        ];

        $finalCode .= import_reference (
          $signatureBody,
          $obj,
          false
        );

        continue;
      }

      list ($referenceName, $reference) = (array) (
        get_reference_datas (join('.', [$prefix, $signatureSlice]))
      );

      $reference = '\\' . preg_replace (
        '/\./', '\\', $reference
      );

      $reference = preg_replace ('/\\\{2,}/', '\\', $reference);

      $finalCode .= join ('', [
        "\nCapsule::Def ('{$referenceName}'",
        ", new {$reference});\n"
      ]);
    }

    return $openPHP ? "\n<?php\n$finalCode\n?>\n" : $finalCode;
  }}
}
