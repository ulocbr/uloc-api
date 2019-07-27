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

use Symfony\Component\PropertyAccess\PropertyAccess;

class ValueNavigator
{

    private $accessor;
    private $oneElement = null; //informa que a navegação irá retornar somente um elemento
    private $rootNode = null; //verifica se o nó root já foi percorrido
    private $start = null; //verifica se a navegação iniciou
    private $end = false; //verifica se a navegação terminou

    /**
     * Possibilidades de um retorno para ser serializado
     * CASO1: Um objeto
     * CASO2: Um array de objetos
     * CASO3: Um array com propriedades
     * CASO4: Um array de array de propriedades
     */

    public function map($data, $properties)
    {
        $this->checkRootNode();
        if (null === $this->start) {
            $this->start = true;
            $rootNode = true;
        }
        if (null === $data) return;
        $result = [];
        if (is_array($data)) {
            $result = $this->mapArray($data, $properties);
        } elseif (is_object($data)) {
            $result = $this->mapObject($data, $properties);
        }

        if (isset($rootNode)) { //significa que o node root navegou por todos os elementos inferiores
            $this->end = true;
            if (null !== $this->oneElement) { // significa que o o elemento navegado foi um único objeto ou um único array de propriedades, não uma lista
                if (isset($result[0]) && count($result) < 2) {
                    return $result[0];
                }
            }
        }
        return $result;
    }

    public function mapObject($data, $properties)
    {
        $result = [];
        if (method_exists($data, 'getValues')) {
            $values = $data->getValues();
            foreach ($values as $item) {
                $result[] = $this->recursiveMap($item, $properties); //CASO2 e CASO 4
            }
            return $result;
        } elseif ($data instanceof \ArrayObject || $data instanceof \ArrayIterator || $data instanceof \IteratorAggregate) {
            foreach ($data as $item) {
                $result[] = $this->recursiveMap($item, $properties); //CASO2 e CASO 4
            }
            return $result;
        } else {
            $this->defineRootNode(true);
            if (is_array($properties)) {
                $result = $this->recursiveMap($data, $properties); //CASO1
            } else {
                $result = $this->readProperty($data, $properties); //CASO1
            }
        }
        return $result;
    }

    public function mapArray($data, $properties)
    {
        $result = [];
        if (isset($data[0])) { //is array keys
            if (is_object($data[0]) || is_array($data[0])) {
                foreach ($data as $item) {
                    $result[] = $this->recursiveMap($item, $properties); //CASO2 e CASO 4
                }
            } else {
                throw new \RuntimeException('Array key can contain an object or array in value.');
            }
        } else { // is associative array
            $this->defineRootNode(true);
            if (is_array($properties)) {
                $result = $this->recursiveMap($data, $properties); //CASO3
            } else {
                $result = $this->readProperty($data, $properties); //CASO3
            }
        }
        return $result;
    }

    public function recursiveMap($data, $properties)
    {
        $this->defineRootNode();
        $root = [];
        foreach ($properties as $key => $property) {
            $mount = [];
            $this->checkValidProperty($key, $property);
            switch ($type = gettype($property)) {
                case "array":
                    $parseProperty = $this->parseProperty($key);
                    $mount[$parseProperty['alias']] = $this->map($this->readProperty($data, $parseProperty['property']), $property);
                    break;
                case "string":
                    $parseProperty = $this->parseProperty($property);
                    $mount[$parseProperty['alias']] = $this->map($data, $parseProperty['property']);
                    break;
                default:
                    throw new \LogicException(sprintf("Property must be an array or string. %s given", gettype($property)));
            }
            $root += $mount;
        }
        return $root;
    }

    public function parseProperty($property)
    {
        preg_match('/([\w]+)\s+(as\s+)?([\w]+)?/', $property, $matches);
        if (count($matches) === 0) {
            return array('property' => $property, 'alias' => $property);
        }
        return array('property' => $matches[1], 'alias' => count($matches) > 2 ? $matches[3] : $matches[1]);
    }

    public function readProperty($data, $propertyPath)
    {
        if ($this->accessor === null) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }

        if ($data === null) {
            return null;
        }

        if (is_array($data)) {
            $propertyPath = "[$propertyPath]";
        }
        return $this->accessor->getValue($data, $propertyPath);
    }

    public function checkValidProperty($key, $property)
    {
        $typeKey = gettype($key);
        $typeProperty = gettype($property);
        if ($typeKey === 'integer' && $typeProperty !== 'string') {
            throw new \LogicException(sprintf("Property of representation must be an string, '%s' given", (string)$typeProperty));
        }

        if ($typeKey === 'string' && $typeProperty !== 'array') {
            throw new \LogicException(sprintf("Property of representation must be array when key is an string, '%s' given", (string)$typeProperty));
        }
    }

    public function checkRootNode()
    {
        if (null === $this->rootNode) {
            $this->rootNode = true;
        }
    }

    public function defineRootNode($isOneElement = false)
    {
        if (true === $this->rootNode) {
            if ($isOneElement) $this->oneElement = true;
            $this->rootNode = false;
        }
    }

}