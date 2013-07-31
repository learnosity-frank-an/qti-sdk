<?php

namespace qtism\data;

use \ReflectionClass;
use \InvalidArgumentException;
use \OutOfRangeException;
use \SplObserver;
use \SplSubject;

/**
 * This extension of QtiComponentCollection can retrieve items it contains
 * by identifier.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
abstract class QtiIdentifiableCollection extends QtiComponentCollection implements SplObserver {
	
	/**
	 * Create a new QtiIdentifiableCollection object.
	 * 
	 * @param array $array (optional) An array of QtiIdentifiable objects to populate the new collection.
	 * @throws InvalidArgumentException If a value of $array is not a QtiIdentifiable object.
	 */
	public function __construct(array $array = array()) {
		foreach ($array as $a) {
			$this->offsetSet(null, $a);
		}
	}
	
	protected function checkType($value) {
		parent::checkType($value);
		
		if (!$value instanceof QtiIdentifiable) {
			$msg = "The QtiIdentifiable class only accepts to store QtiIdentifiable objects.";
			throw new InvalidArgumentException($msg);
		}
	}
	
	/**
	 * Whether a QtiIdentifiable object with 'identifier' = $offset is in
	 * the collection.
	 * 
	 * @return boolean
	 * @throws OutOfRangeException If the request $offset is not a string or is empty.
	 */
	public function offsetExists($offset) {
		if (gettype($offset) !== 'string' && empty($offset) === false) {
			$msg = "The requested offset must be a string.";
			throw new OutOfRangeException($msg);
		}
		
		$data = &$this->getDataPlaceHolder();
		return isset($data[$offset]);
	}
	
	/**
	 * Retrieve a QtiIdentifiable object from the collection.
	 * 
	 * @return QtiIdentifiable|null The requested QtiIdentifiable object or null if no object with 'identifier' = $offset is found.
	 * @throws OutOfRangeException If the request $offset is not a string or is empty.
	 */
	public function offsetGet($offset) {
		if (gettype($offset) !== 'string') {
			$msg = "The requested offset must be a non-empty string.";
			throw new OutOfRangeException($msg);
		}
		
		$returnValue = null;
		$data = &$this->getDataPlaceHolder();
		
		if (isset($data[$offset]) === true) {
			$returnValue = $data[$offset];
		}
		
		return $returnValue;
	}
	
	/**
	 * Put a QtiIdentifiable object into the collection. No specific offset must be
	 * set because the key associated to $value is always its 'identifier' attribute's
	 * value.
	 * 
	 * @param null $offset
	 * @param QtiIdentifiable $value A QtiIdentifiable object.
	 * @throws InvalidArgumentException If $value is not a QtiIdentifiable object.
	 * @throws OutOfRangeException If the offset is not null.
	 */
	public function offsetSet($offset, $value) {
		$this->checkType($value);
		
		if (is_null($offset) === false) {
			$msg = "No specfic offset can be set in a QtiIdentifiableCollection. ";
			$msg.= "The offset is always infered from the 'identifier' attribute of ";
			$msg.= "the given QtiIdentifier object.";
			
			throw new OutOfRangeException($msg);
		}
		
		$data = &$this->getDataPlaceHolder();
		$data[$value->getIdentifier()] = $value;
		
		// Listen to events thrown by this $value.
		$value->attach($this);
	}
	
	/**
	 * Attach a given QtiIdentifiable $object to the collection. Its key in the collection
	 * will be the value value of its 'identifier' attribute.
	 * 
	 * This method overrides AbstractCollection::attach.
	 *
	 * @param QtiIdentifiable $object A QtiIdentifiable object.
	 * @throws InvalidArgumentException If $object is not a QtiIdentifiable object.
	 */
	public function attach($object) {
		$this->offsetSet(null, $object);
	}
	
	/**
	 * Remove a QTIIdentifiable object from the collection that has its
	 * 'identifier' attribute equals to $offset.
	 * 
	 * @throws OutOfRangeException If $offset is not a string.
	 */
	public function offsetUnset($offset) {
		if (gettype($offset) === 'string') {
			$data = &$this->getDataPlaceHolder();
			if (isset($data[$offset])) {
				unset($data[$offset]);
			}
		}
		else {
			$msg = "The requested offset must be a non-empty string.";
			throw new OutOfRangeException($msg);
		}
	}
	
	/**
	 * Implementation of SplObserver::update.
	 * 
	 * @param SplSubject $subject
	 */
	public function update(SplSubject $subject) {
		// -- case 1 (QtiIdentifiable)
		// If it is a QtiIdentifiable, it has changed its identifier.
		$data = &$this->getDataPlaceHolder();
		foreach (array_keys($data) as $k) {
			if ($data[$k] === $subject && $k !== $subject->getIdentifier()) {
				unset($data[$k]);
				$this->offsetSet(null, $subject);
			}
		}
	}
}