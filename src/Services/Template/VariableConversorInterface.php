<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Services\Template;

/**
 * Interface VariableConversorInterface
 * @package Uloc\ApiBundle\Services\Template
 */

interface VariableConversorInterface
{

    public function getVariables(): array;

    public function setData($data): self;

    public static function getClass(): string;

    public function setOm($om): self;

}