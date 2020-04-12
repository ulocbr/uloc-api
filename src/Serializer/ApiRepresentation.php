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

use Uloc\ApiBundle\Serializer\Encoders\JsonEncoder;

class ApiRepresentation
{

    private $context;
    /** @var  ApiRepresentationMetadata */
    private $metadata;
    /** @var  ValueNavigator */
    private $navigator;
    private $started = false;

    /**
     * @param null $context
     * @return $this
     */
    public function start($context = null)
    {
        if (true === $this->started) {
            throw new \LogicException('This representation was already started, and cannot be re-used.');
        }
        if (null !== $context && null === $this->context) {
            $this->context = $context;
        }
        $this->navigator = new ValueNavigator();
        $this->started = true;

        return $this;
    }

    /**
     * Set an manual representation for api.
     * Can be an instance of ApiRepresentation class or an anonymous function
     * @param ApiRepresentation|\Closure $res
     * @return $this
     */
    public function setMetadata($res)
    {
        if (is_callable($res)) { //anonymous function
            $metadata = new ApiRepresentationMetadata();
            call_user_func($res, $metadata);
            $this->metadata = $metadata;
            $this->context = null;
            return $this;
        }
        if (!class_exists($res)) {
            throw new \RuntimeException(
                sprintf('Class %s does not exists', $res)
            );
        }
        $this->context = new \ReflectionClass($res);
        return $this;
    }

    public function createMetadata(\ReflectionClass $context)
    {
        if ($this->metadata !== null) {
            return $this->metadata;
        }
        $metadata = new ApiRepresentationMetadata();
        $context->getMethod('loadApiRepresentation')->invoke(null, $metadata);
        return $this->metadata = $metadata;
    }

    /**
     * Find a object with loadApiRepresentation method
     * Is expected that $context is instance of an class or an array of instances of class.
     * If this not match, return null
     * @return ApiRepresentationMetadata
     */
    public function getMetadata()
    {
        if (null === $this->metadata) {
            $context = $this->context;
            $type = gettype($context);
            if ('object' === $type) {
                return $this->getReflectionMetadata($context);
            } elseif ('array' === $type) {
                if (isset($context[0])) {
                    $context = $context[0];
                    $type = gettype($context);
                    if ('object' === $type) {
                        return $this->getReflectionMetadata($context);
                    }
                }
            }
            return null;
        }
        return $this->metadata;
    }

    public function serialize($data, $format = 'json', $group = 'public')
    {

        $this->start($data)->getMetadata();

        if (null == $this->metadata || null === $data || empty($data)) {
            /*throw new \LogicException(
                sprintf('No metadata find for ApiRepresentation instance')
            );*/
            return $this->getEncoder($format)->encode($data);
        }

        /**
         * Possibilidades de um retorno para ser serializado
         * Um objeto entity com o loadApiRepresentation
         * Um array de objetos entity com o loadApiRepresentation
         * Um array com propriedades para serem serializadas
         * Um array de array de propriedades para serem serializadas
         */

        $properties = $this->metadata->getProperties($group);
        if (!empty($this->metadata->getProperties('all'))) {
            $properties = array_merge_recursive($properties, $this->metadata->getProperties('all'));
        }

        $result = $this->navigator->map($data, $properties);

        return $this->getEncoder($format)->encode($result);

    }

    /**
     * @param $context
     * @return null|ApiRepresentationMetadata
     */
    public function getReflectionMetadata($context)
    {
        if ($context instanceof \ReflectionClass) {
            $reflection = $context;
        } else {
            $class = get_class($context);
            $reflection = new \ReflectionClass($class);
        }
        if ($reflection->hasMethod('loadApiRepresentation')) {
            return $this->createMetadata($reflection);
        }
        return null;
    }

    public function getEncoder($name)
    {
        $name = strtolower($name);
        switch ($name) {
            case "json":
                return new JsonEncoder();
            default:
                return new class {
                    public function encode($data)
                    {
                        return $data;
                    }
                };

        }
    }

}