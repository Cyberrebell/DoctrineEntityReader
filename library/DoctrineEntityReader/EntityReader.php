<?php

namespace DoctrineEntityReader;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Creates profiles about entity Properties by reading the doctrine annotations
 *
 * @author   Cyberrebell <chainsaw75@web.de>
 */
class EntityReader
{
    /**
     * Returns Properties determined by Entity-Namespace
     * 
     * @param string $entityNamespace Entity-Namespace
     * @return array:\DoctrineEntityReader\Property
     */
    public static function getProperties($entityNamespace)
    {
        $reflectionClass = new \ReflectionClass($entityNamespace);
        $reflectionProperties = $reflectionClass->getProperties();
        
        $properties = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            $property = self::createProperty($reflectionProperty);
            if ($property) {
                $properties[$property->getName()] = $property;
            }
        }
        return $properties;
    }
    
    /**
     * Returns created Property-Object
     * gets Information from Reflection-Property which contains Doctrine-Annotations
     * 
     * @param \ReflectionProperty $reflectionProperty Reflection-Property of Entity
     * 
     * @throws \Exception
     * @return boolean|\DoctrineEntityReader\Property
     */
    protected static function createProperty(\ReflectionProperty $reflectionProperty)
    {
        $property = new Property();
        $property->setName($reflectionProperty->getName());
        
        $annotationReader = new AnnotationReader();
        $annotations = $annotationReader->getPropertyAnnotations($reflectionProperty);
        foreach ($annotations as $annotation) {
            $annotationClassName = get_class($annotation);
            if ($annotationClassName == 'Doctrine\ORM\Mapping\Column' || $annotationClassName == 'Doctrine\ORM\Mapping\Id') {
                $property->setAnnotation($annotation);
                $property->setType(Property::PROPERTY_TYPE_COLUMN);
            } elseif ($annotationClassName == 'Doctrine\ORM\Mapping\ManyToOne' || $annotationClassName == 'Doctrine\ORM\Mapping\OneToOne') {
                $property->setAnnotation($annotation);
                $property->setType(Property::PROPERTY_TYPE_REF_ONE);
                $property->setTargetEntity($annotation->targetEntity);
            } elseif ($annotationClassName == 'Doctrine\ORM\Mapping\ManyToMany' || $annotationClassName == 'Doctrine\ORM\Mapping\OneToMany') {
                $property->setAnnotation($annotation);
                $property->setType(Property::PROPERTY_TYPE_REF_MANY);
                $property->setTargetEntity($annotation->targetEntity);
            }
        }
        
        if ($property->getType() == -1) {
            throw new \Exception(
                'Entity "' . $reflectionProperty->getDeclaringClass()->getName()
                . '": defining annotation is missing at property "' . $property->getName() . '"!'
            );
        }
        
        return $property;
    }
}
