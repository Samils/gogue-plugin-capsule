<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\CommandReader
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\CommandReader {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists ('Sammy\Packs\Gogue\Transpiler\Capsule\CommandReader\Base')) {
  /**
   * @trait Base
   * Base internal trait for the
   * Gogue\Transpiler\Capsule\CommandReader module.
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
     * [transpileCode2]
     * @return string
     */
    function transpileCode2 () {
      # getCapsuleCommands

      $capsuleCommandsArr = $this->getCapsuleCommands ();
      $capsuleCommands = array_keys($capsuleCommandsArr);

      #print_r($capsuleCommands);
      #
      #exit ($this->code);

      # Map whole the code chras
      for ( $i = 0; $i < strlen ($this->code); $i++ ) {
        # map the '$capsuleCommands' array
        # to get each of them and try
        # matching with the current
        # slice of the code
        foreach ( $capsuleCommands as $capsuleCommand ) {
          # Current capsule block name
          $command = ($capsuleCommand);

          $codeSlice = substr ($this->code, $i,
            strlen ($command)
          );

          if ( $codeSlice === $command ) {

            $nextPoint = $i + strlen($command) + 0;
            $prevChar = trim($this->code[ $i - 1]);

            $blockCommandIsolated = ( boolean )(
              (empty ($prevChar) || !preg_match ('/([a-zA-Z0-9_\-])/', $prevChar)) && (
                empty (trim($this->code[ $nextPoint])) ||
                trim($this->code[ $nextPoint]) === '('
              )
            );

            # Ignore if the $block syntax is not
            # by it self.
            if ( !$blockCommandIsolated ) {
              continue;
            }

            #echo "\n\033[34m$command\033[m\n";

            # <<$group1>>:
            $endLine = "\n";
            #echo "\n\n\n", $command, "\n\n\n";

            for ($point = $i; $point < strlen($this->code); $point++) {
              $endSlice = substr ($this->code, $point, strlen($endLine));

              if (preg_match ('/\n+/', $endSlice)) {
                $endPoint = ($point - $i) + strlen($endLine);

                $commandBody = substr ($this->code, $i, $endPoint);

                            #print_r($capsuleCommandsArr);

                $commandFuncName = $capsuleCommandsArr [
                  trim ($command)
                ];

                $replacement = call_user_func_array (
                  $commandFuncName, [
                    $commandBody, $this
                  ]
                );

                $this->code = substr_replace($this->code, $replacement,
                  $i, $endPoint
                );

                #echo "\n\n\n\nCODE\n\n\n\n";
                #echo $replacement;
                #echo "\n";

                break;
              } elseif (($point+1) >= strlen($this->code)) {
                $codeLen = strlen($endLine);
                $endPoint = ($point - $i) + $codeLen;

                $commandBody = substr ($this->code, $i, $codeLen);


                $commandFuncName = $capsuleCommandsArr [
                  trim ($command)
                ];

                $replacement = call_user_func_array (
                  $commandFuncName, [
                    $commandBody, $this
                  ]
                );

                $this->code = substr_replace($this->code, $replacement,
                  $i, $endPoint
                );

                #echo "\n\n\n\nCODE\n\n\n\n";
                #echo $replacement;
                #echo "\n";
              }
            }

          }
        }
      }



      #$this->code = $this->decodeCapsuleDoBlocks ();



      #echo ($this->code);


      #echo "\n\n\n\n\n\n\n\n\n\n".$this->file."\n\n\n\n\n\n\n\n\n\n\n";




      $this->code = $this->decodeCapsuleIterpolations (
        $this->code
      );

      #exit ($this->code);
      $this->code = $this->decodeWholeBlocks ($this->code);
      #$this->code = $this->decodeCapsuleSymbols ($this->code);

      $this->code = $this->decodeCommandBlocks ($this->code);

      $this->code = $this->decodeWholeBlocks ($this->code);
      #$this->code = $this->decodeCapsuleSymbols ($this->code);
      return $this->decodeCapsuleSymbols ($this->code);
      /*
      return preg_replace (
        '/\n+/',
        '',
        preg_replace (
          '/\s{2,}/',
          ' ',
          $this->code
        )
      );
      */
    }
  }}
}
