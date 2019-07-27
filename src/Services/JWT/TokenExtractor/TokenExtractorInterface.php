<?php

namespace Uloc\ApiBundle\Services\JWT\TokenExtractor;

use Symfony\Component\HttpFoundation\Request;

/**
 * TokenExtractorInterface.
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
interface TokenExtractorInterface
{
    /**
     * @param Request $request
     *
     * @return string|false
     */
    public function extract(Request $request);
}
