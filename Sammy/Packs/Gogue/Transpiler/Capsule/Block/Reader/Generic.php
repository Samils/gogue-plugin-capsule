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
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Block\Reader\Generic')){
  /**
   * @trait Generic
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
  trait Generic {
    /**
     * [readCapsuleBlocks]
     * @return string
     */
    function readCapsuleBlocks () {
      $idn = !$this->capsuleConfigs['commandIdentifier'] ? '' : (
        '@'
      );

      $capsuleBlocks = $this->getCapsuleBlocks ();

      # Map whole the code chras
      for ( $i = 0; $i < strlen($this->code); $i++ ) {
        # map the '$capsuleBlocks' array
        # to get each of them and try
        # matching with the current
        # slice of the code
        foreach ( $capsuleBlocks as $capsuleBlock ) {
          # Current capsule block name
          $block = ($idn . $capsuleBlock);

          $codeSlice = substr ($this->code, $i,
            strlen ($block)
          );

          if ( $codeSlice === $block ) {

            $nextPoint = $i + strlen($block) + 0;
            $prevChar = trim($this->code[ $i - 1]);

            $blockCommandIsolated = ( boolean )(
              (empty ($prevChar) || !preg_match ('/([a-zA-Z0-9_\-])/', $prevChar)) && (
                empty (trim($this->code[ $nextPoint])) ||
                trim($this->code[ $nextPoint]) === '('
              )
            );

            # Ignore if the $block syntax is not
            # by it self.
            if ( !$blockCommandIsolated ) {
              continue;
            }

            # <<$group1>>:

            $signatureSlice = substr ($this->code,
              $i + strlen ($block), strlen($this->code)
            );

            /**
             * [$blockDatas description]
             * @var array
             */
            $blockDatas = $this->getBlockDatas (
              $i, $block
            );

            #print_r($blockDatas);
            $this->readCapsuleBlock ($blockDatas);

          }
        }
      }

      return $this->code;
    }
  }}
}
