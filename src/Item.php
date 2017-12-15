<?php

namespace Unio\FeedExport;

use Nette\Object;

abstract class Item extends Object implements IItem
{
	/** @var array */
	protected $row = [];

	/** @var ProductEntity */
	protected $product;

	/** @var array */
	protected $required = array();


	/**
	 * Check if required fields are set up.
	 * @throws \Exception
	 */
	public function checkRequired()
	{
		$diff = array_diff_key(array_flip($this->required), $this->row);
		if($diff)
			throw new InvalidArgumentException('These elements are required and have not been set: ' . implode(array_keys($diff), ","));

		$instersect = array_intersect_key($this->row, array_flip($this->required));
		$filtered = array_filter($instersect, function($value) {
			if($value === "" || $value === false) {
				return true;
			}
		});
		if($filtered)
			throw new InvalidArgumentException('These elements can not be empty: ' . implode(array_keys($filtered), ","));
	}


	/**
	 * @return array
	 * @throws \Exception
	 */
	public function getRow()
	{
		$this->checkRequired();
		return $this->row;
	}

}