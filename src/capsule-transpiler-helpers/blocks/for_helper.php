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
namespace Sammy\Packs\Gogue\Capsule\Block {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Block\_for')){
  /**
   * @function _for
   * Base internal function for the
   * Capsule command '_for'.
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
  function _for ($block, $obj = null, $options = null) {
    $signature = trim($block['signature']);

    $phpInit = Extension\usePHPInitCode ($options);
    $phpEnd = Extension\usePHPEndCode ($options);

    $signature = Extension\rewriteVariableReferences (
      $signature
    );

    if (Extension\isBettwenBrokets ($signature)) {
      $signature = preg_replace ('/^(\s*\(?)/', '',
        preg_replace ('/(\s*(\)\s*)?)$/', '', $signature)
      );
    }

    if (preg_match ('/^((.+)\s+(as|in)\s+(.+))$/i', $signature, $match)) {
      $op = strtolower($match [3]);

      if ($op === 'in') {
        $arrayName = $match[4];

        $id = rand (100, 9999) . date ('His');

        $arrayNameRef = "\$arr{$id}ay";

        $bodyCode = "{$phpInit}{$arrayNameRef} = $arrayName; for ( \$i{$id}terator = 0; \$i{$id}terator < count (is_array ({$arrayNameRef}) ? {$arrayNameRef} : []); \$i{$id}terator++ ) {\n\t" . (
          $match[2] . " = \Saml::Array2Object ({$arrayNameRef}[ \$i{$id}terator ]);\n{$phpEnd}\n"
        );

        return $bodyCode . $block['body'] . "\n{$phpInit}}{$phpEnd}";
      } else {
        $arrayName = $match[2];

        $id = rand (100, 9999) . date ('His');

        $arrayNameRef = "\$arr{$id}ay";
        $bodyCode = "{$phpInit}{$arrayNameRef} = $arrayName; for ( \$i = 0; \$i < count (is_array ({$arrayNameRef}) ? {$arrayNameRef} : []); \$i++ ) {\n\t" . (
          $match[4] . " = \Saml::Object2Array ({$arrayNameRef}[ \$i ]);\n{$phpEnd}\n"
        );

        return $bodyCode . $block['body'] . "\n{$phpInit}}{$phpEnd}";
      }
    }

    return "{$phpInit}for ( {$signature} ) { {$phpEnd}\n {$block['body']} \n{$phpInit}} {$phpEnd}";
  }}
}
