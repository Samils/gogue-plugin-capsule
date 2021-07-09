<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\Block
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\Block {
    /**
     * Make sure the module base internal class is not
     * declared in the php global scope defore creating
     * it.
     */
    if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Block\Factory')){
    /**
     * @trait Factory
     * Base internal trait for the
     * Gogue\Transpiler\Capsule\Block module.
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
    trait Factory {
        /**
         * [blockFactory description]
         * @param  [type] $point
         * @param  [type] $endPoint
         * @param  [type] $currentBlock
         * @param  [type] $blockContent
         * @return [type]
         */
        function blockFactory ($point, $endPoint, $currentBlock, $blockContent) {
            $blockNameRe = '/^(\\'.$currentBlock.')/i';
            $blockSignatureRe = '/^([^\n]+)/';

            $blockBody = trim(preg_replace ($blockNameRe, '',
                preg_replace ('/(\s*(@|)end\s*)$/i', '', $blockContent)
            ));

            $blockSignatureMatch = preg_match ($blockSignatureRe, $blockBody,
                $blockSignature
            );

            $blockBody = preg_replace($blockSignatureRe, '', $blockBody);
            $name = preg_replace ('/^@/', '', $currentBlock);
            $ns = 'Sammy\\Packs\\Gogue\\Capsule\\Block\\';

            if (function_exists( $ns . $name )) {
                $funcName = $ns . $name;
            } else {
                $funcName = "{$ns}_{$name}";
            }

            # Block Datas
            return array (
                'signature' => $this->decodeBlocks (
                    $blockSignature[ 0 ]
                ),
                'name' => $name,
                'funcName' => $funcName,
                'body' => /*$this->decodeBlocks*/ ($blockBody),
                'init' => $point,
                'end' => $endPoint
            );
        }
    }}
}
