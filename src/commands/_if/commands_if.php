<?php
namespace Sammy\Packs\Gogue\Capsule\Command\Ifc;

function _else ($signature, $obj = null, $options = []) {
  $phpInit = \Sammy\Packs\Gogue\Capsule\Block\Extension\usePHPInitCode ($options);
  $phpEnd = \Sammy\Packs\Gogue\Capsule\Block\Extension\usePHPEndCode ($options);

  $blockInit = \Sammy\Packs\Gogue\Capsule\Block\Extension\useBlockInit ($options);
  $blockEnd = \Sammy\Packs\Gogue\Capsule\Block\Extension\useBlockEnd ($options);

  return "\n{$phpInit}{$blockEnd}} else {{$blockInit}{$phpEnd}\n";
}

function _elsif ($signature, $obj = null, $options = []) {
  $signatureBody = trim (preg_replace ('/\s{2,}/', ' ',
    $obj->decodeBlocks ( $signature )
  ));

  $phpInit = \Sammy\Packs\Gogue\Capsule\Block\Extension\usePHPInitCode ($options);
  $phpEnd = \Sammy\Packs\Gogue\Capsule\Block\Extension\usePHPEndCode ($options);

  $blockInit = \Sammy\Packs\Gogue\Capsule\Block\Extension\useBlockInit ($options);
  $blockEnd = \Sammy\Packs\Gogue\Capsule\Block\Extension\useBlockEnd ($options);

  if ( empty ($signatureBody) ) {
    $signatureBody = 'null';
  }

  return "\n{$phpInit}{$blockEnd}} elseif ($signatureBody) {{$blockInit}{$phpEnd}\n";
}

function _elseif () {
  return call_user_func_array ('Sammy\Packs\Gogue\Capsule\Command\Ifc\_elsif',
    func_get_args ()
  );
}

function _elif () {
  return call_user_func_array ('Sammy\Packs\Gogue\Capsule\Command\Ifc\_elsif',
    func_get_args ()
  );
}
