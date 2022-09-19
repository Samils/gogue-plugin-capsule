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
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules\Base')){
  /**
   * @trait Base
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
  trait Base {
    /**
     * [readAutoClosedCapsule description]
     * @param  string $signature
     * @param  integer $initPoint
     * @param  integer $endPoint
     * @return null|string
     */
    private function readAutoClosedCapsule ($signature, $initPoint, $endPoint) {
      $id = count (self::$capsuleStore);
      $this->code = substr_replace ($this->code, "\n%::capsule-code{$id}::\n",
        $initPoint, $endPoint
      );

      $capsuleName = null;

      # [body] =>
      # [end]? =>
      # [init?] =>
      # [name] => Header
      # [attributes] =>

      if (preg_match ($this->tagNameRe, $signature, $match)) {
        $capsuleName = $match [2];
      }

      $capsuleDatas = [
        $signature,
        'body' => '',
        'name' => $capsuleName
      ];

      $capsuleDatas['attributes'] = $this->readCapsuleAttributes (
        $signature
      );

      #echo $capsuleDatas['attributes'], "\n\n";

      array_push (self::$capsuleStore, $capsuleDatas);
    }

    private function readContainerCapsule ($capsuleDatas) {
      $id = count (self::$capsuleStore);

      $codeSlice = substr (
        $this->code,
        $capsuleDatas['init'],
        $capsuleDatas['end']
      );

      $this->code = str_replace (
        $capsuleDatas['body'],
        "\n%::capsule-code{$id}::\n",
        $this->code
      );

      #$this->code = substr_replace ($this->code, "\n%::capsule-code{$id}::\n",
      #    $capsuleDatas['init'], $capsuleDatas['end']
      #);


      #exit("FIM \n\n\n\n\n\n". $this->code);

      $capsuleBody = $capsuleDatas ['body'];
      $capsuleSignatureDatas = $this->getCapsuleSignatureInPartialCode (
        0, $capsuleBody
      );

      $capsuleBody = substr_replace ($capsuleBody, '', 0,
        $capsuleSignatureDatas ['signatureLen']
      );

      $capsuleBody = preg_replace ('/(<\/(.+)>)$/', '', $capsuleBody);

      $capsuleDatas ['body'] = $capsuleBody;

      $regex = $this->tagNameRe;

      if (preg_match ($regex, $capsuleSignatureDatas['signature'], $match)) {
        $capsuleDatas ['name'] = trim ($match[2]);
      }

      $capsuleDatas ['attributes'] = $this->readCapsuleAttributes (
        $capsuleSignatureDatas ['signature']
      );


      #echo "\n\n\n\n\n\n\n\n\n\033[31m$capsuleBody\033[m\n\n\n\n\n\n\n\n\n";
      #print_r($capsuleSignatureDatas);
      #print_r($capsuleDatas);

      #echo "\n\n\n\nMANS\n\n\n\n";

      #print_r($capsuleDatas);
      #echo "\n\n\n\nMANS\n\n\n\n";
      array_push (self::$capsuleStore, $capsuleDatas);
    }
  }}
}
