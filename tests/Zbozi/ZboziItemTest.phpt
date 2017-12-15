<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Zbozi;

use Tester\Assert;
use Tester\TestCase;
use Unio\FeedExport\InvalidArgumentException;

require_once __DIR__ . '/../bootstrap.php';
/**
 * @testCase
 */
class ZboziItemTest extends TestCase
{

	/** @var ZboziItem */
	private $item;


	public function setUp()
	{
		$this->item = new ZboziItem();
	}


	public function testException()
	{

		$item = new ZboziItem();
		$item->setId(88);
		$item->setUrl('http://www.test.cz');
		$item->setDescription('<p>popisek bez html tagu</p>');
		$item->setDeliveryDate(2);

		Assert::exception(function() use ($item) {
			$item->getRow();
		}, InvalidArgumentException::class, 'These elements are required and have not been set: PRODUCTNAME,PRICE_VAT');

		Assert::exception(function() use ($item) {
			$item->setMaxCPC(9000);
		}, InvalidArgumentException::class, 'MAX CPC must be between 1 AND 500 Kč');

		Assert::exception(function() use ($item) {
			$item->setUrl('neplatna-url');
		}, InvalidArgumentException::class);

	}


	public function testSettings()
	{
		$item = $this->item;
		$item->setId(99);
		$item->setName('Test');
		$item->setUrl('http://wwww.test.cz');
		$item->setDescription('<p>popisek bez html tagu</p>');
		$item->setPrice(100);
		$item->setDeliveryDate(2);
		$item->setCategoryText('Main | Sub | Detail');
		$item->setImage('http://wwww.test.cz/image.jpg');

		$row = $item->getRow();
		Assert::same('popisek bez html tagu', $row['DESCRIPTION']);
		Assert::same('Test', $row['PRODUCTNAME']);
		Assert::same('http://wwww.test.cz', $row['URL']);
		Assert::same(2, $row['DELIVERY_DATE']);
		Assert::same(100.0, $row['PRICE_VAT']);
		Assert::same('Main | Sub | Detail', $row['CATEGORYTEXT']);
		Assert::same('http://wwww.test.cz/image.jpg', $row['IMGURL'][0]);

		$clone = clone $item;
		$clone->setItemgroupId(99);
		$clone->setId('99_vid_5');
		$clone->setName('Varianta');
		$clone->setPrice('5.76699');

		$row = $clone->getRow();
		Assert::same(99, $row['ITEMGROUP_ID']);
		Assert::same('99_vid_5', $row['ITEM_ID']);
		Assert::same('Varianta', $row['PRODUCTNAME']);
		Assert::same(5.77, $row['PRICE_VAT']);
		Assert::same('Main | Sub | Detail', $row['CATEGORYTEXT']);
	}
}


(new ZboziItemTest())->run();
