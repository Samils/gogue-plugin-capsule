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
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\import_from')){
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
   * @param array $args
   * list of sent arguments to the
   * current cli command.
   */
  function import_from ($from, $signature, $obj, $openPHP = true) {
    $signature = strip_wrapper ($signature);

    if (empty ($from) || !$from) {
      return import_reference (
        $signature,
        $obj,
        $openPHP
      );
    }

    if (is_str ($from)) {
      $commaRe = '/\s*,\s*/';
      $finalCode = "";

      $signatureSlices = preg_split ( $commaRe, $signature);

      foreach ( $signatureSlices as $slice ) {


        if (is_group_block (trim ($slice))) {

          $finalCode .= import_from (
            $from,
            decode_blocks ($slice, $obj),
            $obj,
            false
          );

          continue;
        }

        if (is_block (trim ($slice))) {

          $finalCode .= import_from (
            $from,
            decode_blocks ($slice, $obj, 'block'),
            $obj,
            false
          );

          continue;
        }



        $as = trim ($slice);
        $importFrom = $from;
        $asCommandRe = '/(\s+as\s+(.+))$/i';
        $capsuleRef = null;

        if (preg_match ($asCommandRe, $slice, $asMatch)) {
          $slice = trim(preg_replace ($asCommandRe, '', $slice));
          $as = trim ($asMatch[2]);
        }

        # if (preg_match ($bblockRe, $slice, $bblockMatch))

        if ( is_null ( $capsuleRef )) {
          /**
           * [$capsuleRef]
           * @var string
           */
          $capsuleRef = $slice === $as ? "'$slice'" : (
            /**
             * Capsule Import Reference
             */
            "['$slice' => '$as']"
          );
        }

        #sexit ("as => $as\n\n Sig => $slice\n\n");


        if (is_null ($importFrom)) {
          $importFrom = "'./{$slice}'";
        }

        $finalCode .= "\tCapsule::Import ($capsuleRef, Capsule::RelativePathDecode (path ($importFrom)));\n";
      }

      return $openPHP ? "\n<?php\n$finalCode\n?>\n" : $finalCode;
    }

    return import_reference (
      [$signature, $from],
      $obj,
      $openPHP
    );
  }}
}
