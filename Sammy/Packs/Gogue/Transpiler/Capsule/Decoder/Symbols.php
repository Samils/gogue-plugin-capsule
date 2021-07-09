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
    if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Decoder\Symbols')){
    /**
     * @trait Symbols
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
    trait Symbols {
        /**
         * [decodeCapsuleSymbolsMT]
         * @param  string $match
         * @return string
         */
        function decodeCapsuleSymbolsMT ($match) {
            $prevChar = ($match[1]);
            $nextChar = ($match[3]);
            $symbolContent = trim($match[2]);
            $ic = ':';

            $isCapsuleSymbol = ( boolean ) (
                !in_array ($prevChar, preg_split ('/\s*/', $ic)) || (
                    empty ($prevChar)
                )
            );

            return ( $isCapsuleSymbol ) ? "$prevChar'{$symbolContent}'$nextChar" : (
                $match[0]
            );
        }


        function decodeCapsuleSymbols ($code) {
            $re = '/(.{1}):([a-zA-Z0-9_]+)(.{1})/';

            return preg_replace_callback ( $re,
                [$this, 'decodeCapsuleSymbolsMT'], $code
            );
        }
    }}
}
