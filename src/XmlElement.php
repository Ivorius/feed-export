<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport;

/**
 * For elemet with attributes
 * Class XmlElement
 * @package Unio\FeedExport
 */
class XmlElement
{

	/**
	 * @var array
	 */
	private $attributes = [];

	/**
	 * @var mixed
	 */
	private $value;


	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}


	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function addAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}


	/**
	 * @return bool
	 */
	public function hasAttributes()
	{
		return (bool) count($this->attributes);
	}

}