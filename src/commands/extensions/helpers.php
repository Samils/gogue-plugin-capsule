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
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\is_str')){
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
   */
  function is_str ($data = null) {
    return ( boolean ) (
      is_string ($data) &&
      preg_match ('/^(::\$([0-9]+):)$/', $data)
    );
  }}
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\is_group_block')){
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
   */
  function is_group_block ($data = null) {
    # =group-block
    return ( boolean ) (
      is_string ($data) &&
      preg_match ('/^(::=group-block([0-9]+)::)$/', $data)
    );
  }}
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\is_block')){
  /**
   * @function is_block
   * Base internal function for the
   * Capsule command 'is_block'.
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
   */
  function is_block ($data = null) {
    # =group-block
    return ( boolean ) (
      is_string ($data) &&
      preg_match ('/^(::=block-block([0-9]+)::)$/', $data)
    );
  }}

  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\decode_blocks')){
  /**
   * @function decode_blocks
   * Base internal function for the
   * Capsule command 'decode_blocks'.
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
   */
  function decode_blocks ($data = null, $obj = null, $block = 'group') {
    $data = preg_replace ('/\s{2,}/', ' ',
      $obj->decodeWrapperBlocks ($data, $block)
    );

    return strip_wrapper ($data);
  }}
}
