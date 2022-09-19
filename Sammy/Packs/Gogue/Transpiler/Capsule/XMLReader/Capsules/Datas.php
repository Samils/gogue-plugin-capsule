<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Capsule\XMLReader\Capsules
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules\Datas')){
  /**
   * @trait Datas
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
  trait Datas {

    private function capsuleNameReMathCallBack ($match) {
      return '\\' . $match[0];
    }

    private function getCapsuleDatas ($point, $capsuleName) {

      $capsuleNameReScappingChars = '/[\.]/';
      $capsuleNameRe = preg_replace_callback (
        $capsuleNameReScappingChars,
        [$this, 'capsuleNameReMathCallBack'],
        $capsuleName
      );

      $capsuleNameRe = "/^(<\/{$capsuleNameRe}\s*>)/";

      for ($i = ($point + 1); $i < strlen($this->code); $i++) {
        $codeSlice = substr ($this->code, $i, strlen($this->code));

        # Loop is startig here

        if (preg_match ($this->tagRe, $codeSlice, $match)) {
          $capsuleName = trim ($match[2]);

          $capsuleSignatureDatas = $this->getCapsuleSignature ($i);


          #echo "\n\n\n\n\n\n\n SIGNATURE \n\n\n\n\n\n\n";

          #print_r($capsuleSignatureDatas);

          #echo "\n\n\n\n\n\n\n\n";

          #exit (0);

          if (is_array ($capsuleSignatureDatas)) {

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

              #print_r($capsuleSignatureDatas);

              #exit (0);
              /**
               * [$capsuleDatas description]
               * @var [type]
               */
              $capsuleDatas = $this->getCapsuleDatas (
                $i, $capsuleName
              );

              #echo "\n\n\n\n\n\n\n";
              #print_r($capsuleDatas);
              #echo "\n\n\n\n\n\n\n";

              $this->readContainerCapsule ($capsuleDatas);
            }
          }
        }

        # end

        if (preg_match ($capsuleNameRe, $codeSlice, $match)) {
          $closementBody = $match [0];

          $capsuleEndPoint = ($i - $point) + strlen($closementBody);

          $capsuleBody = substr ($this->code, $point, $capsuleEndPoint);

          #echo "\n\n\n\n\n\n\n$capsuleBody\n\n\n\n\n\n\n\n\n";

          return array (
            'body' => $capsuleBody,
            'end' => $capsuleEndPoint,
            'init' => $point,
            'name' => $capsuleNameRe
          );
        }

      }

      #echo $capsuleNameRe, "\n";
      # return
    }

  }}
}
