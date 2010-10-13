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
				"Index" => "library",
				"JSON"  => "json",
			);
			$this->template->controller	  = $this->request->controller;
			$this->template->action		  = $this->request->action;
		}
	}

	/**
	 * Fill in default values for our properties before rendering the output.
	 */
	public function after()
	{
		if($this->auto_render)
		{
			// Define defaults
			$styles                  = array(
				'http://ajax.googleapis.com/ajax/libs/yui/2.8.1/build/reset-fonts-grids/reset-fonts-grids.css' => 'screen',
				'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/smoothness/jquery-ui.css' => 'screen',
				'static/css/override.css' => 'screen'
					);
			$scripts                 = array(
					'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js',
					'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js',
					'static/datatable/media/js/jquery.dataTables.min.js'
					);

			// Add defaults to template variables.
			$this->template->styles  = array_reverse(array_merge($this->template->styles, array_reverse($styles)));
			$this->template->scripts = array_reverse(array_merge($this->template->scripts, array_reverse($scripts)));
		}

		// Run anything that needs to run after this.
		parent::after();
	}
}
?>
