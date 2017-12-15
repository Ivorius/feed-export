<?php

namespace Unio\FeedExport\Zbozi;


use Unio\FeedExport\IItem;
use Unio\FeedExport\InvalidArgumentException;
use Unio\FeedExport\Item;

class ZboziItem extends Item implements IItem
{

	/** @var array */
	protected $required = array(
		'ITEM_ID',
		'PRODUCTNAME',
		'DESCRIPTION',
		'URL',
		'PRICE_VAT',
		'DELIVERY_DATE'
	);


	//required

	public function setName($name)
	{
		$this->row['PRODUCTNAME'] = $name;
	}

	public function getName()
	{
		return $this->row['PRODUCTNAME'];
	}

	public function setID($id)
	{
		if (preg_match('/[^a-z_\-0-9]/i', $id)) {
			throw new InvalidArgumentException("ID " . $id . " must be alphanumeric.");
		}
		$this->row['ITEM_ID'] = $id;
	}

	public function setUrl($url)
	{
		if(\Nette\Utils\Validators::isUrl($url) === false) {
			throw new InvalidArgumentException('Is not valid URL: ' . $url);
		}
		$this->row['URL'] = $url;
	}

	public function getUrl()
	{
		return $this->row['URL'];
	}

	public function setDescription($desc)
	{
		$this->row['DESCRIPTION'] = strip_tags($desc);
	}

	public function setPrice($price)
	{
		$this->row['PRICE_VAT'] = round($price, 2);
	}

	public function setDeliveryDate($delivery)
	{
		$this->row['DELIVERY_DATE'] = $delivery;
	}


	//recommended

	public function setCategoryText($category)
	{
		$this->row['CATEGORYTEXT'] = $category;
	}

	public function setImage($image)
	{
		$this->row['IMGURL'][] = $image;
	}

	public function setEan($ean)
	{
		$this->row['EAN'] = $ean;
	}

	public function setProductNo($no)
	{
		$this->row['PRODUCTNO'] = $no;
	}

	public function setItemgroupId($group)
	{
		$this->row['ITEMGROUP_ID'] = $group;
	}

	public function setManufacturer($man)
	{
		$this->row['MANUFACTURER'] = $man;
	}

	public function setErotic($boolean)
	{
		$this->row['EROTIC'] = (int) $boolean;
	}


	// non-required

	public function setBrand($brand)
	{
		$this->row['BRAND'] = $brand;
	}

	// název nabídky ve výsledcích vyhledávání, rozvětvená značka PRODUCTNAME včetně přívlastků.
	public function setProduct($product)
	{
		$this->row['PRODUCT'] = $product;
	}

	public function setExtraMessage($msg)
	{
		if ($msg) {
			$this->row['EXTRA_MESSAGE'][] = $msg;
		}
	}

	public function setShopDepots($depot)
	{
		if ($depot) {
			$this->row['SHOP_DEPOTS'][] = $depot;
		}
	}

	public function setVisibility($visibility = 1)
	{
		$this->row['VISIBILITY'] = (int) $visibility;
	}

	public function setMaxCPC($cpc = NULL)
	{
		if ($cpc) {
			if ($cpc < 1 || $cpc > 500) {
				throw new InvalidArgumentException('MAX CPC must be between 1 AND 500 Kč');
			} elseif (!is_numeric($cpc)) {
				throw new InvalidArgumentException('MAX CPC must be numeric');
			}
			$this->row['MAX_CPC'] = $cpc;
		}
	}

	public function setMaxCPCSearch($cpc = NULL)
	{
		if ($cpc) {
			if ($cpc < 1 || $cpc > 500) {
				throw new InvalidArgumentException('MAX CPC SEARCH must be between 1 AND 500 Kč');
			} elseif (!is_numeric($cpc)) {
				throw new InvalidArgumentException('MAX CPC SEARCH must be numeric');
			}
			$this->row['MAX_CPC_SEARCH'] = $cpc;
		}
	}

	public function addParam($name, $value, $unit = NULL)
	{
		$this->row["PARAM"][] = [
			"PARAM_NAME" => $name,
			"VAL" => $value,
			"UNIT" => $unit,
		];
	}

	public function unsetParam()
	{
		unset($this->row['PARAM']);
	}


}