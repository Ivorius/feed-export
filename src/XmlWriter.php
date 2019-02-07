<?php

namespace Unio\FeedExport;


use Nette\SmartObject;

class XmlWriter implements IWriter
{

	use SmartObject;

	/**
	 * @var \XMLWriter
	 */
	protected $xml;


	/**
	 * Prepare write item to xml
	 * @throws \Exception
	 */
	public function writeItem(IItem $item)
	{
		$this->processArray($item->getRow());
	}

	/**
	 *
	 * @return \XMLWriter
	 */
	public function getXml()
	{
		if (!$this->xml) {
			$this->xml = new \XMLWriter();
		}
		return $this->xml;
	}


	public function resolve($val, $key = null)
	{
		if (is_array($val) || $val instanceof \Traversable) {
			$this->processArray($val, $key);
		} else {
			$this->processOnlyValue($val);
		}
	}

	public function processOnlyValue($value)
	{
		if (is_object($value) && $value instanceof XmlElement) {
			if ($value->hasAttributes()) {
				foreach ($value->getAttributes() AS $aName => $aValue) {
					$this->xml->writeAttribute($aName, $aValue);
				}
			}

			$this->resolve($value->getValue());

		} else {
			$this->xml->text($value);
		}
	}

	public function processArray($arr, $key = NULL)
	{
		if ($this->isAssoc($arr)) {
			if ($key !== NULL) {
				$this->xml->startElement($key);
			}
			foreach ($arr AS $k => $v) {
				if(is_array($v)) {
					$this->processArray($v, $k);
				} else {
					//has own key $k to write [name => '']
					$this->xml->startElement($k);
					$this->resolve($v);
					$this->xml->endElement();
				}
			}
			if ($key !== NULL) {
				$this->xml->endElement();
			}
		} else {
			//is int = use $key images[0] => ''
			foreach ($arr AS $k => $v) {
				$this->xml->startElement($key);
				$this->resolve($v);
				$this->xml->endElement();
			}
		}
	}

	public function isAssoc($arr)
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}
