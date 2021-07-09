<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Capsule\Block\Extension
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Capsule\Block\Extension {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope before creating
   * it.
   */
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Block\Extension\isBettwenBrokets')){
  /**
   * @function isBettwenBrokets
   * Base internal function for the
   * Capsule command 'isBettwenBrokets'.
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
   * -
   */
  function isBettwenBrokets ($str) {
    if (!(is_string($str) && $str))
      return;

    $str = trim ($str);

    if (!(preg_match ('/^\(/', $str) && preg_match ('/\)$/', $str))) {
      return false;
    }

    $ends = 1;

    for ($i = 1; $i < strlen($str); $i++) {
      $strSlice = substr ($str, $i, 1);

      if ($strSlice === '(') {
        $end_ = 1;
        for ($p = ($i + 1); $p < strlen($str); $p++) {
          $strSlice_ = substr ($str, $p, 1);

          if ($strSlice_ === '(') {
            $end_++;
          }

          if ($strSlice_ === ')') {
            if ($end_ === 1) {
              $endPoint = ($p - $i + 1);
              $body = substr($str, $i, $endPoint);
              $str = substr_replace($str, '', $i, $endPoint);
              break;
            } else {
              $end_--;
            }
          }
        }
      }
    }

    return ( boolean ) (
      preg_match ('/^\(/', trim ($str)) &&
      preg_match ('/\)$/', trim ($str))
    );
  }}
}
