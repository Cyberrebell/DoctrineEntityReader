<?php

namespace DoctrineEntityReader;

/**
 * Container Class to store required information the EntityReader collected
 * 
 * @author   Cyberrebell <cyberrebell@web.de>
 */
class Property
{
    const PROPERTY_TYPE_COLUMN = 0;
    const PROPERTY_TYPE_REF_ONE = 1;
    const PROPERTY_TYPE_REF_MANY = 2;
    
    protected $name;
    protected $annotation;
    protected $type = -1;
    protected $targetEntity;
    
    /**
     * Set the Name of Entity-Property
     * 
     * @param string $name Property-Name
     * @return null
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Returns the Property-Name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set the annotation
     * stored for extended information about property
     *
     * @param string $annotation Defining Annotation of Entity-Property
     * @return null
     */
    public function setAnnotation($annotation)
    {
        $this->annotation = $annotation;
    }
    
    /**
     * Returns the annotation
     * only for extended information about property
     * 
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
    
    /**
     * Set the type
     * The constants of Property-Class are possible Types
     *
     * @param string $type Class-Constant
     * @return null
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Returns the Property-Type
     * The constants of Property-Class are possible Types
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set the target Entity-Namespace
     * (is only set if Type is PROPERTY_TYPE_TOONE or PROPERTY_TYPE_TOMANY)
     *
     * @param string $targetEntity Entity-Namespace
     * @return null
     */
    public function setTargetEntity($targetEntity)
    {
        $this->targetEntity = $targetEntity;
    }
    
    /**
     * Returns the target Entity-Namespace
     * (is only set if Type is PROPERTY_TYPE_TOONE or PROPERTY_TYPE_TOMANY)
     * 
     * @return string
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }
}
