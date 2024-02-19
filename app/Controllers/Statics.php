<?php
namespace App\Controllers;
use Algolia\AlgoliaSearch\SearchClient;

class Statics extends _MyController {
	function initSystem() {
		parent::initSystem();
	}
	function term(){
		return $this->view("/statics/term");
	}
	function test(){
		$client = SearchClient::create('ARJ50N5KS2', '1dc741c5e427b78ba8273e0ec662a635');
		$index = $client->initIndex('your_index_name');
		// $index->saveObjects([
		// 	['objectID' => 1, 'name' => 'Foo', 'description' => 'Bar'],
		// 	['objectID' => 2, 'name' => 'Hello', 'description' => 'World'],
		// ], ['autoGenerateObjectIDIfNotExist' => true]);
		$results = $index->search('WE');
		dbug($results);
	}
}
