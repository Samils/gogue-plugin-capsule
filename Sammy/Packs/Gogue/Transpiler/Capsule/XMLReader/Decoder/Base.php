<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Decoder
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Decoder {
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\XMLReader\Decoder\Base')){
  /**
   * @trait Base
   * Base internal trait for the
   * Gogue\Transpiler\Capsule\XMLReader\Decoder module.
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
    private $capsuleCodeRe = '/^%::capsule-code([0-9]+)::$/i';
    private $commandBlockRe = '/^@([a-zA-Z]+)/i';
    private $nestingBlocksKeywords = [
      'else',
      'elseif',
      'elsif'
    ];

    private static $commandBlockStore = [];
    /**
     * [decodeCapsuleXMLElementsMT]
     * @param  array $match
     * @return string
     */
    private function decodeCapsuleXMLElementsMT ($match, $isChild = false, $options = []) {
      $options = is_array ($options) ? $options : [
        'del' => true
      ];

      $delimitPropertySet = ( boolean ) (
        is_array ($options) &&
        isset ($options ['del']) &&
        is_bool ($options ['del'])
      );

      if ( !$delimitPropertySet ) {
        $options ['del'] = true;
      }

      $delimit = ( boolean ) (
        is_array ($options) &&
        isset ($options ['del']) &&
        is_bool ($options ['del']) &&
        $options ['del']
      );

      #if ($delimit) exit ('YAAAAAsA!');

      #exit ('VAL => ' . $options['del']);
      /**
       * [$id Current Capsule ID inside the capsuleStore]
       * @var int
       */
      $id = (int) ($match[1]);
      $isChild = is_bool ($isChild) ? $isChild : false;

      if (!isset (self::$capsuleStore [ $id ])) {
        return $match[0];
      }

      $capsuleDatas = self::$capsuleStore [$id];

      $body = !isset($capsuleDatas ['body']) ? '' : (
        trim ($capsuleDatas ['body'])
      );

      $noEmptyAttributesList = ( boolean ) (
        isset ($capsuleDatas['attributes']) &&
        is_string ($capsuleDatas['attributes']) &&
        !empty ($capsuleDatas['attributes']) && (
          $capsuleDatas['attributes'] !== '[]'
        )
      );

      $name = trim ($capsuleDatas ['name']);

      $renderDatas = $this->getCapsuleRenderDatas (
        $name, $isChild
      );

      $renderMethodName = $renderDatas ['renderMethodName'];
      $renderComponentName = $renderDatas ['renderComponentName'];

      $args = !$noEmptyAttributesList ? '' : (
        (empty ($renderComponentName) ? '' : ', ') .
        $capsuleDatas['attributes']
      );

      if (empty ($args)) {
        $args = (empty ($renderComponentName) ? '' : ', ') . '[]';
      }

      $phpInit = $delimit ? '<?php ' : '';
      $phpEnd = $delimit ? ' ?>' : '';

      #exit ($phpInit);

      if (empty ($body)) {

        if ( !$isChild ) {
          return "{$phpInit}Capsule::{$renderMethodName} ({$renderComponentName}{$args});{$phpEnd}\n";
        } else {
          return ", Capsule::{$renderMethodName} ({$renderComponentName}{$args})";
        }

      } else {

        $capsuleChildrenList = $this->getCapsuleChildrenList (
          $body
        );

        if ( !$isChild ) {
          $code = ("\n{$phpInit} Capsule::{$renderMethodName} "
            . "({$renderComponentName}{$args}{$capsuleChildrenList});{$phpEnd}\n"
          );
        } else {
          $code = ", Capsule::{$renderMethodName} " . (
            "({$renderComponentName}{$args}{$capsuleChildrenList})"
          );
        }

        #$code .= $this->decodeCapsuleXMLElements ($body);

        /*return $code . "\n<?php })); ?>;*/



        return $code;
      }
    }

    private function getCapsuleChildrenList ($capsuleBody, $options = []) {
      $capsuleBodyLines = is_array ($capsuleBody) ? $capsuleBody : (
        preg_split ( '/\n+/', $capsuleBody )
      );
      $capsuleBodyLinesCount = count ($capsuleBodyLines);
      $childrenList = '';

      $options = is_array ($options) ? $options : [];

      #$delimit = ( boolean ) (
      #  is_array ($options) &&
      #  isset ($options ['del']) &&
      #  is_bool ($options ['del']) &&
      #  $options ['del']
      #);

      for ($i = 0; $i < $capsuleBodyLinesCount; $i++) {
        $capsuleBodyLine = $capsuleBodyLines [ $i ];
        $line = trim ($capsuleBodyLine);

        if (empty ($line)) {
          continue;
        }

        if (preg_match ($this->capsuleCodeRe, $line, $match)) {

          $childrenList .= $this->decodeCapsuleXMLElementsMT (
            $match, true, $options
          );
        } else {


          if ($this->commandBlockSignature ($line)) {
            #if ($this->isNestingBlocksKeyword ($line)) {
              #continue;
            #}
            #echo $line, "\n";

            $commandBlock = $this->commandBlock (
              # The current line to be parsed in
              # the loop.
              # This'll allow to get the current
              # line content in order knowing what
              # it should be; a simple string or a
              # command structure block being opened
              # in the current code line
              trim ($line),
              # Whole of the current capsule body lines.
              # Should be used to the the command structure
              # block body and render the same correctly in
              # the code
              $capsuleBodyLines,
              # index
              # SHould be used to map the capsuleBodyLines array
              # from the current point when getting the current
              # capsule body lines.
              $i
            );

            $childrenList .= $commandBlock [ 0 ];
            $i = $commandBlock [ 1 ] - 1;
          } else {
            $childrenList .= $this->readCapsuleStringChild ($line);
          }
        }

      }

      #echo "\n\nEND\n\n";

      return $childrenList;
    }

    private function commandBlock (string $line_, $capsuleBodyLines, $index, $isChild = true) {

      if (preg_match ($this->commandBlockRe, $line_, $match)) {
        # CommandName
        # - The current command name
        # CommandFunction
        # - The command handler
        list ($commandName, $commandFunction) = (
          $this->commandDatas (strtolower (@$match [ 1 ]))
        );

        $capsuleBodyLinesCount = count ($capsuleBodyLines);
        $expectedEnds = 1;

        $commandBlockKeyCode = '';

        # TODOS:
        #
        # [] - Round whole the '$capsuleBodyLines' to get the
        #      current commandBlock body finding the '@end' key.
        # [] - Store the commandBody inside a '$commandBody'
        #      variable
        # [] - Generate the correct code for the given command
        #      structure based on the CapsuleSyntax.
        #      Use the '$commandFunction' for doing that.
        # [] - Return the generated code
        #

        for ($i = $index + 1; $i < $capsuleBodyLinesCount; $i++) {
          $capsuleBodyLine = $capsuleBodyLines [ $i ];
          $line = trim ($capsuleBodyLine);

          if ($this->commandBlockSignature ($line)) {
            $expectedEnds++;
          }

          if (strtolower ($line) === '@end' && $expectedEnds >= 1) {

            if ($expectedEnds <= 1) {

              $commandBody = $this->parseCommandBlocksFromPartialCode (
                array_slice ($capsuleBodyLines, $index + 1, ($i - $index) - 1)
              );

              $commandSignature = preg_replace (
                '/^@([a-zA-Z]+)/', '',
                trim ($line_)
              );

              $commandBodyAsString = call_user_func_array (
                $commandFunction,
                [
                  [
                    'body' => join ("\n", $commandBody),
                    'signature' => $commandSignature
                  ],
                  $this,
                  [
                    'del' => false,
                    'blockInitCode' => '',#'<Fragment>',
                    'blockEndCode' => ''#'</Fragment>'
                  ]
                ]
              );

              #$commandBodyAsString2 = $this->encodeCapsuleXMLElements (
              #  $commandBodyAsString
              #);

              $commandBodyAsString2 = $this->decodeCapsuleXMLElements (
                $commandBodyAsString, false
              );

              if ($isChild) {
                $commandBlockBody = join ('', [
                  ', function ($args, CapsuleScopeContext $scope) {',
                  $commandBodyAsString2,
                  '}'
                ]);
              } else {
                $commandBlockBody = join ('', [
                  'Capsule::PartialRender (\'Fragment\', [], function ($args, CapsuleScopeContext $scope) {',
                  $commandBodyAsString2,
                  '});'
                ]);
              }

              #echo "\n\n\n\n\n\n\nC => \n\n\n\n\n\n\n\n\n\n", preg_replace ('/\n+/', "\n", $commandBlockBody), "\n\n\n\n\n\n\n\n\nEND\n\n\n\n\n\n\n\n\n";

              #exit ($commandBlockBody);
              $id = count (self::$commandBlockStore);
              $commandBlockKeyCode .= "<<=comand-block-{$id}>>";

              array_push (self::$commandBlockStore,
                $commandBlockBody
              );

              $expectedEnds = 1;

              return [$commandBlockKeyCode, $i + 1, $index];
            }

            $expectedEnds--;
          }
        }

      }

      return ['', $i + 1];
    }

    /**
     *
     */
    private function parseCommandBlocksFromPartialCode ($blockBodyLines) {
      $blockBodyLinesCount = count ($blockBodyLines);

      for ($blockBodyLineIndex = 0; $blockBodyLineIndex < $blockBodyLinesCount; $blockBodyLineIndex++) {
        $blockBodyLine = trim ($blockBodyLines [$blockBodyLineIndex]);

        if ($this->commandBlockSignature ($blockBodyLine)
          && preg_match ($this->commandBlockRe, $blockBodyLine, $match)) {

          # CommandName
          # - The current command name
          # CommandFunction
          # - The command handler
          list ($commandName, $commandFunction) = (
            $this->commandDatas (strtolower (@$match [ 1 ]))
          );

          $commandSignature = preg_replace (
            '/^@([a-zA-Z]+)/', '', trim ($blockBodyLine)
          );

          $parsedCommandSignature = call_user_func_array (
            $commandFunction,
            [
              [
                'body' => '',
                'signature' => $commandSignature
              ],
              $this,
              [
                'del' => false,
                'blockInitCode' => '',#'<Fragment>',
                'blockEndCode' => ''#'</Fragment>'
              ]
            ]
          );

          $parsedCommandSignature = preg_replace ('/(\s*\}\s*)$/', '', $parsedCommandSignature);

          $commandBlockBody = join ('', [
            'Capsule::PartialRender (\'Fragment\', [], function ($args, CapsuleScopeContext $scope) {',
            $parsedCommandSignature
          ]);

          $blockBodyLines [$blockBodyLineIndex] = $commandBlockBody;
        } elseif (preg_match ('/^(@end)$/i', $blockBodyLine)) {
          $blockBodyLines [$blockBodyLineIndex] =  "}});";
        }
      }

      return $blockBodyLines;
    }

    private function decodeCommandBlocks ($code) {
      $re = '/<<\\=comand-block-([0-9]+)>>/';
      return preg_replace_callback ($re, function ($match) {
        $commandBlockId = (int)($match[1]);

        if (isset (self::$commandBlockStore [$commandBlockId])) {
          return $this->decodeCommandBlocks (
            self::$commandBlockStore [$commandBlockId]
          );
        }

         return $match [0];

      }, $code);
    }

    private function commandDatas (string $command) {
      $commandFunctionNameSpace = join ('\\',[
        'Sammy',
        'Packs',
        'Gogue',
        'Capsule',
        'Block',
        ''
      ]);

      #exit ($commandFunctionNameSpace);

      if (!function_exists ($commandFunctionNameSpace.$command)) {
        return [ $command, $commandFunctionNameSpace.'_'.$command];
      }

      return [ $command, $commandFunctionNameSpace.$command];
    }

    private function commandBlockSignature ($line) {
      if (preg_match ($this->commandBlockRe, trim ($line), $match)) {
        $capsuleBlocks = $this->getCapsuleBlocks ();

        # print_r($capsuleBlocks);
        # echo "Command => ", $match [1], "\n", 'Line => ', $line, "\n\nEND\n\n";

        return ( boolean ) (
          in_array (strtolower ($match [1]), $capsuleBlocks) #||
          #in_array ($match [1], $this->nestingBlocksKeywords)
        );
      }
    }

    private function isNestingBlocksKeyword ($line) {
      if (preg_match ($this->commandBlockRe, trim ($line), $match)) {
        $capsuleBlocks = $this->getCapsuleBlocks ();

        return ( boolean ) (
          in_array ($match [1], $this->nestingBlocksKeywords)
        );
      }
    }

    private function escapeStringChars ($string) {
      $pregRemplaceCallback = function ($match) {
        print_r($match);


        exit (0);
      };

      return $string;

      #echo ($string . "\n");

      #return preg_replace_callback (
      #  "/[\\\']/",
      #  $pregRemplaceCallback,
      #  $string
      #);
    }

    private function getCapsuleRenderDatas ($capsuleName, $isChild) {
      if (preg_match ('/^(yield)$/i', $capsuleName)) {
        $yieldMethodName = $isChild ? 'Yield' : (
          'RenderYieldContext'
        );

        return $this->getCapsuleRenderDatasFactory (
          'null', $yieldMethodName
        );
      }

      $renderMethodName = !$isChild ? 'PartialRender' : 'CreateElement';

      return $this->getCapsuleRenderDatasFactory (
        "'$capsuleName'", $renderMethodName
      );
    }

    private function getCapsuleRenderDatasFactory ($renderComponentName, $renderMethodName) {
      return [
        'renderComponentName' => $renderComponentName,
        'renderMethodName' => $renderMethodName
      ];
    }

    private function decodeCapsuleXMLElementsMTND ($match) {
      return $this->decodeCapsuleXMLElementsMT (
        $match,
        false,
        [
          'del' => false
        ]
      );
    }

    private function decodeCapsuleXMLElements ($code, $del = true) {
      $re = '/%::capsule-code([0-9]+)::/';

      $del = is_bool($del) ? $del : true;
      $methodName = 'decodeCapsuleXMLElementsMT';

      if (!$del) $methodName .= 'ND';

      #echo $code;
      #exit (0);


      return preg_replace_callback (
        $re,
        [ $this, $methodName ],
        $code
      );
    }

    private function linePregSplittingCallback ($match, $decode = false) {
      static $store = [];

      if (!$decode && is_array ($match)) {
        $bindiInterpolation = (string)($match [0]);

        $bindiInterpolationId = join ('', [
          rand(0, 999999999), time(), date('YmdHis')
        ]);

        $bindiInterpolationBody = join ('', ['::bib:', $bindiInterpolationId, ':']);

        $store [$bindiInterpolationId] = $bindiInterpolation;

        return "\n{$bindiInterpolationBody}\n";
      } elseif (is_string ($match) && $decode) {
        $bindiInterpolationId = trim ($match);

        if (isset ($store [$bindiInterpolationId])) {
          return $this->decodeBindingIterpolationsFromStr ($store [$bindiInterpolationId]);
        }
      }
    }

    private function decodeBindingIterpolationsFromStr ($string) {
      $re = '/::bib:([0-9]+):/i';

      return preg_replace_callback ($re, function ($match) {
        #echo "\n\ndecodeBindingIterpolationsFromStr\n\n";

        return $this->linePregSplittingCallback ($match [1], true);
      }, $string);
    }

    private function decodeBlocksInString ($match) {
      $id = (int)($match [ 2 ]);

      if (!isset (self::$blockStore [$id])) {
        return $match [0];
      }

      $blockDatas = self::$blockStore [$id];

      $blockContent = $blockDatas [0];

      $blockContent = preg_replace_callback (
        $this->bindingInterpolationRe,
        [$this, 'linePregSplittingCallback'],
        $blockContent
      );

      return $this->decodeWholeBlocks ($blockContent, [$this, 'decodeBlocksInString']);
    }

    private function readCapsuleStringChild ($line) {

      $line = preg_replace_callback (
        $this->bindingInterpolationRe,
        [$this, 'linePregSplittingCallback'],
        $line
      );

      $line = preg_replace_callback (
        $this->bindingInterpolationRe,
        [$this, 'linePregSplittingCallback'],
        $this->decodeWholeBlocks ($line, [$this, 'decodeBlocksInString'])
      );

      #echo "Str => ", $line, "\n\n";

      $stringChildrenList = '';
      $lineMap = preg_split ("/\n+/", trim ($line));
      $lineMapCount = count ($lineMap);

      for ($i = 0; $i < $lineMapCount; $i++) {
        $currentLine = $lineMap [ $i ];

        if (empty (trim ($currentLine))) {
          continue;
        } elseif (preg_match ('/::bib:([0-9]+):/i', $currentLine, $match)) {
          $childbody = $this->decodeBindingIterpolationsFromStr ($match [0]);

          if (preg_match ($this->bindingInterpolationRe, $childbody, $bindiInterpolationMatch)) {
            $childbody = $this->decodeCapsuleBindingIterpolationsMT (
              $bindiInterpolationMatch, [ 'del' => false ]
            );

            $stringChildrenList .= ", function(\$args, CapsuleScopeContext \$scope){return {$childbody};}";
          }
        } else {
          $currentLine = $this->escapeStringChars (
            $currentLine
          );

          $strData = $this->readRawStr ($currentLine);

          $stringChildrenList .= ", $strData";
        }
      }

      return $this->decodeBindingIterpolationsFromStr ($stringChildrenList);
    }

    /**
     * @method string readRawStr
     */
    private function readRawStr (string $rawStr) {
      if (preg_match ('/^::\$([0-9]+):$/', $rawStr)) {
        return $rawStr;
      }

      return "'$rawStr'";
    }
  }}
}
