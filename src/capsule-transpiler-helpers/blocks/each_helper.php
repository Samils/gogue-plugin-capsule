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
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Block\_each')){
  /**
   * @function _each
   * Base internal function for the
   * Capsule command '_each'.
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
  function _each ($block, $obj = null, $options = null) {
    $signature = trim (preg_replace ('/^(\s*\(?)/', '',
      preg_replace ('/(\s*(\)\s*)?)$/', '', trim($block['signature']))
    ));

    $phpInit = Extension\usePHPInitCode ($options);
    $phpEnd = Extension\usePHPEndCode ($options);

    $signature = Extension\rewriteVariableReferences (
      $signature
    );

    if (preg_match ('/^((.+)\s+(as|in)\s+(.+))$/i', $signature, $match)) {
      $op = strtolower($match [3]);

      if ($op === 'in') {
        $arrayName = $match[4];

        $bodyCode = "{$phpInit}for ( \$i = 0; \$i < count (is_array ({$arrayName}) ? {$arrayName} : []); \$i++ ) {\n\t" . (
          $match[2] . " = \Saml::Array2Object ({$arrayName}[ \$i ]);\n{$phpEnd}\n"
        );

        return $bodyCode . $block['body'] . "\n{$phpInit}} {$phpEnd}";
      } else {
        $arrayName = $match[2];
        $bodyCode = "{$phpInit}for ( \$i = 0; \$i < count (is_array ({$arrayName}) ? {$arrayName} : []); \$i++ ) {\n\t" . (
          $match[4] . " = \Saml::Object2Array ({$arrayName}[ \$i ]);\n{$phpEnd}\n"
        );

        return $bodyCode . $block['body'] . "\n{$phpInit}} {$phpEnd}";
      }
    }

    $id = rand (100, 9999) . (
      date ('His')
    );

    $dataRef = "\$ref{$id}erence";
    $ref = "\$ref$id";

    $code = ("{$dataRef} = {$signature};\n\tif (isset ($dataRef) ".
      "&& is_array ($dataRef)) {".
      "\n\tfor (\$i = 0; \$i < count ({$dataRef}); \$i++) {".
      "\n\t\t{$ref} = {$dataRef}[\$i];\n\t\tif (in_array (".
      "strtolower(gettype({$ref})), ['array', 'object']".
      ")) {\n\t\t\t{$ref} = \\Saml::Array2Object({$ref});".
      "\n\t\t\t{$ref}_props = array_keys ((array)".
      "({$ref}));\n\t\t\tif (is_object ($ref) && in_array ('Sammy\Packs\Sami\Base\\".
      "ILeanable', class_implements (get_class ($ref)))) {\n\t\t\t\t".
      "{$ref}_props = array_keys ((array)({$ref}->lean()));\n\t\t\t}\n".
      "\t\t\tforeach ({$ref}_props as \$key) {".
      "\n\t\t\t\tif (is_right_var_name(\$key)) ".
      "{ \$scope->\$key = is_object ($ref) ? {$ref}->\$key : {$ref}[\$key]; }\n\t\t\t}\n\t\t}"
    );

    return "{$phpInit}{$code}\n{$phpEnd}\n {$block['body']} \n{$phpInit}}} {$phpEnd}";
  }}
}
