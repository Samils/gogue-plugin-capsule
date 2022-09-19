<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader')){
  /**
   * @trait XMLReader
   * Base internal trait for the
   * Gogue\Transpiler\Capsule module.
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
  trait XMLReader {
    /**
     * [$tagRe description]
     * @var string
     */
    private $tagRe = '/^(<([a-zA-Z_]([a-zA-Z0-9_\.]*))\s*)/';
    /**
     * [$tagNameRe description]
     * @var string
     */
    private $tagNameRe = '/^(<([a-zA-Z0-9_\.]+))/';
    /**
     * [$capsuleStore description]
     * @var array
     */
    private static $capsuleStore = array ();
    /**
     * [$commentsReader description]
     * @var Sammy\Packs\Gogue\Code\CommentsReader
     */
    private static $commentsReader;
  }}
}
