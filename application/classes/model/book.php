<?php
class Model_Book extends ORM
{
	protected $_primary_val = 'code';

	public $id;
	public $code;
	public $name;
	public $author;
	public $publisher;
	public $publishDate;
	public $status;
}
?>
