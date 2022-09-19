<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Capsule\Extensions
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Capsule\Extensions {
  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\parse_block_argument_list')){
  /**
   * @function parse_block_argument_list
   * Base internal function for the
   * Capsule command 'parse_argument_list'.
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
   * @param array $args
   * list of sent arguments to the
   * current cli command.
   */
  function parse_block_argument_list ($argList, $index, $parser) {
    $argList = preg_replace ( '/^\\{\s*/', '',
      preg_replace ('/\s*\}$/', '', trim ($argList))
    );

    $argListArray = preg_split ('/\s*,\s*/', $argList);

    $argListCode = '';

    foreach ($argListArray as $argIndex => $arg) {
      $arg = preg_replace ('/^\$((scope|this)->)?/i', '', trim ($arg));

      if (preg_match ('/^([^=]+)/', $arg, $match)) {
        $argName = trim ($match[0]);
        $argKey = $argName;
        $argValue = trim (preg_replace (
          '/^([^=]+)=?/', '', $arg
        ));

        if (empty ($argValue)) {
          $argValue = 'null';
        }

        if (preg_match ('/^([^:]+)/', $argName, $argNameMatch)) {
          $argKey = trim ($argNameMatch [ 0 ]);
          $argName = trim (preg_replace (
            '/^([^:]+):*\s*/', '', $argName
          ));
          $argName = trim (preg_replace ('/^\$/', '', $argName));

          if (empty ($argName)) {
            $argName = $argKey;
          }
        }

        if (!preg_match ('/^([a-zA-Z0-9_]+)$/', $argName)) {
          # may Throw new Error 'something is wrong'
          continue;
        }

        # echo $argName, ' => ' , $argKey, "\n";

        #$scope->userList = !(isset ($args[0]) && ((is_array ($args[0]) && isset ($args[0][$argKey])) || (is_object ($args[0]) && isset ($args[0]->$argKey)))) ? $argValue : ((is_array ($args[0])) ? $args[0][$argKey] : $args[0]->$argKey);

        $argListCode .= "\$scope->{$argName} = !(isset (\$args ['{$argKey}'])) ? {$argValue} : \$args ['{$argKey}'];\n";

      }
    }



    return $argListCode;
  }}

  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists('Sammy\Packs\Gogue\Capsule\Extensions\parse_array_argument_list')){
  /**
   * @function parse_array_argument_list
   * Base internal function for the
   * Capsule command 'parse_argument_list'.
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
   * @param array $args
   * list of sent arguments to the
   * current cli command.
   */
  function parse_array_argument_list ($slice, $index, $parser) {

  }}


  /**
   * Make sure the command base internal function is not
   * declared in the php global scope defore creating
   * it.
   */
  if (!function_exists ('Sammy\Packs\Gogue\Capsule\Extensions\parse_argument_list')) {
  /**
   * @function parse_argument_list
   * Base internal function for the
   * Capsule command 'parse_argument_list'.
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
   *
   * -
   * @param array $args
   * list of sent arguments to the
   * current cli command.
   */
  function parse_argument_list ($argument_list, $parser) {
    $code = '';

    #exit ($argument_list);

    $usedKeys = ['\'children\''];
    $argList = preg_replace ( '/^\(/', '',
      preg_replace ('/\)$/', '', trim ($argument_list))
    );

    $argsSlices = preg_split ( '/\s*,\s*/', $argList );

    foreach ($argsSlices as $index => $slice) {
      $slice = preg_replace ('/^\$((scope|this)->)?/i', '', trim($slice));

      # ::$block-block0::
      # ::$array-block0::
      $blockRe = '/^::\=(block|array)-block[0-9]+::$/i';

      if (preg_match ($blockRe, $slice, $match)) {
        $blockType = strtolower (trim (@$match [ 1 ]));

        $func = "Sammy\Packs\Gogue\Capsule\Extensions\parse_{$blockType}_argument_list";

        $slice = $parser->decodeWholeBlocks (
          $slice
        );

        # $args[$index][$prop]
        $code .= call_user_func_array ($func,
          [ $slice, $index, $parser ]
        );

        continue;
      }

      if (preg_match ('/^([^=]+)/', $slice, $match)) {
        $spredOperatorRe = '/^(\.{3}\$?)/';
        $argName = trim ($match[0]);
        $argValue = trim (preg_replace (
          '/^([^=]+)=?/', '', $slice
        ));

        if (preg_match ($spredOperatorRe, $argName)) {
          $argName = preg_replace (
            $spredOperatorRe, '',
            trim ($argName)
          );

          $argValue = '[' . join (', ', $usedKeys) . ']';

          $code .= join ('', [
            "\$scope->{$argName} = ArrayHelper",
            "::PropsBeyond ({$argValue}, \$args);\n"
          ]);

          continue;
        }

        if (empty ($argValue)) {
          $argValue = 'null';
        }

        array_push ($usedKeys, "'{$argName}'");

        $code .= join ('', [
          "\$scope->{$argName} = !isset ",
          "(\$args['{$argName}']) ? $argValue",
          " : \$args [ '{$argName}' ];\n"
        ]);
      }
    }

    return $code;
  }}
}
