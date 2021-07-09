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
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Decoder\CapsuleDoBlocks')){
  /**
   * @trait CapsuleDoBlocks
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
  trait CapsuleDoBlocks {
    /**
     * [decodeCapsuleDoBlocksMT description]
     * @param  array $match
     * @return string
     */
    function decodeCapsuleDoBlocksMT ($match) {
      # <<capsule-block0>>
      $blockId = ( int )( trim ($match [1]) );

      $blockExists = ( boolean ) (
        isset (self::$DoBlocksStore [ $blockId ]) &&
        is_array ($block = self::$DoBlocksStore [ $blockId ])
      );

      if ( $blockExists ) {
        $transpiler = new static ($block['body'], $this->file);

        return $transpiler->transpileCodeBase ();
      }
    }


    function decodeCapsuleDoBlocks () {
      $re = '/<<\\$capsule-block([0-9]+)>>/i';

      return preg_replace_callback ($re,
        [$this, 'decodeCapsuleDoBlocksMT'], $this->code
      );
    }
  }}
}
