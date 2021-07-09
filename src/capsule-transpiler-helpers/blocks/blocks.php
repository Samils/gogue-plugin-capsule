<?php
namespace Sammy\Packs\Gogue\Capsule\Block;

/**
  function is_bettwen_brokets ($str) {
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
  }

  function _getPHPInitCode ($options) {
    $validOptions = ( boolean ) (
      is_array ($options) &&
      isset ($options ['del']) &&
      is_bool ($options ['del'])
    );

    if ( $validOptions ) {
      return $options ['del'] ? '<?php ' : '';
    }

    return '<?php ';
  }

  function _getPHPEndCode ($options) {
    $validOptions = ( boolean ) (
      is_array ($options) &&
      isset ($options ['del']) &&
      is_bool ($options ['del'])
    );

    if ( $validOptions ) {
      return $options ['del'] ? ' ?>' : '';
    }

    return ' ?>';
  }


  function def ( $block, $obj ) {
    $signature = trim ($block['signature']);

    $nameRe = '/^([^\s]+)/';
    preg_match ($nameRe, $signature, $match);

    $signature = trim(preg_replace ($nameRe, '', $signature));

    $capsuleName = trim ($match[0]);

    $args = '$args, CapsuleScopeContext $scope';

    $code = \Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_list (
      $signature, $obj
    );

    $codeInit = "<?php Capsule::Def ('{$capsuleName}', function ($args) {\n{$code}?>\n";

    $endCode = !$obj->capsuleAutoExport() ? '' : (
      "\n<?php Capsule::Export ('{$capsuleName}'); ?>"
    );

    return $codeInit . trim($block['body']) . (
      "\n<?php }); ?>$endCode"
    );
  }


  function _for () {


  }



  function each () {

  }


  function rewriteVariableReferences ($code) {
    $re = '/\$[a-zA-Z_]([a-zA-Z0-9\-\>_]*)/';

    $matchCallback = function ( $match ) {
      $varName = preg_replace ('/^(\$)/', '', $match[0]);

      # Prevent from rewriting '$this' variable
      # when getting the variable name
      if (preg_match ('/^((scope|this)(->(.+)|))$/', $varName)) {
        return $match [ 0 ];
      }

      return '$scope->' . $varName;
    };

    return preg_replace_callback ($re, $matchCallback, $code);
  }


  function _if () {


  }
*/
