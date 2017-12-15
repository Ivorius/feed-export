<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Google;

use Tester\Assert;
use Tester\TestCase;
use Unio\FeedExport\InvalidArgumentException;

require_once __DIR__.'/../bootstrap.php';

/**
 * @testCase
 */
class GoogleItemTest extends TestCase
{

	public function testException()
	{

		$item = new GoogleItem();
		$item->setId(88);

		Assert::exception(function () use ($item) {
			$item->getRow();
		}, InvalidArgumentException::class, 'These elements are required and have not been set: g:title,description,g:product_type,link,g:image_link,g:price,g:google_product_category,g:availability,g:condition,g:brand');

		Assert::exception(function () use ($item) {
			$item->setUrl('neplatna-url');
		}, InvalidArgumentException::class);
	}

	public function testItem()
	{
		$item = new GoogleItem();
		$item->setId(1);
		$item->setUrl('http://www.seznam.cz');
		$item->setName('test');
		$item->setDescription('popisek');
		$item->setImage('http://www.seznam.cz/obrazek.jpg');
		$item->addAdditionalImage('http://www.seznam.cz/obrazek2.jpg');

		$item->setAvailability('in stock');
		$item->setPrice(100, 'czk');
		$item->setCategoryText('Elektronika > Komunikace > Telefonování > Mobilní telefony');
		$item->setCategory('moje > kategorie');
		$item->setCondition('new');
		$item->setEan('1234567890123');
		$item->setBrand('značka');

		Assert::type('array', $item->getRow());
		Assert::same('100 CZK', $item->getRow()['g:price']);
	}
}


(new GoogleItemTest())->run();
