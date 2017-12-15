<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Tests\Google;

use Tester\Assert;
use Tester\TestCase;
use Unio\FeedExport\Google\GoogleGenerator;
use Unio\FeedExport\Google\GoogleItem;
use Unio\FeedExport\InvalidArgumentException;
use Unio\FeedExport\XmlWriter;


require_once __DIR__ . '/../bootstrap.php';
/**
 * @testCase
 */
class GoogleGeneratorTest extends TestCase
{
	/**
	 * @var GoogleGenerator
	 */
	private $generator;

	private $file;


	public function setUp()
	{
		$this->file = TEMP_DIR . '/google.xml';
		$this->generator = new GoogleGenerator(new XmlWriter());
	}

	public function testGenerate()
	{
		$item = new GoogleItem();
		$item->setName('Test');
		$item->setUrl('http://www.seznam.cz');
		$item->setId(10);
		$item->setDescription('<p>popisek</p>');
		$item->setPrice(100, 'czk');
		$item->setCategory('moje > kategorie');
		$item->setCategoryText('google > kategorie');
		$item->setCondition('new');
		$item->setQuantity(2);
		$item->setAvailability('in stock');
		$item->setEan('1234567890123');
		$item->setBrand('značka');
		$item->setImage('http://www.seznam.cz/obrazek.jpg');

		$item2 = clone $item;
		$item2->setName('varianta');
		$item2->setId('11_vid1');
		$item2->setItemgroupId(11);

		$this->generator->beginGenerate($this->file);
		$this->generator->generateItem($item);
		$this->generator->generateItem($item2);
		$this->generator->endGenerate();

		Assert::same(file_get_contents(__DIR__ . '/google_test.xml'), file_get_contents($this->file));
	}



}


(new GoogleGeneratorTest())->run();
