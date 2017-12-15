<?php
namespace Unio\FeedExport;


interface IWriter
{
	function writeItem(IItem $item);
}