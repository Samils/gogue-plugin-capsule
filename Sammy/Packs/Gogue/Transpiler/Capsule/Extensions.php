<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Extensions')){
  /**
   * @trait Extensions
   * Base internal trait for the
   * Gogue\Transpiler\Capsule module.
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
  trait Extensions {

    private $code;
    private $file;
    private static $blockStore = array ();

    private $blocksSyntaxes = array (
      ['{', '}', 'block'],
      ['(', ')', 'group'],
      ['[', ']', 'array']
    );

    function getBlockEnd ($block, $point) {
      # Get the end of the given block in the
      # given position.
      # While doing that, get others possible
      # blocks that should be inside it and
      # save them inside the a capsule too.
      # ---
      # Map the code string to start reading
      # blocks beggining in the code from the
      # given point in the code ($point)
      for ($i = $point; $i < strlen($this->code); $i++) {
        $expectedEnds = 1;
        # Map the syntaxes inside the
        # blocksSyntaxes array in order
        # getting one of them being used
        # in the current position in the
        # code.
        foreach ($this->blocksSyntaxes as $syntax) {
          $codeSlice = substr($this->code, $i, strlen($syntax[0]));

          if ($codeSlice === $syntax[0]) {

            #echo $codeSlice, " => ", $i, "\n\n";

            $blockBody = $this->getBlockEnd ($syntax, $i + 1);

            if ($syntax [0] === $block [0]) {
              $expectedEnds++;
            }
            # echo "\n\n\n", $blockBody[0], "\n\n\n";
            $blockType = $syntax [2];
            $blockId = count( self::$blockStore );
            $syntaxName = "::={$blockType}-block{$blockId}::";

            array_push(self::$blockStore,
              $blockBody
            );

            $replacementCode = substr (
              $this->code,
              $blockBody ['start'],
              $blockBody ['end']
            );

            $replacementCodeLen = strlen ($replacementCode);

            $replacementCharsNumber = ($replacementCodeLen - 4) - strlen ($syntaxName);

            #echo $replacementCharsNumber;

            #exit (0);

            $replacementChars = str_repeat (
              '*',
              !($replacementCharsNumber >= 1) ? 0 : (
                $replacementCharsNumber
              )
            );



            #exit ("NUM => " . $replacementChars);

            $replacement = $syntaxName . (
              !($replacementCharsNumber >= 1) ? '' : (
                "/*{$replacementChars}*/"
              )
            );

            $this->code = substr_replace (
              $this->code,
              $replacement,
              $blockBody ['start'],
              $blockBody ['end']
            );
          }
        }

        # A code slice that should
        # contain the block end
        $endSlice = substr($this->code, $i, strlen($block[1]));
        # Verify if the block end
        # has been acieved
        if ($endSlice === $block[1]) {
          if ($expectedEnds <= 1) {
            $endPoint = ($i - $point) + strlen($block[1]) + 1;

            return [
              substr ($this->code, $point - 1, $endPoint),
              'start' => $point - 1,
              'end' =>  $endPoint
            ];
          } else {
            $expectedEnds--;
          }
        }
      }
    }


    private function getBlockEndInPartialCode ($partialCode, $block, $point) {
      $codeLen = strlen($partialCode);
      # Get the end of the given block in the
      # given position.
      # While doing that, get others possible
      # blocks that should be inside it and
      # save them inside the a capsule too.
      # ---
      # Map the code string to start reading
      # blocks beggining in the code from the
      # given point in the code ($point)
        $expectedEnds = 1;
      for ($i = $point; $i < $codeLen; $i++) {

        $codeSlice = substr($partialCode, $i, strlen($block[0]));

        if ($codeSlice === $block [0]) {
          $expectedEnds++;
        }
        # Map the syntaxes inside the
        # blocksSyntaxes array in order
        # getting one of them being used
        # in the current position in the
        # code.
        /**
        foreach ($this->blocksSyntaxes as $syntax) {
          $codeSlice = substr($partialCode, $i, strlen($syntax[0]));

          if ($codeSlice === $syntax[0]) {

            #echo $codeSlice, " => ", $i, "\n\n";

            $blockBody = $this->getBlockEndInPartialCode ($partialCode, $syntax, $i + 1);

            if ($syntax [0] === $block [0]) {
              $expectedEnds++;
            }
            # echo "\n\n\n", $blockBody[0], "\n\n\n";
            $blockType = $syntax [2];
            $blockId = count( self::$blockStore );
            $syntaxName = "::={$blockType}-block{$blockId}::";

            array_push (self::$blockStore,
              $blockBody
            );

            $replacementCodeBody = substr (
              $partialCode,
              $blockBody ['start'],
              $blockBody ['end']
            );

            $replacement = $syntaxName;

            $partialCode = substr_replace (
              $partialCode,
              $replacementCodeBody . '-',
              $blockBody ['start'],
              $blockBody ['end']
            );
          }
        }
        */

        # A code slice that should
        # contain the block end
        $endSlice = substr($partialCode, $i, strlen($block[1]));
        # Verify if the block end
        # has been acieved
        if ($endSlice === $block[1]) {
          if ($expectedEnds <= 1) {
            $endPoint = ($i - $point) + strlen($block[1]) + 1;

            return [
              substr ($partialCode, $point - 1, $endPoint),
              'start' => $point - 1,
              'end' =>  $endPoint
            ];
          } else {
            $expectedEnds--;
          }
        }
      }
    }


    private function encodeBlocksInPartialCode ($partialCode = '') {

      $partialCodeLen = strlen ($partialCode);

      for ($n = 0; $n < 1; $n++) {
        for ($i = 0; $i < strlen ($partialCode); $i++) {
          # Map the syntaxes inside the
          # blocksSyntaxes array in order
          # getting one of them being used
          # in the current position in the
          # code.
          foreach ($this->blocksSyntaxes as $syntax) {
            $codeSlice = substr($partialCode, $i, strlen($syntax[0]));

            if ($codeSlice === $syntax[0]) {

              #exit ($codeSlice);

              #echo $codeSlice, " => ", $i, "\n\n";

              $blockBody = $this->getBlockEndInPartialCode (
                $partialCode,
                $syntax,
                $i + 1
              );

              #if (!$blockBody)
              #   continue;

              #echo "\n", $blockBody, "\n\n";

              $blockType = $syntax [2];
              $blockId = count ( self::$blockStore );
              array_push (self::$blockStore, null);
              $syntaxName = "::={$blockType}-block{$blockId}::";

              $openChar = $syntax [0];
              $closeChar = $syntax [1];


              #exit ($blockBody[0]);

              $blockBodyContent = $this->encodeBlocksInPartialCode (
                substr ($blockBody [0], 1, strlen ($blockBody[0]) - 2)
              );

              #exit ($blockBodyContent);


              $blockBody [0] = join ('', [
                $openChar,
                $blockBodyContent,
                $closeChar
              ]);

              #exit ($blockBody[0]);

              if (in_array ($blockType, ['array', 'group'])) {
                $blockBody [0] = $this->rewriteVariableReferences ($blockBody [0]);
              }

              self::$blockStore [$blockId] = /*in_array ($blockType, ['array', 'group']) ? $this->rewriteVariableReferences ($blockBody) :*/ $blockBody;

              $replacement = $syntaxName . '';

              #echo "\n";
              #print(gettype($blockBody));
              #echo "\n";

              #echo $blockBody['start'], ' => ', $syntax[0], "\n\n";

              $partialCode = substr_replace (
                $partialCode,
                $replacement,
                $blockBody ['start'],
                $blockBody ['end']
              );
            }
          }
        }
      }

      #echo "\n\n";
      #print_r(self::$blockStore);
      #echo "\n\n";
      #print_r(self::$blockStore);
      return $partialCode;
    }


    function encodeBlocks () {
      $codeLen = strlen($this->code);

      for ($i = 0; $i < $codeLen; $i++) {
        # Map the syntaxes inside the
        # blocksSyntaxes array in order
        # getting one of them being used
        # in the current position in the
        # code.
        foreach ($this->blocksSyntaxes as $syntax) {
          $codeSlice = substr($this->code, $i, strlen($syntax[0]));

          if ($codeSlice === $syntax[0]) {
            #echo $codeSlice, " => ", $i, "\n\n";

            $blockBody = $this->getBlockEnd ($syntax, $i + 1);

            #if (!$blockBody)
            #   continue;

            #echo "\n", $blockBody, "\n\n";

            $blockType = $syntax [2];
            $blockId = count( self::$blockStore );
            $syntaxName = "::={$blockType}-block{$blockId}::";

            array_push (self::$blockStore, $blockBody);

            $replacementCode = substr (
              $this->code,
              $blockBody ['start'],
              $blockBody ['end']
            );

            $replacementCodeLen = strlen ($replacementCode);

            $replacementCharsNumber = ($replacementCodeLen - 4) - strlen ($syntaxName);

            #echo $replacementCharsNumber;

            #exit (0);

            $replacementChars = str_repeat (
              '*',
              !($replacementCharsNumber >= 1) ? 0 : (
                $replacementCharsNumber
              )
            );



            #exit ("NUM => " . $replacementChars);

            $replacement = $syntaxName . (
              !($replacementCharsNumber >= 1) ? '' : (
                "/*{$replacementChars}*/"
              )
            );

            #echo "\n";
            #print(gettype($blockBody));
            #echo "\n";

            #echo $blockBody['start'], ' => ', $syntax[0], "\n\n";

            $this->code = substr_replace (
              $this->code,
              $replacement,
              $blockBody ['start'],
              $blockBody ['end']
            );
          }
        }
      }

      #echo "\n\n";
      #print_r(self::$blockStore);
      #echo "\n\n";
      #print_r(self::$blockStore);
      return $this->code;
    }


    function decodeBlocksMT ($match) {
      $id = (int)($match [ 1 ]);

      if (!isset(self::$blockStore [$id])) {
        return $match[0];
      }

      $blockDatas = self::$blockStore [$id];

      return trim ( $this->decodeBlocks ( $blockDatas [0] ) );
    }

    function decodeBlocks ($code) {
      $re = '/::=group-block([0-9]+)::/i';


      #echo $code, "\n\n";

      #preg_match ($re, $code, $match);
      #print_r($match);


      #echo ("\n\n\nELALAL\n\n\n");

      return preg_replace_callback (
        $re,
        [$this, 'decodeBlocksMT'],
        $code
      );
    }



    function decodeWrapperBlocksMT ($match) {
      $id = (int)($match [ 1 ]);

      if (!isset(self::$blockStore [$id])) {
        return $match[0];
      }

      $blockDatas = self::$blockStore [$id];

      return trim ( $blockDatas [0] );
    }

    function decodeWrapperBlocks ($code, $block = 'group') {
      $re = '/::='.$block.'-block([0-9]+)::/i';

      #echo $code, "\n\n";

      #preg_match ($re, $code, $match);
      #print_r($match);


      #echo ("\n\n\nELALAL\n\n\n");

      return preg_replace_callback (
        $re,
        [$this, 'decodeWrapperBlocksMT'],
        $code
      );
    }


    function decodeWholeBlocksMT ($match) {

      $id = (int)($match [ 2 ]);

      if (!isset (self::$blockStore [$id])) {
        return $match [0];
      }

      $blockDatas = self::$blockStore [$id];

      return trim ($this->decodeWholeBlocks ($blockDatas [0]));
    }

    function decodeWholeBlocks ($code) {
    function decodeWholeBlocks ($code, array $callback = null) {
      $re = '/::=(array|block|group)-block([0-9]+)::/i';

      return preg_replace_callback ($re, [$this, 'decodeWholeBlocksMT'], $code);
    }

  }}
}
