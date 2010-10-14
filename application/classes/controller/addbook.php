<?php
defined('SYSPATH') or die('No direct script access.');

class Controller_AddBook extends Controller_DefaultTemplate
{
	public function action_index()
	{
		$this->template->content = View::factory('pages/addbook');
	}
} // End
?>
