<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules\Attributes')){
  /**
   * @trait Signature
   * Base internal trait for the
   * Gogue\Transpiler\Capsule\XMLReader\Capsules module.
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
   */
  trait Attributes {
    /**
     * [readCapsuleAttributes description]
     * @param  string $signature
     * @return string
     */
    private function readCapsuleAttributes ($signature) {
      $signature = trim (preg_replace ($this->tagNameRe, '',
        preg_replace ('/\s*(\/)?\s*>$/', '', $signature)
      ));

      $signature = $this->encodeBlocksInPartialCode ($signature);

      $list = '[';

      $equalRe = '/\s*=\s*/';

      $signature = preg_replace ($equalRe, '=', $signature);
      $signatureSlices = preg_split ('/\s+/', $signature);

      $attributesMerges = [];

      #echo $signature, "\n\n\n\n\n\n";

      #exit (0);

      foreach ($signatureSlices as $slice) {
        $bindingRe = '/^(%::=block-block([0-9]+)::)$/';

        if (preg_match ($bindingRe, $slice)) {
          $bindingContent = $this->getBindingBlockContent (
            $slice
          );

          $id = date ('hisdm') . '' . rand (0, 9999);
          $randomVarName = "\$ref{$id}e";

          array_push ($attributesMerges, "(is_array ({$randomVarName} = {$bindingContent}) ? {$randomVarName} : [])");

          continue;
        }

        if (preg_match ('/^([^=]+)/', $slice, $match)) {
          $attrName = trim ($match[0]);
          $attrValue = trim(preg_replace ('/^([^=]+)=?/', '', $slice));

          if (empty($attrValue)) {
            $attrValue = '""';
          } else {
            # %::$block-block1::
            $interpolationRe = (
              '/^(%::\\=block-block([0-9]+)::)$/i'
            );

            if (preg_match ($interpolationRe, $attrValue)) {
              $attrValue = $this->getBindingBlockContent (
                $attrValue
              );
            }
          }

          $list .= "'$attrName' => $attrValue, ";
        }
      }

      $finalStrList = $list === '[' ? '' : (
        preg_replace ('/(,\s*)$/', '', $list) . ']'
      );

      if (count ($attributesMerges) >= 1) {

        $finalStrList = empty ($finalStrList) ? '[]' : $finalStrList;

        return "array_merge (".join (', ', $attributesMerges).", {$finalStrList})";
      } else {
        return $finalStrList;
      }
    }

    protected function getBindingBlockContent ($block) {
      $blockBody = trim (
        $this->decodeWholeBlocks ( $block )
      );

      $blockBody = $this->rewriteVariableReferences (
        $blockBody
      );

      return trim (preg_replace ('/^(\s*%\{\s*)/', '',
        preg_replace ('/(\s*\}\s*)$/', '', $blockBody)
      ));
    }
  }}
}
