<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport;

use Tester\Assert;
use Tester\TestCase;
use Unio\Assets;

require './bootstrap.php';

/**
 * @testCase
 */
class XmlWriterTest extends TestCase
{
	/** @var XmlWriter */
	private $writer;

	public function setUp()
	{
		$this->prepareNew();
	}

	private function prepareNew()
	{
		$this->writer = new XmlWriter();
		$xml = $this->writer->getXml();
		$xml->openMemory();
		$xml->setIndent(TRUE);
		$xml->startDocument('1.0', 'UTF-8');
		return $this->writer;
	}

	public function testAttributes()
	{
		$att = new XmlElement();
		$att->setValue('example & son');
		$att->addAttribute('prvni', '10');
		$att->addAttribute('druhy', 'dvacka');
		$this->writer->processArray(['test' => $att]);
		$xml = $this->writer->getXml();
		Assert::same("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<test prvni=\"10\" druhy=\"dvacka\">example &amp; son</test>\n",
			$xml->flush());


		$this->prepareNew();
		$att = new XmlElement();
		$att->addAttribute('id', 10);
		$att->setValue(["one" => "jedna", "two" => "dva & > zbytek"]);
		$this->writer->processArray(['test' => $att]);
		$xml = $this->writer->getXml();
		Assert::same("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<test id=\"10\">\n <one>jedna</one>\n <two>dva &amp; &gt; zbytek</two>\n</test>\n",
			$xml->flush());
	}


	public function testWriteArray()
	{
		$arr = ["one", "two", "three"];
		$this->writer->processArray($arr, 'test');
		$xml = $this->writer->getXml();
		Assert::same("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<test>one</test>\n<test>two</test>\n<test>three</test>\n",
			$xml->flush());

		$this->prepareNew();
		$arr = ["one" => "jedna", "two" => "dva"];
		$this->writer->processArray($arr, 'test');
		$xml = $this->writer->getXml();
		Assert::same("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<test>\n <one>jedna</one>\n <two>dva</two>\n</test>\n",
			$xml->flush());

		$this->prepareNew();
		$arr = ["one", "two", "three"];
		$this->writer->processArray(['test' => $arr]);
		$xml = $this->writer->getXml();
		Assert::same("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<test>one</test>\n<test>two</test>\n<test>three</test>\n",
			$xml->flush());

		$this->prepareNew();
		$arr = ["one" => "jedna", "two" => "dva"];
		$this->writer->processArray(['test' => $arr]);
		$xml = $this->writer->getXml();
		Assert::same("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<test>\n <one>jedna</one>\n <two>dva</two>\n</test>\n",
			$xml->flush());

	}


	public function testParam()
	{
		$arr["PARAM"][0] = array(
			"PARAM_NAME" => 'Barva',
			"VAL" => 'Červená',
			"UNIT" => '',
		);
		$arr["PARAM"][1] = array(
			"PARAM_NAME" => 'Váha',
			"VAL" => '150',
			"UNIT" => 'g',
		);

		$this->prepareNew();
		$this->writer->processArray($arr);
		$xml = $this->writer->getXml();
		Assert::same(file_get_contents(__DIR__ . '/writer_param_test.xml'), $xml->flush());
	}

}


(new XmlWriterTest())->run();
