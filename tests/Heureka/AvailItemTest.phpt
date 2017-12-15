<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Heureka;

use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\TestCase;
use Unio\FeedExport\InvalidArgumentException;
use Unio\FeedExport\XmlElement;

require_once __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class AvailItemTest extends TestCase
{
	/** @var AvailItem */
	private $item;

	public function setUp()
	{
		$this->item = new AvailItem();
	}

	public function testSettings()
	{
		$item = $this->item;
		$item->setId(99);
		$item->setDeadline(DateTime::from('2017-12-12 12:00'));
		$item->setDaysToDelivery(1);
		$item->setQty(6);
		$item->setDepot('aaa', 0, 3);

		Assert::same(99, $item->getId());
		Assert::same('2017-12-12 12:00', $item->getDeadlineTime());
		Assert::same('2017-12-13 12:00', $item->getRow()['delivery_time']->getValue());
		Assert::same(6, $item->getRow()['stock_quantity']);

		/** @var XmlElement $depot */
		$depot = $item->getRow()['depot'];
		Assert::type(XmlElement::class, $depot);
		Assert::same(['id' => 'aaa'], $depot->getAttributes());

	}

	public function testExceptions()
	{
		$item = $this->item;

		Assert::exception(function() use ($item) {
			$item->setQty('b5');
		}, InvalidArgumentException::class, 'Stock quantity must be numeric and greater than zero');

		Assert::exception(function() use ($item) {
			$item->setDaysToDelivery(8);
		}, InvalidArgumentException::class);

		Assert::exception(function() use ($item) {
			$item->setDaysToDelivery(8);
		}, InvalidArgumentException::class);

		Assert::exception(function() use ($item) {
			$item->setDepot('aa', 8);
		}, InvalidArgumentException::class, '');

	}
}


(new AvailItemTest())->run();
