<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules
 * - Autoload, application dependencies
 *
 * MIT License
 *
 * Copyright (c) 2020 Ysare
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope before creating
   * it.
   */
  if (!trait_exists ('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Capsules\Base')) {
  /**
   * @trait Base
   * Base internal trait for the
   * Gogue\Transpiler\Capsule\XMLReader\Capsules module.
   * -
   * This is (in the ils environment)
   * an instance of the php module,
   * which should contain the module
   * core functionalities that should
   * be extended.
   * -
   * For extending the module, just create
   * an 'exts' directory in the module directory
   * and boot it by using the ils directory boot.
   * -
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

      array_push (self::$capsuleStore, $capsuleDatas);
    }

    private function readContainerCapsule ($capsuleDatas) {
      $id = count (self::$capsuleStore);

      if (!is_array ($capsuleDatas)) {
        return;
      }

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

      $capsuleDatas ['attributes'] = $this->readCapsuleAttributes ($capsuleSignatureDatas ['signature']);

      array_push (self::$capsuleStore, $capsuleDatas);
    }
  }}
}
