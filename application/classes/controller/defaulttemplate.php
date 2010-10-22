<?php
defined('SYSPATH') or die('No direct script access.');

class Controller_DefaultTemplate extends Controller_Template
{
	public $template = 'templates/default';

	/**
	 * Initialize properties before running the controller methods (actions),
	 * so they are available to our action.
	 */
	public function before()
	{
		// Run anything that need ot run before this.
		parent::before();

		if($this->auto_render)
		{
			// Initialize empty values
			$this->template->title            = ' 迦密大元邨福音堂';
			$this->template->meta_keywords    = '';
			$this->template->meta_description = '';
			$this->template->meta_copywrite   = '';
			$this->template->header           = '';
			$this->template->content          = '';
			$this->template->footer           = '';
			$this->template->styles           = array();
			$this->template->scripts          = array();

			$this->template->navigation	  = array(
				"List" => "listbook",
				"Add"  => "addbook",
			);
			$this->template->controller	  = $this->request->controller;
			$this->template->action		  = $this->request->action;

			$this->template->styles = array(
				'static/extjs/latest/resources/css/ext-all.css' => 'screen',
				'static/extjs/latest/resources/css/xtheme-gray.css' => 'screen',
				'static/css/override.css' => 'screen'
					);
			$this->template->scripts                 = array(
					'static/extjs/latest/adapter/ext/ext-base-debug.js',
					'static/extjs/latest/ext-all-debug.js'
					);
		}
	}
}
?>
