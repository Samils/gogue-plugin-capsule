<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\Block\Reader
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\Block\Reader {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Block\Reader\Specific')){
  /**
   * @trait Specific
   * Base internal trait for the
   * Gogue\Transpiler\Capsule\Block\Reader module.
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
  trait Specific {
    /**
     * [readCapsuleBlock description]
     * @param  string $block
     * @return string
     */
    protected function readCapsuleBlock ( $block ) {
      #echo "\n\n\033[31mBlock => \033[m \n\n";
      #print_r($blockDatas);
      #echo "\n\n\n\n\n";

      $replacement = call_user_func_array (
        $block ['funcName'], [$block, $this]
      );

      $this->code = substr_replace (
        $this->code,
        $replacement,
        $block['init'],
        $block['end']
      );
    }
  }}
}
