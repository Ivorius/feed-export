<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Heureka;


use Unio\FeedExport\IItem;
use Unio\FeedExport\InvalidArgumentException;
use Unio\FeedExport\Item;
use Unio\FeedExport\XmlElement;
use Nette\Utils\DateTime;

class AvailItem extends Item implements IItem
{

	protected $row;
	private $id;
	private $deadline;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param int $qty
	 */
	public function setQty($qty)
	{
		if (!is_numeric($qty)) {
			throw new InvalidArgumentException('Stock quantity must be numeric and greater than zero');
		}

		if ($qty > 0) {
			$this->row["stock_quantity"] = $qty;
		}
	}


	/**
	 * Za kolik dni bude doruceno
	 * @param int $days
	 */
	public function setDaysToDelivery($days)
	{
		if (!is_numeric($days) || $days < 0 || $days > 7) {
			throw new InvalidArgumentException('Delivery days must be integer between 0 and 7. Now is ' . $days);
		}

		$date = $this->getDeadline()->modifyClone("+$days days");

		$element = new XmlElement();
		$element->addAttribute('orderDeadline', $this->getDeadlineTime());
		$element->setValue($date->format('Y-m-d H:i'));

		$this->row['delivery_time'] = $element;
	}


	/**
	 * @return DateTime
	 */
	public function getDeadline()
	{
		if (!isset($this->deadline)) {
			$this->deadline = new DateTime;
			$this->deadline->setTime(12, 00);
			if (new DateTime > $this->deadline) {
				$this->deadline->modify('+1 day');
			}
		}

		return $this->deadline;
	}


	public function setDeadline(DateTime $dateTime)
	{
		$this->deadline = $dateTime;
	}


	public function getDeadlineTime()
	{
		return $this->getDeadline()->format('Y-m-d H:i');
	}

	/**
	 * @param $depot
	 * @param int $days
	 */
	public function setDepot($depot, $days, $qty = null)
	{
		$element = new XmlElement();
		$element->addAttribute('id', $depot);
		$arr = [];
		$arr['pickup_time'] = $this->getPickupDays($days);
		if($qty) {
			$arr['stock_quantity'] = $qty;
		}
		$element->setValue($arr);

		$this->row["depot"] = $element;
	}

	/**
	 * Za kolik dni je mozno vyzvednout na pobocce
	 * @param int $days
	 * @return XmlElement
	 */
	private function getPickupDays($days)
	{
		if (!is_numeric($days) || $days < 0 || $days > 7) {
			throw new InvalidArgumentException('Pickup days must be integer between 0 and 7. Now is ' . $days);
		}

		$date = $this->getDeadline()->modifyClone("+$days days");

		$element = new XmlElement();
		$element->addAttribute('orderDeadline', $this->getDeadlineTime());
		$element->setValue($date->format('Y-m-d H:i'));

		return $element;
	}

}