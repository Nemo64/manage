<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 20:46
 */

namespace Nemo64\EntityExtraBundle\Type;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;
use Nemo64\EntityExtraBundle\Type\WeakRelationType\WeakRelationDeserializeEventArgs;
use Nemo64\EntityExtraBundle\Type\WeakRelationType\WeakRelationSerializeEventArgs;

class WeakRelationType extends JsonArrayType
{
    const TYPE = 'weak_relation';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $eventArgs = new WeakRelationSerializeEventArgs($value);
        $platform->getEventManager()->dispatchEvent('weakRelationSerialize', $eventArgs);

        $value = array(
            'class' => $eventArgs->getClassName(),
            'identifier' => $eventArgs->getIdentifier()
        );

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);

        $eventArgs = new WeakRelationDeserializeEventArgs($value['class'], $value['identifier']);
        $platform->getEventManager()->dispatchEvent('weakRelationDeserialize', $eventArgs);

        return $eventArgs->getEntity();
    }

    public function getName()
    {
        return self::TYPE;
    }
}