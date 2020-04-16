<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */


namespace Uloc\ApiBundle\Serializer;


use Symfony\Component\Process\Exception\RuntimeException;

class ApiRepresentationMetadata implements ApiRepresentationMetadataInterface
{
    private $group = 'public';
    private $properties = array();

    /**
     * @param $groupName
     * @return ApiRepresentationMetadata
     */
    public function setGroup($groupName)
    {
        $this->group = $groupName;
        return $this;
    }

    public function addProperties($props)
    {
        if (!is_array($props)) {
            throw new RuntimeException('Erro ao adicionar propriedades ao ApiRepresentationMetadata. Props não é um array');
        }
        $this->properties[$this->group] =
            isset($this->properties[$this->group])
                ? array_merge($this->properties[$this->group], $props)
                : $props;

        return $this;
    }

    public function build()
    {
        // TODO: Implement builder() method.
    }

    /**
     * TODO: Verificar obrigatoriedade da existência de ao menos um grupo (public)
     * @return array
     */
    public function getProperties($group = null)
    {
        if (null !== $group) {
            if ($group === 'all') {
                if (isset($this->properties[$group])) {
                    return $this->properties[$group];
                }
                return null;
            }
            if (!isset($this->properties[$group])) {
                throw new \LogicException(sprintf('Serialization group %s not exists', $group));
            }
            return $this->properties[$group];
        }
        return $this->properties;
    }


}