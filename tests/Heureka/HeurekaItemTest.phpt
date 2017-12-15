<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Heureka;

use Tester\Assert;
use Tester\TestCase;
use Unio\FeedExport\InvalidArgumentException;


require_once __DIR__ . '/../bootstrap.php';
/**
 * @testCase
 */
class HeurekaItemTest extends TestCase
{

	/** @var HeurekaItem */
	private $item;


	public function setUp()
	{
		$this->item = new HeurekaItem();
	}


	public function testException()
	{

		$item = new HeurekaItem();
		$item->setId(88);
		$item->setUrl('http://www.test.cz');
		$item->setDescription('<p>popisek bez html tagu</p>');
		$item->setDeliveryDate(2);

		Assert::exception(function() use ($item) {
			$item->getRow();
		}, InvalidArgumentException::class, 'These elements are required and have not been set: PRODUCTNAME,PRICE_VAT');

		Assert::exception(function() use ($item) {
			$item->setMaxCPC(9000);
		}, InvalidArgumentException::class, 'MAX CPC must be between 1 AND 1000 Kč');

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
		$item->setVideo('http://www.strema.cz/video-nepujde-protoze-neni-youtube');

		$row = $item->getRow();
		Assert::same('popisek bez html tagu', $row['DESCRIPTION']);
		Assert::same('Test', $row['PRODUCTNAME']);
		Assert::same('http://wwww.test.cz', $row['URL']);
		Assert::same(2, $row['DELIVERY_DATE']);
		Assert::same(100.0, $row['PRICE_VAT']);
		Assert::same('Main | Sub | Detail', $row['CATEGORYTEXT']);
		Assert::same('http://wwww.test.cz/image.jpg', $row['IMGURL']);
		Assert::notContains('VIDEO_URL', $row, 'Videa pouze z youtube.com');

		$clone = clone $item;
		$clone->setItemgroupId(99);
		$clone->setId('99_vid_5');
		$clone->setName('Varianta');
		$clone->setPrice('5.76699');
		$clone->addDelivery('CESKA_POSTA_NA_POSTU', 100, 120);
		$clone->addDelivery('PPL', 100);
		$clone->addDelivery('DHL', null, 120);
		$clone->setVideo('http://www.youtube.com/watch?v=XXXXXXXX');

		$row = $clone->getRow();
		Assert::same(99, $row['ITEMGROUP_ID']);
		Assert::same('99_vid_5', $row['ITEM_ID']);
		Assert::same('Varianta', $row['PRODUCTNAME']);
		Assert::same(5.77, $row['PRICE_VAT']);
		Assert::same('Main | Sub | Detail', $row['CATEGORYTEXT']);
		Assert::same(3, count($row['DELIVERY']));
		Assert::equal(['DELIVERY_ID' => 'CESKA_POSTA_NA_POSTU', 'DELIVERY_PRICE' => 100, 'DELIVERY_PRICE_COD' => 120], $row['DELIVERY'][0]);
		Assert::equal(['DELIVERY_ID' => 'DHL', 'DELIVERY_PRICE_COD' => 120], $row['DELIVERY'][2]);
		Assert::same('http://www.youtube.com/watch?v=XXXXXXXX', $row['VIDEO_URL']);
	}


}


(new HeurekaItemTest())->run();
