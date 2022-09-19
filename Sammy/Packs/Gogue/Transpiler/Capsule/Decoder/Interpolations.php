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
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Decoder\Interpolations')){
  /**
   * @trait Interpolations
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
  trait Interpolations {
    function decodeCapsuleIterpolationsMT ($match) {
      $id = (int)($match [ 1 ]);

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

      $blockBody = $this->rewriteVariableReferences (
        $blockBody
      );

      $blockBody = $this->decodeWholeBlocks ($blockBody);

      return "<?php function(\$args, CapsuleScopeContext \$scope){return {$blockBody};} ?>";
    }

    function decodeCapsuleIterpolations ($code) {
      $code = $this->decodeCapsuleBindingIterpolations ($code);

      $re = '/\$::\=block-block([0-9]+)::/i';

      return preg_replace_callback ( $re,
        [$this, 'decodeCapsuleIterpolationsMT'], $code
      );
    }
  }}
}
