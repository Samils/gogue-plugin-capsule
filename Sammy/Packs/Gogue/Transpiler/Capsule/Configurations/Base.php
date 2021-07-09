<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\Configurations
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\Configurations {
    /**
     * Make sure the module base internal class is not
     * declared in the php global scope defore creating
     * it.
     */
    if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Configurations\Base')){
    /**
     * @trait Base
     * Base internal trait for the
     * Gogue\Transpiler\Capsule\Configurations module.
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
    trait Base {
        /**
         * [$capsuleConfigs description]
         * @var array
         */
        private $capsuleConfigs = array (
            'commandIdentifier' => true,
            'capsuleAutoExport' => true,
            'capsuleImportInterop' => false
        );

        function capsuleAutoExport () {
            $ae = 'capsuleAutoExport';

            return ( boolean ) (
                isset ($this->capsuleConfigs[$ae]) &&
                is_bool ($this->capsuleConfigs[$ae]) && (
                    $this->capsuleConfigs[$ae]
                )
            );
        }

        function commandIdentifier () {
            return ( boolean ) (
                isset ($this->capsuleConfigs['commandIdentifier']) &&
                is_bool ($this->capsuleConfigs['commandIdentifier']) && (
                    $this->capsuleConfigs['commandIdentifier']
                )
            );
        }

        /**
         * [GogueConfigInit]
         * @param array $config
         */
        function GogueConfigInit ($config) {
            /**
             * Make sure '$config' is an array
             * to merge it with the capsule
             * transpiler configurations prop.
             */
            if (is_array ($config)) {
                /**
                 * [$this->capsuleConfigs description]
                 * @var array
                 */
                $this->capsuleConfigs = array_merge (
                    $this->capsuleConfigs, $config
                );
            }
        }
    }}
}
