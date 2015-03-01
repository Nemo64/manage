<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 01.03.15
 * Time: 20:54
 */

namespace PersonBundle\SerializationHandler\PersonHandler;


use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\Serializer\JsonDeserializationVisitor;
use PersonBundle\Entity\Person;

/**
 * @Service()
 * @Tag("jms_serializer.handler", attributes={
 *  "type": "PersonBundle\Entity\Person\Name",
 *  "direction": "deserialization",
 *  "format": "json",
 *  "method": "deserializeName"
 * })
 */
class NameHandler
{
    public function deserializeName(JsonDeserializationVisitor $visitor, $data, array $type)
    {
        $last = isset($data['last']) ? $data['last'] : null;
        $first = isset($data['first']) ? $data['first'] : null;
        $second = isset($data['second']) ? $data['second'] : null;
        $nickname = isset($data['nickname']) ? $data['nickname'] : null;
        $prefix = isset($data['prefix']) ? $data['prefix'] : null;
        $suffix = isset($data['suffix']) ? $data['suffix'] : null;
        $name = new Person\Name($last, $first, $second, $nickname, $prefix, $suffix);
        return $name;
    }
}