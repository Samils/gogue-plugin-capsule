<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\Decoder
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\Decoder {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Decoder\BindingIterpolations')){
  /**
   * @trait BindingIterpolations
   * Base internal trait for the
   * Gogue\Capsule\Decoder module.
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
  trait BindingIterpolations {
    protected $bindingInterpolationRe = (
      '/%::\=block-block([0-9]+)::/i'
    );
    /**
     * [decodeCapsuleBindingIterpolationsMT]
     * @param  array  $match
     * @return string
     */
    function decodeCapsuleBindingIterpolationsMT ($match = [], $options = []) {
      $id = (int)($match [ 1 ]);

      $phpEnd = \Sammy\Packs\Gogue\Capsule\Block\Extension\usePHPEndCode ($options);
      $phpInit = \Sammy\Packs\Gogue\Capsule\Block\Extension\usePHPInitCode ($options, true);

      if (!isset(self::$blockStore [$id])) {
        return $match[0];
      }

      $blockDatas = self::$blockStore [$id];

      $blockBody = trim (
        $this->decodeBlocks ( $blockDatas [0] )
      );

      $blockBody = trim (preg_replace ('/^(\s*\{\s*)/', '',
        preg_replace ('/(\s*\}\s*)$/', '', $blockBody)
      ));

      $blockBody = $this->rewriteVariableReferences ( $blockBody );

      $varRe = '/^(\$?([a-zA-Z_])([a-zA-Z0-9_]+))$/';

      if (preg_match ($varRe, $blockBody)) {
        $varBind = "{$phpInit}!isset ({$blockBody}) ? null : str ({$blockBody}){$phpEnd}";
        /**
         * Verify the data sourec to bind
         */
        return preg_match ('/^\$/', $blockBody) ? $varBind : (
          "{$phpInit}!defined ('{$blockBody}') ? null : str ({$blockBody}){$phpEnd}"
        );
      } else {
        $blockBody = $this->decodeWholeBlocks ( $blockBody );
        return "{$phpInit}{$blockBody}{$phpEnd}";
      }
    }

    function decodeCapsuleBindingIterpolations ($code) {
      #$re = '/%::\=block-block([0-9]+)::/i';

      return preg_replace_callback ( $this->bindingInterpolationRe,
        [$this, 'decodeCapsuleBindingIterpolationsMT'], $code
      );
    }
  }}
}
