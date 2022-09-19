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
namespace Sammy\Packs\Gogue\Capsule\Block {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Block\_if')){
  /**
   * @function _if
   * Base internal function for the
   * Capsule command '_if'.
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
  function _if ($blockDatas, $obj = null, $options = []) {
    $capsuleBlocks = $obj->getCapsuleIfCommands ();
    $capsuleBlocksArr = array_keys ($capsuleBlocks);

    $phpInit = Extension\usePHPInitCode ($options);
    $phpEnd = Extension\usePHPEndCode ($options);

    $blockInit = Extension\useBlockInit ($options);
    $blockEnd = Extension\useBlockEnd ($options);

    $blockCode = $blockDatas [ 'body' ];
    $blockSignature = trim($blockDatas ['signature']);

    $blockSignature = Extension\rewriteVariableReferences (
      $blockSignature
    );

    #echo "\n\n\n\n CODE: \n\n\n\n", $blockCode,"\n\n\n\n";

    # Map whole the code chras
    for ( $i = 0; $i < strlen ($blockCode); $i++ ) {
      # map the '$capsuleBlocks' array
      # to get each of them and try
      # matching with the current
      # slice of the code
      foreach ( $capsuleBlocksArr as $capsuleBlock ) {
              # Current capsule block name
        $block = ($capsuleBlock);

        $codeSlice = substr ($blockCode, $i,
          strlen ($block)
        );

        if ( strtolower($codeSlice) === $block ) {
          $nextPoint = $i + strlen($block) + 0;
          $prevChar = trim($blockCode[ $i - 1]);

          $blockCommandIsolated = ( boolean )(
            (empty ($prevChar) || !preg_match ('/([a-zA-Z0-9_\-])/', $prevChar)) && (
              empty (trim($blockCode[ $nextPoint])) ||
              trim($blockCode[ $nextPoint]) === '('
            )
          );

          # Ignore if the $block syntax is not
          # by it self.
          if ( !$blockCommandIsolated ) {
            continue;
          }

          $end = "\n";

          for ($point = $i; $point < strlen($blockCode); $point++) {
            $endCodeSlice = substr ($blockCode, $point,
              strlen ($end)
            );

            if ($endCodeSlice === $end) {
              $endPoint = ($point - $i) + strlen($end);

              $stateMentBody = substr ($blockCode, $i, $endPoint);

              $stateMentNameRe = '/^(@([a-zA-Z0-9_]+)\s*)/i';

              if (preg_match ($stateMentNameRe, $stateMentBody, $match)) {
                $func = $capsuleBlocks [ trim ($match[0]) ];

                $stateMentBody = preg_replace ($stateMentNameRe, '',
                  $stateMentBody
                );

                $replacement = call_user_func_array ($func,
                  [trim($stateMentBody), $obj, $options]
                );


                $blockCode = substr_replace ($blockCode, $replacement,
                  $i, $endPoint
                );
              }


              break;
            }
          }

        }
      }
    }

    return join ('', [
      "{$phpInit}if ( {$blockSignature} )",
      " {{$blockInit}{$phpEnd}\n",
      "{$blockCode}\n",
      "{$phpInit}{$blockEnd}}{$phpEnd}"
    ]);
  }}
}
