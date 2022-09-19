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
    if (!function_exists('Sammy\Packs\Sami\Cli\set')){
    /**
     * @function set
     * Base internal function for the
     * Capsule command 'set'.
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
    function set ($signature, $obj) {
        $signature = preg_replace ('/^(@?set\s*)/i', '',
            trim ($signature)
        );

        $blockRe2 = '/(\s*<<\\$capsule-block([0-9]+)>>\s*)$/i';

        if (preg_match ($blockRe2, $signature, $match)) {
        } else {
        }
    }}
}
