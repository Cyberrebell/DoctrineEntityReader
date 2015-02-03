<?php

namespace DoctrineEntityReader;

/**
 * Container Class to store required information the EntityReader collected
 * 
 * @author Cyberrebell <chainsaw75@web.de>
 */
class Property
{
	const PROPERTY_TYPE_ID = 0;
	const PROPERTY_TYPE_COLUMN = 1;
	const PROPERTY_TYPE_REF_ONE = 2;
	const PROPERTY_TYPE_REF_MANY = 3;
	
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
	function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Returns the Property-Name
	 * 
	 * @return string
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 * Set the annotation
	 * stored for extended information about property
	 *
	 * @param string $annotation Defining Annotation of Entity-Property
	 * @return null
	 */
	function setAnnotation($annotation) {
		$this->annotation = $annotation;
	}
	
	/**
	 * Returns the annotation
	 * only for extended information about property
	 * 
	 * @return string
	 */
	function getAnnotation() {
		return $this->annotation;
	}
	
	/**
	 * Set the type
	 * The constants of Property-Class are possible Types
	 *
	 * @param string $type Class-Constant
	 * @return null
	 */
	function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Returns the Property-Type
	 * The constants of Property-Class are possible Types
	 *
	 * @return string
	 */
	function getType() {
		return $this->type;
	}
	
	/**
	 * Set the target Entity-Namespace
	 * (is only set if Type is PROPERTY_TYPE_REF_ONE or PROPERTY_TYPE_REF_MANY)
	 *
	 * @param string $targetEntity Entity-Namespace
	 * @return null
	 */
	function setTargetEntity($targetEntity) {
		$this->targetEntity = $targetEntity;
	}
	
	/**
	 * Returns the target Entity-Namespace
	 * (is only set if Type is PROPERTY_TYPE_REF_ONE or PROPERTY_TYPE_REF_MANY)
	 * 
	 * @return string
	 */
	function getTargetEntity() {
		return $this->targetEntity;
	}
	
	function ensurePrintableValue($value, $formMode = false) {
		switch ($this->type) {
			case self::PROPERTY_TYPE_COLUMN:
				return $this->ensurePrintableColumn($value, $formMode);
			case self::PROPERTY_TYPE_REF_ONE:
				return $this->ensurePrintableRefOne($value, $formMode);
			case self::PROPERTY_TYPE_REF_MANY:
				return $this->ensurePrintableRefMany($value, $formMode);
			default:
				return $value;
		}
	}
	
	protected function ensurePrintableColumn($value, $formMode) {
		if (is_string($value) || is_int($value)) {
			return $value;
		} else if (is_object($value)) {
			if ($value instanceof \DateTime) {
				return $value->format('d.m.Y H:i:s');
			} else {
				return '';
			}
		} else {	//bool value
			if ($formMode) {
				return $value;
			} else {
				if ($value) {
					return '+';
				} else {
					return '-';
				}
			}
		}
	}
	
	protected function ensurePrintableRefOne($value, $formMode) {
		if ($formMode) {
			if ($value) {
				$value = $value->getId();
			} else {
				$value = 0;
			}
		} else {
			if ($value) {
				$targetEntity = $this->getTargetEntity();
				$targetPropertsGetter = 'get' . ucfirst($targetEntity::DISPLAY_NAME_PROPERTY);
				$value = $this->ensurePrintableColumn($value->$targetPropertsGetter(), false);
			} else {
				$value = '-';
			}
		}
		return $value;
	}
	
	protected function ensurePrintableRefMany($value, $formMode) {
		if ($formMode) {
			$multiValues = [];
			if ($value) {
				foreach ($value as $refEntity) {
					$multiValues[] = $refEntity->getId();
				}
			}
			return $multiValues;
		} else {
			$targetEntity = $this->getTargetEntity();
			$targetPropertsGetter = 'get' . ucfirst($targetEntity::DISPLAY_NAME_PROPERTY);
			if (count($value) > 0) {
				$listString = '';
				foreach ($value as $targetEntity) {
					$targetEntityStr = $targetEntity->$targetPropertsGetter();
					$listString .= $this->ensurePrintableColumn($targetEntityStr, false) . ', ';
				}
				return substr($listString, 0, -2);
			} else {
				return '-';
			}
		}
	}
}
