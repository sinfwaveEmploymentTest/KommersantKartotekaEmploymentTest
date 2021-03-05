<?php

namespace Kommersant;

/**
 * Format incoming invoice data
 * 
 * @method static addSumWithoutVAT() takes invoice data array and returns with additional data
 * 	(sum of VAT and invoice sum without VAT)
 */
class invoiceData
{
	/**
	 * Method for calculation of invoice sum without VAT
	 *
	 * @param array $invoiceData input of raw invoice data
	 * @return array $invoiceData invoice data with new key\value pairs with sum of VAT and sum without VAT
	 */
	public static function addSumWithoutVAT( array $invoiceData )
	{
		// check if invoice have field and 0 sum and VAT percentage, if positive return (or can change to trow error)
		if ( empty($invoiceData['invoiceSumWithVAT']) || empty($invoiceData['percentageOfVAT']) )
		{
			return;
		};
		
		// convert array string to float
		$invoiceSumWithVAT = (float)$invoiceData['invoiceSumWithVAT'];
		$percentageOfVAT = (float)$invoiceData['percentageOfVAT'];
		
		// calculate sum without VAT percentage
		$sumWithoutVAT = $invoiceSumWithVAT - ( ( $invoiceSumWithVAT / 100 ) * $percentageOfVAT );
		
		$invoiceData['invoiceSumOfVAT'] = $invoiceSumWithVAT - $sumWithoutVAT;
		$invoiceData['invoiceSumWithoutVAT'] = (float)$sumWithoutVAT;
		
		return $invoiceData;
	}
}

// example with all string invoice data array
$invoiceDataArray = [
	'invoiceNumber' => '123',
	'invoiceData' => '03.03.2021',
	'invoiceSumWithVAT' => '60000',
	'percentageOfVAT' => '20'
];

// add sum without VAT to existing array
$invoiceDataArray = invoiceData::addSumWithoutVAT($invoiceDataArray);

// display full array result
$display = "Счет номер {$invoiceDataArray['invoiceNumber']}
			Дата счета {$invoiceDataArray['invoiceData']}
			Сумма счета с НДС {$invoiceDataArray['invoiceSumWithVAT']}
			Процент НДС {$invoiceDataArray['percentageOfVAT']}%
			Сумма НДС {$invoiceDataArray['invoiceSumOfVAT']}
			Стоимость счета без НДС {$invoiceDataArray['invoiceSumWithoutVAT']}"
			;

echo( nl2br($display) );