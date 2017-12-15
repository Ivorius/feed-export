<?php

/**
 * GoogleItem
 *
 * @author Ivo
 */

namespace Unio\FeedExport\Google;


use Unio\FeedExport\IItem;
use Unio\FeedExport\InvalidArgumentException;
use Unio\FeedExport\Item;

class GoogleItem extends Item implements IItem
{

	/** @var array */
	protected $required = [
		'g:id',
		'g:title',
		'description',
		'g:product_type',
		'link',
		'g:image_link',
		'g:price',
		'g:google_product_category',
		'g:availability',
		'g:condition',
		'g:brand',
	];


	/**
	 * Dynamic set new element
	 * @param string $key
	 * @param string $value
	 * @throws \InvalidArgumentException
	 */
	public function setElement($key, $value)
	{
		if (isset($this->row[$key]))
			throw new InvalidArgumentException('You must set element in right method: set' . ucfirst($key) . '()');
		$this->row[$key] = $value;
	}

	public function setDescription($desc)
	{
		$this->row['description'] = strip_tags($desc);
	}

	public function setCategory($category)
	{
		$this->row['g:product_type'] = $category;
	}


	public function setProductNo($no)
	{
		$this->row['g:mpn'] = $no;
	}

	public function setEan($ean)
	{
		$this->row['g:gtin'] = $ean;
	}

	public function setItemgroupId($id)
	{
		$this->row['g:item_group_id'] = $id;
	}

	public function setQuantity($quantity)
	{
		$this->row['g:quantity'] = $quantity;
	}

	public function setId($id)
	{
		$this->row['g:id'] = $id;
	}

	public function setName($name)
	{
		$this->row['g:title'] = $name;
	}

	public function getName()
	{
		return $this->row['g:title'];
	}

	public function setUrl($url)
	{
		if (\Nette\Utils\Validators::isUrl($url) === FALSE) {
			throw new InvalidArgumentException('Is not valid URL: ' . $url);
		}
		$this->row['link'] = $url;
	}

	public function getUrl()
	{
		return $this->row['link'];
	}

	public function setPrice($price, $currency)
	{
		$this->row['g:price'] = round($price, 2) . " " . strtoupper($currency);
	}

	public function setSalePrice($price, $currency)
	{
		$this->row['g:sale_price'] = round($price, 2) . " " .  strtoupper($currency);
	}


	public function setCategoryText($category)
	{
		$this->row['g:google_product_category'] = $category;
	}

	public function setImage($image)
	{
		$this->row['g:image_link'] = $image;
	}

	public function setAdditionalImage(\Traversable $images)
	{
		$this->row['g:additional_image_link'] = $images;
	}

	public function addAdditionalImage($image)
	{
		$this->row['g:additional_image_link'][] = $image;
	}

	public function identifierExists()
	{
		if (!$this->row['g:gtin'] && !$this->row['g:mpn']) {
			$this->row['g:identifier_exists'] = FALSE;
			return FALSE;
		}
		return TRUE;
	}

	public function setCondition($cond = "new")
	{
		if (!$cond) {
			$cond = "new";
		}

		$possible = array("new", "used", "refurbished");
		if (!in_array($cond, $possible)) {
			throw new InvalidArgumentException("Condition must be one of: " . implode(",",
					$possible) . " but now is: " . $cond);
		}
		$this->row['g:condition'] = $cond;
	}

	public function setAvailability($cond)
	{
		$possible = array("in stock", "out of stock", "preorder");
		if (!in_array($cond, $possible)) {
			throw new InvalidArgumentException("Availability must be one of: " . implode(",",
					$possible) . " but now is: " . $cond);
		}
		$this->row['g:availability'] = $cond;
	}

	public function setBrand($brand)
	{
		$this->row['g:brand'] = $brand;
	}


	public function setErotic($boolean)
	{
		$this->row['g:adult'] = (int) $boolean;
	}

}
