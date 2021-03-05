<?php

/**
 * Function check input number for prime, and after echo result yes/no or show error
 *
 * @param int $inputNumber input integer, else php error
 * @return bool true/false as check result
 */
function primeNumberCheck( int $inputNumber )
{
	// check if input is number 1 if true return false
	if ($inputNumber == 1)
	{
		return false;
	};
	
	// check for prime number
	for ($i = 2; $i <= $inputNumber/2; $i++)
	{
		if ($inputNumber % $i == 0)
		{
			return false;
		};
	};
	
	return true;
};

// example of use
$number = 19;

$boolResult = primeNumberCheck($number);

// ternary check result
$echoResult = ( $boolResult ) ? "Да" : "Нет";

echo "Номер: {$number}, Простое число? - {$echoResult}";