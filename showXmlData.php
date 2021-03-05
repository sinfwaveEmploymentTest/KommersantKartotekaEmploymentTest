<?php

namespace Kommersant;
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
/**
 * Constructed class. Takes test xml file as construct argument for using in class methods
 * 
 * @method public __construct( string $fileName ) uses xml filename as argument and converts file string to object
 * @method public getBankruptPersonDataToString() converts bankrupt person data from xml object to string
 * @method public getLotsDataToString() converts lots data from xml object to string via foreach
 * @example this $showXmlData = new showXmlData('test.xml');
 */
class showXmlData
{
	/**
	 * xml data object (SimpleXML)
	 */
	protected object $xmlDataObject;
	
	/**
	 * Class constructor
	 *
	 * @param string $fileName full path to filename example: 'etc\test.xml'
	 */
	public function __construct( string $fileName )
	{
		// read file
		$xmlString = file_get_contents($fileName);
		
		// fix string from common mistake create object SimpleXmlObject and takes to class property
		$brokenFormat = ['&type='];
		$replaceTo    = ['&amp;type='];
		$this->xmlDataObject = simplexml_load_string( str_replace( $brokenFormat, $replaceTo, $xmlString ) );
		
		return $this;
	}
	
	/**
	 * Takes bankrupt person data from class propety (xmlDataObject) and converts to formatted string
	 *
	 * @return string $bankruptPersonData formatted string
	 */
	public function getBankruptPersonDataToString()
	{
		// get bankrupt person attributes node
		$data = $this->xmlDataObject->BankruptInfo->BankruptPerson->attributes();

		// concat full bankrupt person name
		$fullName = $data['FirstName'].' '.$data['MiddleName'].' '.$data['LastName'];

		// create display string
		$bankruptPersonData = "Должник: ID {$data['Id']}, Тип: {$data['InsolventCategoryName']}, Имя: {$fullName}<br />";
		
		// return string
		return $bankruptPersonData;
	}
	
	/**
	 * Takes lots data from class propety (xmlDataObject) and converts to formatted string
	 *
	 * @return string $displayLotData formatted string
	 */
	public function getLotsDataToString()
	{
		// type cast lot table
		$lotData = $this->xmlDataObject->MessageInfo->Auction->LotTable->AuctionLot;
		
		// create string for concat with list header
		$displayLotData = 'Список лотов:<br />';

		foreach ($lotData as $key => $value)
		{
			// trim whitespace in description;
			$description = trim($value->Description);
			
			$displayLotData .="Номер (лот): {$value->Order}, Стоимость (начальная цена) {$value->StartPrice}, Описание: {$description}<br />";
		}
		
		// return string
		return $displayLotData;
	}
}

// example of use
$showXmlData = new showXmlData('test.xml');

echo( $showXmlData->getBankruptPersonDataToString() );
echo( $showXmlData->getLotsDataToString() );