<?php
class ISBN_Exception extends Kohana_Exception {}

class ISBN {
	public static function clean($value) {
		return preg_replace('/[\-\s]+/', '', strtoupper($value));
	}

	public static function verify($value) {
		switch (strlen($value)) {
			case 10:
				return self::valid10($value);
			case 13:
				return self::valid13($value);
			default:
				return false;
		}
	}	

	private static function valid10($isbn) {
		if (!preg_match('/^[0-9]{9}[0-9X]$/', $isbn)) return FALSE;

		$sum = 0;
		for ($i = 0 ; $i < 9 ; $i++)
			$sum += (1+$i)*substr($isbn, $i, 1);
		$sum %= 11;
		if ($sum == 10) $sum = 'X';

		if (substr($isbn, -1, 1) != $sum)
			return FALSE;

		return TRUE;
	}

	private static function valid13($isbn) {
		if (!preg_match('/^[0-9]{13}$/', $isbn)) return FALSE;

		$sum = 0;
		for ($i = 0 ; $i < 12 ; $i++)
			$sum += (($i%2)?3:1) * substr($isbn, $i, 1);
		$sum = 10 - $sum % 10;

		if (substr($isbn, -1, 1) != $sum)
			return FALSE;

		return TRUE;
	}
}
?>
