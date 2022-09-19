<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules {
    /**
     * Make sure the module base internal class is not
     * declared in the php global scope defore creating
     * it.
     */
    if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules\Signature')){
    /**
     * @trait Signature
     * Base internal trait for the
     * Gogue\Transpiler\Capsule\XMLReader\Capsules module.
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
    trait Signature {

        private function getCapsuleSignature ($point) {

            for ($i = ($point + 1); $i < strlen ($this->code); $i++) {
                $codeSlice = substr ($this->code, $i, 1);

                if ($codeSlice === '>') {
                    $endPoint = (($i - $point) + 1);

                    $signature = substr($this->code, $point, $endPoint);

                    return ['signature' => trim($signature),
                        'signatureEndPoint' => ($i + 1),
                        'signatureLen' => $endPoint
                    ];
                }
            }
        }

        private function getCapsuleSignatureInPartialCode ($point, $code) {

            for ($i = $point; $i < strlen ($code); $i++) {
                $codeSlice = substr ($code, $i, 1);

                if ($codeSlice === '>') {
                    $endPoint = (($i - $point) + 1);

                    $signature = substr($code, $point, $endPoint);

                    return ['signature' => trim($signature),
                        'signatureEndPoint' => ($i + 1),
                        'signatureLen' => $endPoint
                    ];
                }
            }
        }

    }}
}
