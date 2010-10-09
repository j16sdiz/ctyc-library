<?php
defined('SYSPATH') or die('No direct script access.');

class Controller_JSON extends Controller
{
	public static $COLUMN = array("id","code","name","author","publisher");
	
	// list all books as filter
	public function action_list()
	{
	
		$this->auto_render = false;
		header('content-type: application/json');

		$count_all = ORM::Factory('book')->count_all();

		$query = ORM::Factory('book');
		$query = $this->_filter_term($query);

		$query = $query->reset(FALSE);
		$count_filter = $query->count_all();

		$query = $this->_sort($query);
		$query = $this->_limit($query);

		$books = array();
		foreach ($query->find_all() as $book) {
			$row = array();
			foreach (Controller_JSON::$COLUMN as $col) {
				array_push( $row, $book->{$col} );
			}
			array_push( $row, "" );
			array_push( $books, $row );
		}
		
		$this->request->response = json_encode( array(
		 "aaData" => $books,
		 "iTotalRecords" => $count_all,
		 "iTotalDisplayRecords" => $count_filter
		));
	
		$this->_profile();
	}
	
	// get book copies
	function action_getdetail() {
		$code = $this->request->param('id');
		$result = ORM::Factory('bookcopy')
			->where('code', '=', $code)
			->order_by('copy')
			->find_all()->as_array();
	
		$this->request->response = json_encode( $result );
	
		$this->_profile();
	}

	function _filter_term($query) {
		if (isset($_REQUEST['sSearch'])) {
			foreach( preg_split('/\s+|,|"|;/', $_REQUEST['sSearch']) as $si ) {
				if ( $si == '' )
					continue;
				$query = $query->and_where_open();
				foreach (Controller_JSON::$COLUMN as $col)
					$query = $query->or_where($col, 'LIKE', '%'.$si.'%');
				$query = $query->and_where_close();
			}
		}
		return $query;
	}
	function _sort($query) {
		if (isset($_REQUEST['iSortingCols']) && $_REQUEST['iSortingCols'] >= 1) {
		    for ($i = 0 ; $i < $_REQUEST['iSortingCols'] ; $i++) {
		    	if (!isset($_REQUEST['iSortCol_' . $i]))
		    		break;
		    	if (!isset($_REQUEST['sSortDir_' . $i]))
		    		break;
		    	if ($_REQUEST['sSortDir_' . $i] != 'asc' &&
		    	    $_REQUEST['sSortDir_' . $i] != 'desc')
		    	    	break;
		    	
		    	$query = $query->order_by(
		    		Controller_JSON::$COLUMN[ $_REQUEST['iSortCol_' . $i] ],
		    		$_REQUEST['sSortDir_' . $i]);
		    }
		}
		return $query;
	}
	function _limit($query) {
		if (isset($_REQUEST['iDisplayStart'])) {
			$query = $query->offset($_REQUEST['iDisplayStart']);
		}
		if (isset($_REQUEST['iDisplayLength'])) {
			$query = $query->limit($_REQUEST['iDisplayLength']);
		}
		return $query;
	}
	
	function _profile() {
		if (!Request::$is_ajax) {
			?><div id="kohana-profiler">
			<?php echo View::factory('profiler/stats') ?>
			</div>
			<?php
		}
	}
} // End
?>
