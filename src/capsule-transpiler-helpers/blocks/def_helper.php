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
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Block\_def')){
  /**
   * @function _def
   * Base internal function for the
   * Capsule command '_def'.
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
  function _def ($block, $obj) {
    $signature = trim ($block['signature']);

    $nameRe = '/^([^\s]+)/';
    preg_match ($nameRe, $signature, $match);

    $signature = trim(preg_replace ($nameRe, '', $signature));

    $capsuleName = trim ($match[0]);

    $args = '$args, CapsuleScopeContext $scope';

    $code = \Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_list (
      $signature, $obj
    );

    $codeInit = "<?php Capsule::Def ('{$capsuleName}', function ($args) {\n{$code}?>\n";

    $endCode = !$obj->capsuleAutoExport() ? '' : (
      "\n<?php Capsule::Export ('{$capsuleName}'); ?>"
    );

    return $codeInit . trim($block['body']) . (
      "\n<?php }); ?>$endCode"
    );
  }}
}
