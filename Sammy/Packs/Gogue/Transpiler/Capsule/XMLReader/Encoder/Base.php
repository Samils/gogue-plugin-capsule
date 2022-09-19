<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Encoder
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Encoder {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Encoder\Base')){
  /**
   * @trait Base
   * Base internal trait for the
   * Gogue\Transpiler\Capsule\XMLReader\Encoder module.
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
     * [encodeCapsuleXMLElements description]
     * @return string
     */
    function encodeCapsuleXMLElements () {
      /**
       * [$i description]
       * @var integer
       */
      for ($i = 0; $i < strlen($this->code); $i++) {
        $codeSlice = substr ($this->code, $i, strlen($this->code));

        if (preg_match ($this->tagRe, trim ($codeSlice), $match)) {
          $capsuleName = trim ($match[2]);

          #print_r($match); echo "\n\n\n\n\n";

          #echo "\n$capsuleName\n";

          $capsuleSignatureDatas = $this->getCapsuleSignature (
            $i, $this->code
          );
          #print_r($capsuleSignatureDatas);
          #exit (0);

          if (is_array ($capsuleSignatureDatas)) {

            #print_r($capsuleSignatureDatas);

            #exit (0);
            #

            $signature = $capsuleSignatureDatas [
              'signature'
            ];

            $autoClosedCapsuleRe = '/(\s*\/\s*>)$/';
            $signatureLen = $capsuleSignatureDatas [
              'signatureLen'
            ];

            $signatureEndPoint = $capsuleSignatureDatas [
              'signatureEndPoint'
            ];

            if (preg_match ($autoClosedCapsuleRe, $signature)) {
              /**
               * [readAutoClosedCapsule]
               */
              $this->readAutoClosedCapsule (
                $signature, $i, $signatureLen
              );
            } else {
              /**
               * [$capsuleDatas description]
               * @var [type]
               */
              $capsuleDatas = $this->getCapsuleDatas (
                $i, $capsuleName
              );

              #echo "OUTPUT- {$capsuleName} \n\n";

              #print_r ($capsuleDatas);

              #exit (0);

              #echo "\n\n\n\nCapsule datas => \n\n\n\n";

              #print_r($capsuleDatas);

              #exit (0);

              $this->readContainerCapsule ($capsuleDatas);
            }
          }
        }
      }

      return $this->code;
    }
  }}
}
