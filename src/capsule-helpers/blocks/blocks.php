<?php
/**
namespace Sammy\Packs\Gogue\Capsule\Block;


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


function _for ($block, $obj = null, $options = null) {

  $signature = trim($block['signature']);

  $phpInit = _getPHPInitCode ($options);
  $phpEnd = _getPHPEndCode ($options);

  $signature = rewriteVariableReferences (
    $signature
  );

  if (is_bettwen_brokets ($signature)) {
    $signature = preg_replace ('/^(\s*\(?)/', '',
      preg_replace ('/(\s*(\)\s*)?)$/', '', $signature)
    );
  }

  if (preg_match ('/^((.+)\s+(as|in)\s+(.+))$/i', $signature, $match)) {
    $op = strtolower($match [3]);

    if ($op === 'in') {
      $arrayName = $match[4];

      $id = rand (100, 9999) . date ('His');

      $arrayNameRef = "\$arr{$id}ay";

      $bodyCode = "{$phpInit}{$arrayNameRef} = $arrayName; for ( \$i{$id}terator = 0; \$i{$id}terator < count ({$arrayNameRef}); \$i{$id}terator++ ) {\n\t" . (
        $match[2] . " = \Saml::Array2Object ({$arrayNameRef}[ \$i{$id}terator ]);\n{$phpEnd}\n"
      );

      return $bodyCode . $block['body'] . "\n{$phpInit}}{$phpEnd}";
    } else {
      $arrayName = $match[2];

      $id = rand (100, 9999) . date ('His');

      $arrayNameRef = "\$arr{$id}ay";
      $bodyCode = "{$phpInit}{$arrayNameRef} = $arrayName; for ( \$i = 0; \$i < count ({$arrayNameRef}); \$i++ ) {\n\t" . (
        $match[4] . " = \Saml::Object2Array ({$arrayNameRef}[ \$i ]);\n{$phpEnd}\n"
      );

      return $bodyCode . $block['body'] . "\n{$phpInit}}{$phpEnd}";
    }
  }

  return "{$phpInit}for ( {$signature} ) { {$phpEnd}\n {$block['body']} \n{$phpInit}} {$phpEnd}";
}



function each ($block, $obj = null, $options = null) {
  $signature = trim (preg_replace ('/^(\s*\(?)/', '',
    preg_replace ('/(\s*(\)\s*)?)$/', '', trim($block['signature']))
  ));

  $phpInit = _getPHPInitCode ($options);
  $phpEnd = _getPHPEndCode ($options);

  $signature = rewriteVariableReferences (
    $signature
  );

  if (preg_match ('/^((.+)\s+(as|in)\s+(.+))$/i', $signature, $match)) {
    $op = strtolower($match [3]);

    if ($op === 'in') {
      $arrayName = $match[4];

      $bodyCode = "{$phpInit}for ( \$i = 0; \$i < count ({$arrayName}); \$i++ ) {\n\t" . (
        $match[2] . " = \Saml::Array2Object ({$arrayName}[ \$i ]);\n{$phpEnd}\n"
      );

      return $bodyCode . $block['body'] . "\n{$phpInit}} {$phpEnd}";
    } else {
      $arrayName = $match[2];
      $bodyCode = "{$phpInit}for ( \$i = 0; \$i < count ({$arrayName}); \$i++ ) {\n\t" . (
        $match[4] . " = \Saml::Object2Array ({$arrayName}[ \$i ]);\n{$phpEnd}\n"
      );

      return $bodyCode . $block['body'] . "\n{$phpInit}} {$phpEnd}";
    }
  }

  $id = rand (100, 9999) . (
    date ('His')
  );

  $dataRef = "\$ref{$id}erence";
  $ref = "\$ref$id";

  $code = ("{$dataRef} = {$signature};\n\tif (isset ($dataRef) ".
    "&& is_array ($dataRef)) {".
    "\n\tfor (\$i = 0; \$i < count ({$dataRef}); \$i++) {".
    "\n\t\t{$ref} = {$dataRef}[\$i];\n\t\tif (in_array (".
    "strtolower(gettype({$ref})), ['array', 'object']".
    ")) {\n\t\t\t{$ref} = \\Saml::Array2Object({$ref});".
    "\n\t\t\t{$ref}_props = array_keys ((array)".
    "({$ref}));\n\t\t\tif (is_object ($ref) && in_array ('Sammy\Packs\Sami\Base\\".
    "ILinable', class_implements (get_class ($ref)))) {\n\t\t\t\t".
    "{$ref}_props = array_keys ((array)({$ref}->lean()));\n\t\t\t}\n".
    "\t\t\tforeach ({$ref}_props as \$key) {".
    "\n\t\t\t\tif (is_right_var_name(\$key)) ".
    "{ \$scope->\$key = is_object ($ref) ? {$ref}->\$key : {$ref}[\$key]; }\n\t\t\t}\n\t\t}"
  );

  return "{$phpInit}{$code}\n{$phpEnd}\n {$block['body']} \n{$phpInit}}} {$phpEnd}";
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


function _if ($blockDatas, $obj = null, $options = []) {

  $capsuleBlocks = $obj->getCapsuleIfCommands ();
  $capsuleBlocksArr = array_keys ($capsuleBlocks);

  $phpInit = _getPHPInitCode ($options);
  $phpEnd = _getPHPEndCode ($options);

  $blockCode = $blockDatas [ 'body' ];
  $blockSignature = trim($blockDatas ['signature']);

  $blockSignature = rewriteVariableReferences (
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
                [trim($stateMentBody), $obj]
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


  return "{$phpInit}if ( {$blockSignature} ) {{$phpEnd}\n{$blockCode}\n" . (
    "{$phpInit}}{$phpEnd}"
  );
}

*/
