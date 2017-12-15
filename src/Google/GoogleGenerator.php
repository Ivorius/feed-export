<?php

/**
 * XML export for Google
 *
 * @author Ivo
 */

namespace Unio\FeedExport\Google;

use Unio\FeedExport\Generator;
use Unio\FeedExport\IGenerator;
use Unio\FeedExport\IItem;
use Unio\FeedExport\InvalidArgumentException;
use Unio\Eshop\ProductEntity;
use Unio\Eshop\Price;
use Unio\FeedExport\IWriter;

class GoogleGenerator implements IGenerator
{

	/**
	 * @var XmlWriter
	 */
	private $writer;

	private $title;
	private $description;
	private $link;

	public function __construct(IWriter $writer)
	{
		$this->writer = $writer;
	}

	public function setChannel($title, $description, $link)
	{
		$this->title = $title;
		$this->description = $description;
		$this->link = $link;
	}


	public function beginGenerate($file)
	{
		$this->file = $file;

		$xml = $this->writer->getXml();
		$xml->openMemory();
		$xml->setIndent(TRUE);
		$xml->startDocument('1.0', 'UTF-8');
		$xml->startElement('rss');
		$xml->writeAttribute('version', '2.0');
		$xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

		$xml->startElement('channel');
		$xml->writeElement('title', $this->title);
		$xml->writeElement('description', $this->description);
		$xml->writeElement('link', $this->link);
		file_put_contents($file, $xml->flush(TRUE), LOCK_EX);
	}


	public function generateItem(IItem $item)
	{
		if (!empty($item->getRow())) {
			$xml = $this->writer->getXml();
			$xml->startElement("item");
			$this->writer->writeItem($item);
			$xml->endElement();
		}

	}


	public function endGenerate()
	{
		$xml = $this->writer->getXml();

		// Final flush to make sure we haven't missed anything
		$xml->endElement(); //channel
		$xml->endElement(); //rss
		file_put_contents($this->file, $xml->flush(TRUE), FILE_APPEND | LOCK_EX);
	}


	public function flush()
	{
		$xml = $this->writer->getXml();
		file_put_contents($this->file, $xml->flush(TRUE), FILE_APPEND | LOCK_EX);
	}


}
