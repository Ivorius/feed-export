<?php
/**
 * Author: Ivo Toman
 */

namespace Unio\FeedExport;


interface IGenerator
{

	/**
	 * @param string $file path
	 */
	public function beginGenerate($file);


	/**
	 * @param IItem $product
	 * @return mixed
	 */
	public function generateItem(IItem $product);


	public function endGenerate();

	public function flush();
}