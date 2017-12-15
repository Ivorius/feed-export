<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport\Tests\Heureka;

use Nette\Utils\DateTime;
use Tester\Assert;
use Tester\FileMock;
use Tester\TestCase;
use Unio\FeedExport\Heureka\AvailGenerator;
use Unio\FeedExport\Heureka\AvailItem;
use Unio\FeedExport\XmlWriter;


require_once __DIR__.'/../bootstrap.php';

/**
 * @testCase
 */
class AvailGeneratorTest extends TestCase
{
	/**
	 * @var AvailGenerator
	 */
	private $generator;

	private $file;

	public function setUp()
	{
		$this->file = TEMP_DIR . '/heureka_avail.xml' ;
		$this->generator = new AvailGenerator(new XmlWriter());
	}

	public function testGenerate()
	{
		$item = new AvailItem();
		$item->setId(10);
		$item->setDeadline(DateTime::from('2017-12-12 12:00'));
		$item->setDaysToDelivery(2);

		$item2 = new AvailItem();
		$item2->setId(99);
		$item2->setDeadline(DateTime::from('2017-12-12 12:00'));
		$item2->setDaysToDelivery(1);
		$item2->setQty(6);
		$item2->setDepot('aaa', 0, 3);

		$this->generator->beginGenerate($this->file);
		$this->generator->generateItem($item);
		$this->generator->generateItem($item2);
		$this->generator->endGenerate();

		Assert::same( file_get_contents(__DIR__ . '/avail_test.xml'), file_get_contents($this->file));
	}

}


(new AvailGeneratorTest())->run();
