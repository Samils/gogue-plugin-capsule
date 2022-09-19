<?php
/**
 * @version 2.0
 * @author Sammy
 *
 * @keywords Samils, ils, php framework
 * -----------------
 * @package Sammy\Packs\Gogue\Transpiler\Capsule\Block\DataGetter
 * - Autoload, application dependencies
 */
namespace Sammy\Packs\Gogue\Transpiler\Capsule\Block\DataGetter {
    /**
     * Make sure the module base internal class is not
     * declared in the php global scope defore creating
     * it.
     */
    if (!trait_exists('Sammy\Packs\Gogue\Transpiler\Capsule\Block\DataGetter\Base')){
    /**
     * @trait Base
     * Base internal trait for the
     * Gogue\Transpiler\Capsule\Block\DataGetter module.
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
         * [getBlockDatas description]
         * @param  int $point
         * @param  array $currentBlock
         * @return string
         */
        function getBlockDatas ($point, $currentBlock) {
            $idn = !$this->capsuleConfigs['commandIdentifier'] ? '' : (
                '@'
            );

            $capsuleBlocks = $this->getCapsuleBlocks ();

            $expectedEnds = 1;
            # Map whole the code chras
            for ( $i = ($point + 1); $i < strlen($this->code); $i++ ) {
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

                        #$expectedEnds++;

                        $blockDatas = $this->getBlockDatas (
                            $i, $block
                        );


                        #print_r ($blockDatas);
                        $this->readCapsuleBlock ($blockDatas);
                    }
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
                        $endPoint = ($i - $point) + strlen($end) + 0;
                        $blockContent = substr ($this->code, $point, $endPoint);

                        return $this->blockFactory ($point, $endPoint, $currentBlock, $blockContent);

                    } else {
                        $expectedEnds--;
                    }
                }
            }

            # Considere the end of the file [code]
            # as the end of the current capsule
            # block.
            $endPoint = strlen($this->code);

            $blockContent = substr ($this->code, $point, $endPoint) . (
                "\n" . $end
            );

            return $this->blockFactory ($point, $endPoint, $currentBlock, $blockContent);
        }
    }}
}
