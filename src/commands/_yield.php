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
    if (!function_exists('Sammy\Packs\Sami\Cli\_yield')){
    /**
     * @function _yield
     * Base internal function for the
     * Capsule command 'yield'.
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
    function _yield ($signature, $obj) {
        $signature = preg_replace ('/^(@?_?yield\s*)/i', '',
            trim ($signature)
        );

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

            $renderArgList = \Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_sending_list (
                trim ( $signature )
            );

            $renderArgList = \Sammy\Packs\Gogue\Capsule\Block\rewriteVariableReferences (
              $renderArgList
            );

            return "<?php Capsule::Yield ($renderArgList, Capsule::Create (function ($args) {\n{$code}?>\n" . (
                $block . "\n<?php })); ?>\n\n"
            );

        } else {
            $renderArgList = \Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_sending_list (
                trim ( $signature )
            );

            $renderArgList = \Sammy\Packs\Gogue\Capsule\Block\rewriteVariableReferences (
              $renderArgList
            );

            return "<?php Capsule::Yield ($renderArgList); ?>\n";
        }
    }}
}
