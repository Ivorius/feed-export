<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Tests\Heureka;

use Tester\Assert;
use Tester\TestCase;
use Unio\FeedExport\Heureka\HeurekaGenerator;
use Unio\FeedExport\Heureka\HeurekaItem;
use Unio\FeedExport\XmlWriter;


require_once __DIR__.'/../bootstrap.php';

/**
 * @testCase
 */
class HeurekaGeneratorTest extends TestCase
{
	/**
	 * @var HeurekaGenerator
	 */
	private $generator;

	private $file;

	public function setUp()
	{
		$this->file = TEMP_DIR . '/heureka.xml' ;
		$this->generator = new HeurekaGenerator(new XmlWriter());
	}

	public function testGenerate()
	{
		$item = new HeurekaItem();
		$item->setId(10);
		$item->setName('Test');
		$item->setPrice(100);
		$item->setDescription('popisek');
		$item->setDeliveryDate(0);
		$item->setUrl('http://test.cz');

		$item2 = new HeurekaItem();
		$item2->setId('99_vid1');
		$item2->setItemgroupId(99);
		$item2->setName('Varianta');
		$item2->setPrice(200);
		$item2->setDescription('popisek');
		$item2->setDeliveryDate(1);
		$item2->setUrl('http://test.cz/variata1');
		$item2->setManufacturer('Vyrobce');
		$item2->setEan('1234567890123');
		$item2->setCategoryText('Main | Sub | Detail');
		$item2->setMaxCPC(15);
		$item2->setImage('http://www.test/obrazek.jpg');
		$item2->addImageAlternate('http://www.test/obrazek2.jpg');
		$item2->addParam('parametr', 20, 'kg');
		$item2->addParam('barva', 'modra');


		$this->generator->beginGenerate($this->file);
		$this->generator->generateItem($item);
		$this->generator->generateItem($item2);
		$this->generator->endGenerate();

		Assert::same( file_get_contents(__DIR__ . '/heureka_test.xml'), file_get_contents($this->file));
	}
}


(new HeurekaGeneratorTest())->run();
