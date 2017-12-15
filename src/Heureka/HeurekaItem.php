<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Heureka;


use Unio\FeedExport\IItem;
use Unio\FeedExport\InvalidArgumentException;
use Unio\FeedExport\Item;

class HeurekaItem extends Item implements IItem
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


	public function setId($id)
	{
		if (preg_match('/[^a-z_\-0-9]/i', $id)) {
			throw new InvalidArgumentException("ID " . $id . " must be alphanumeric.");
		}
		$this->row['ITEM_ID'] = $id;
	}

	public function setName($name)
	{
		$this->row['PRODUCTNAME'] = $name;
	}

	public function getName()
	{
		return $this->row['PRODUCTNAME'];
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
		$this->row['IMGURL'] = $image;
	}

	public function addImageAlternate($image)
	{
		$this->row['IMGURL_ALTERNATE'][] = $image;
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

	// název nabídky ve výsledcích vyhledávání, rozvětvená značka PRODUCTNAME včetně přívlastků.
	public function setProduct($product)
	{
		$this->row['PRODUCT'] = $product;
	}

	public function setMaxCPC($cpc = NULL)
	{
		if ($cpc) {
			if ($cpc < 1 || $cpc > 1000) {
				throw new InvalidArgumentException('MAX CPC must be between 1 AND 1000 Kč');
			} elseif (!is_numeric($cpc)) {
				throw new InvalidArgumentException('MAX CPC must be numeric');
			}
			$this->row['HEUREKA_CPC'] = $cpc;
		}
	}

	public function addParam($name, $value, $unit = NULL)
	{
		$this->row["PARAM"][] = array(
			"PARAM_NAME" => $name,
			"VAL" => $value,
			"UNIT" => $unit,
		);
	}

	public function unsetParam()
	{
		unset($this->row['PARAM']);
	}


	/**
	 * https://sluzby.heureka.cz/napoveda/xml-feed/#DELIVERY
	 */
	public function addDelivery($name, $price = NULL, $price_cod = NULL)
	{
		$arr['DELIVERY_ID'] = $name;

		if (!is_null($price)) {
			$arr['DELIVERY_PRICE'] = $price;
		}

		if (!is_null($price_cod)) {
			$arr['DELIVERY_PRICE_COD'] = $price_cod;
		}

		$this->row['DELIVERY'][] = $arr;
	}


	public function setVideo($video)
	{
		if(stripos($video, 'youtube.com') !== false) {
			$this->row['VIDEO_URL'] = $video;
		}
	}

}