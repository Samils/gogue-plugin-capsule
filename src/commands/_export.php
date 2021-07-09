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
namespace Sammy\Packs\Gogue\Capsule\Command {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Command\export')){
  /**
   * @function export
   * Base internal function for the
   * Capsule command 'export'.
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
   * @param array $args
   * list of sent arguments to the
   * current cli command.
   */
  function export ($signature, $obj) {
    $signature = preg_replace ('/^(@?export\s*)/i', '',
      trim ($signature)
    );

    $defaultKeyRe = '/^default\s*/i';

    if (preg_match ( $defaultKeyRe, $signature )) {
      $signature = trim (preg_replace ($defaultKeyRe, '', $signature));

      $blockRe = '/^(<<\\$capsule-block([0-9]+)>>)$/i';

      if (preg_match ($blockRe, $signature)) {

        $blockDatas = $obj->readCapsuleDoBlock ($signature);
        $args = '$args, CapsuleScopeContext $scope';

        $args_list = $obj->decodeBlocks ($blockDatas['arguments']);
        $code = \Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_list (
          $args_list, $obj
        );

        return "<?php \$module->exports = Capsule::Create (function ($args) {\n{$code}?>\n" . (
          $signature . "\n<?php }); ?>\n\n"
        );
      } else {
        $blockRe2 = '/(\s*<<\\$capsule-block([0-9]+)>>\s*)$/i';

        if (preg_match ($blockRe2, $signature, $match)) {

          $block = trim($match [0]);
          $signature = preg_replace($blockRe2, '', $signature);

          $blockDatas = $obj->readCapsuleDoBlock ($block);
          $args = '$args, CapsuleScopeContext $scope';

          $args_list = $obj->decodeBlocks ($blockDatas['arguments']);
          $code = \Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_list (
            $args_list, $obj
          );

          return "<?php \$module->exports = Capsule::Create ('{$signature}', function ($args) {\n{$code}?>\n" . (
            $block . "\n<?php }); ?>\n\n"
          );

        } else {
          return "<?php \$module->exports = Capsule::Element ('{$signature}'); ?>\n\n";
        }
      }
    } else {
      $blockRe2 = '/(\s*<<\\$capsule-block([0-9]+)>>\s*)$/i';

      if (preg_match ($blockRe2, $signature, $match)) {

        $block = trim($match [0]);
        $signature = preg_replace($blockRe2, '', $signature);

        $blockDatas = $obj->readCapsuleDoBlock ($block);

        $args = '$args, CapsuleScopeContext $scope';

        $args_list = $obj->decodeBlocks ($blockDatas['arguments']);
        $code = \Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_list (
          $args_list, $obj
        );

        return "<?php Capsule::Export ('{$signature}', function ($args) {\n{$code}?>\n" . (
          $block . "\n<?php }); ?>\n\n"
        );

      } else {
        return "<?php Capsule::Export ('{$signature}'); ?>\n\n";
      }
    }
  }}
}
