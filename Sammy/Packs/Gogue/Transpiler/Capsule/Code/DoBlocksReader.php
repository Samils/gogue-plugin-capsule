<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\Code
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\Code {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Code\DoBlocksReader')){
  /**
   * @trait DBlocksReader
   * Base internal trait for the
   * Gogue\Transpiler\Capsule\Code module.
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
  trait DoBlocksReader {
    /**
     * [$DoBlocksStore description]
     * @var array
     */
    private static $DoBlocksStore = array ();

    private function getCapsuleDoBlockDatas ($point, $match, $blockBody) {

      $blockSignature = trim ($match [0]);

      $blockArgumentsList = trim (preg_replace ('/^(\s*do\s*)/i', '',
        preg_replace ('/\s+$/', '', $blockSignature)
      ));

      $id = count (self::$DoBlocksStore);
      $replacement = "<<\$capsule-block{$id}>>\n";

      $this->code = substr_replace ($this->code, $replacement,
        $blockBody[ 'init' ], $blockBody[ 'end' ]
      );

      if (is_array($blockBody)) {

        $blockCore = array_merge ($blockBody, [
          'arguments' => $blockArgumentsList
        ]);

        array_push(self::$DoBlocksStore, $blockCore);
        return $blockCore;
      }
    }

    public function readCapsuleDoBlock ($block) {
      $block = trim ((string)($block));
      $re = '/<<\\$capsule-block([0-9]+)>>/i';
      /**
       * Get the block informations
       * if sent a number value
       */
      if ( is_numeric ($block) ) {
        $blockId = ( int )( $block );

        if ( isset(self::$DoBlocksStore [ $blockId ]) ) {
          return self::$DoBlocksStore [ $blockId ];
        }
      } elseif (preg_match ($re, $block, $match)) {


        #echo "\n\n\n\n\n\n\n\n\n\n BLOCK \n\n\n\n\n\n\n\n\n\n";
        return $this->readCapsuleDoBlock ((int)(trim($match[1])));
      }
    }


    private function getCapsuleDoBlockBody ($point) {
      $idn = !$this->capsuleConfigs['commandIdentifier'] ? '' : '@';
      # map each char inside the given code
      # in order finding uses of a capsule
      # block as an argument for a capsule
      # rendering and encode it by the defined
      # pattern
      # <<<capsule-block{id}>>>
      $capsuleBlocks = $this->getCapsuleBlocks ();

      $expectedEnds = 1;
      for ($i = ($point + 1); $i < strlen($this->code); $i++) {
          # map the '$capsuleBlocks' array
          # to get each of them and try
          # matching with the current
          # slice of the code
        foreach ( $capsuleBlocks as $capsuleBlock ) {
              # Current capsule block name
          $block = ($idn . $capsuleBlock);

          $codeSlice = substr ($this->code, $i,
            strlen ($block)
          );

          if ( $codeSlice === $block ) {
            $nextPoint = $i + strlen($block) + 0;
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

                  # <<$group1>>:

            $signatureSlice = substr ($this->code,
              $i + strlen ($block), strlen($this->code)
            );

            $expectedEnds++;
          }
        }
          # Get a slice of the given code in order
          # trying to match the begin of the same
          # slice and know if a capsule block is
          # being opened at the current point of
          # the code
        $codeSlice = substr ($this->code, $i, strlen($this->code));
          # re
          # Regular Expression to match beggining
          # of any capsule block at the current point
          # of the given code
        $re = '/^(\s{1}do\s*(::=group-block([0-9]+)::|)\s*)/i';
          # trying to match the begin of the same
          # slice and know if a capsule block is
          # being opened at the current point of
          # the code
        if (preg_match ( $re, $codeSlice, $match)) {
          if (preg_match ('/[^:\s]/', $codeSlice [3])) {
            continue;
          }

          $blockDatas = $this->getCapsuleDoBlockDatas (($i + 1), $match,
            $this->getCapsuleDoBlockBody ($i+1)
          );
        }

        $end = ($idn . 'end');
          # A code slice that should
          # contain the block end
        $endSlice = substr( $this->code, $i, strlen($end) );
          # Verify if the block end
          # has been acieved
        if ( $endSlice === $end ) {

              # Make sure the number of '$expectedEnds'
              # has been achieved before ending the current structure
          if ($expectedEnds <= 1) {


            $endPoint = ($i - $point) + strlen($end) + 1;
            $blockContent = substr ($this->code, $point, $endPoint);

            $blockContent = preg_replace ($re, '',
              preg_replace ('/((@|)end\s*)$/i', '', ' '.$blockContent)
            );

            return array (
              'body' => $blockContent,
              'init' => $point,
              'end' => $endPoint
            );
          } else {
            $expectedEnds--;
          }
        }



        if (($i + 1) >= strlen($this->code)) {
          $endPoint = strlen($this->code);
          $blockContent = substr ($this->code, $point, $endPoint);

          $blockContent = preg_replace ($re, '',
            preg_replace ('/((@|)end\s*)$/i', '', ' '.$blockContent)
          );

          return array (
            'body' => $blockContent,
            'init' => $point,
            'end' => $endPoint
          );
        }



      }
    }


    private function readCapsuleDoBlocks () {
      # map each char inside the given code
      # in order finding uses of a capsule
      # block as an argument for a capsule
      # rendering and encode it by the defined
      # pattern
      # <<<capsule-block{id}>>>
      for ($i = 0; $i < strlen($this->code); $i++) {
          # Get a slice of the given code in order
          # trying to match the begin of the same
          # slice and know if a capsule block is
          # being opened at the current point of
          # the code
        $codeSlice = substr ($this->code, $i, strlen($this->code));
          # re
          # Regular Expression to match beggining
          # of any capsule block at the current point
          # of the given code
        $re = '/^(\s{1}do\s*(::=group-block([0-9]+)::|)\s*)/i';
          # trying to match the begin of the same
          # slice and know if a capsule block is
          # being opened at the current point of
          # the code
        if (preg_match ($re, $codeSlice, $match)) {

          if (preg_match ('/[^:\s]/', $codeSlice [3])) {
            continue;
          }

          $blockDatas = $this->getCapsuleDoBlockDatas (
            ($i + 1),
            $match,
            $this->getCapsuleDoBlockBody (
              $i + 1
            )
          );
        }

      }

      return $this->code;
    }
  }}
}
