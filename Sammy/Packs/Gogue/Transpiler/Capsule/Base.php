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
  use Sammy\Packs\Gogue\ITranspile;
  use Sammy\Packs\Gogue\IGogueComponent;
  use Sammy\Packs\Gogue\IGogueConfigurable;
  /**
   * Make sure the module base internal class is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!class_exists ('Sammy\Packs\Gogue\Transpiler\Capsule\Base')){
  /**
   * @class Base
   * Base internal class for the
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
   */
  class Base implements IGogueConfigurable, IGogueComponent, ITranspile {
    use Core;
    use Extensions;
    use Functions\Base;
    use Code\DoBlocksReader;
    use Configurations\Base;

    use Block\Factory;
    use Block\Reader\Generic;
    use Block\Reader\Specific;
    use Block\DataGetter\Base;
    use CommandReader\Base;
    use Decoder\Symbols;
    use Decoder\Interpolations;
    use Decoder\CapsuleDoBlocks;
    use Decoder\BindingIterpolations;

    use XMLReader;
    use XMLReader\Encoder\Base;
    use XMLReader\Decoder\Base;
    use XMLReader\Capsules\Base;
    use XMLReader\Capsules\Datas;
    use XMLReader\Capsules\Signature;
    use XMLReader\Capsules\Attributes;

    function __construct ($code = '', $file = '') {
      $this->code = $code;
      $this->file = $file;
    }

    function transpileCodeBase () {
      $this->code = $this->readCapsuleBlocks ();

      #$this->code = $this->encodeBlocks ();

      #$this->code = $this->encodeCapsuleXMLElements ();
      $this->code = $this->decodeCapsuleXMLElements (
        $this->encodeCapsuleXMLElements ()
      );

      return trim ($this->transpileCode2 ());
    }

    function run () {
      $commentsReader = self::$commentsReader = \php\requires ('gogue/code/comments-reader');

      $this->code = $commentsReader ($this->code);

      $this->code = $this->encodeBlocksInPartialCode ($this->code);

      #print_r(self::$blockStore);

      #exit ("\n\n\n\n\n\n\n\n\n\n\n\n\n\n\nCODE -> \n\n\n\n\n\n\n\n\n" . $this->code);

      #return $this->code;

      #exit ($this->code);
      #exit ($this->code);
      #$this->code = $this->encodeCapsuleXMLElements ();

      $codeInit = "<?php namespace App\View; use Saml; use Sami;use Sammy\Packs\Samils\Capsule\CapsuleScopeContext; use Sammy\Packs\CapsuleHelper; use Sammy\Packs\CapsuleHelper\ArrayHelper; use Sammy\Packs\CapsuleHelper\ObjectHelper;\n# Capsule Body\n?>\n";

      $codeEnd = "<?php if (!(is_object (\$module->exports) && \$module->exports instanceof Capsule)) { \$module->exports = Capsule::Create (function () {}); }?>";


      $this->code = $this->decodeCapsuleXMLElements (
        $this->encodeCapsuleXMLElements ()
      );

      # $this->code = $this->readCapsuleDoBlocks ();
      #echo ($this->code);
      #exit (0);

      # Reac capsule blocks
      # Such as '@def', '@for' and others
      $this->code = $this->readCapsuleBlocks ();


      #exit (0);

      #echo ($this->code);


      #echo "\n\n\n\n\n\n\n\n\n\n".$this->file."\n\n\n\n\n\n\n\n\n\n\n";


      #$this->code = $this->rewriteVariableReferences (
      #  $this->code
      #);

      #exit ($this->code);
      #$this->code = $this->decodeCapsuleSymbols ($this->code);

      $this->code = $commentsReader->decodeStrings (
        trim ($this->transpileCode2 ())
      );

      $finalCapsuleCode = (
        $codeInit .
        $this->code .
        $codeEnd
      );

      #print_r(self::$blockStore);

      #return preg_replace ('/\s+/', ' ', $finalCapsuleCode);
      return $finalCapsuleCode;
    }




    private function rewriteVariableReferences ($code) {
      $functionReference = join ('\\', [
        'Sammy',
        'Packs',
        'Gogue',
        'Capsule',
        'Block',
        'Extension',
        'rewriteVariableReferences'
      ]);

      return call_user_func_array (
        $functionReference, [$code]
      );
    }
  }}
}
