<?php
class Controller_BookAPI extends Controller_REST {
	public function action_index() {
		header('Content-Type: application/json');
		try {
			$books = ORM::Factory('book');
			$books->bfilter($this->request->param());
			if (isset($_REQUEST['query'])) {
				$query = $_REQUEST['query'];
				foreach( preg_split('/[\s+,\'"\.]/', $query) as $si ) {
					$si = trim($si);
					if ($si == '') continue;
					$books = $books->and_where_open();
					$books->or_where('title', 'LIKE', '%'.$si.'%');
					$books->or_where('author', 'LIKE', '%'.$si.'%');
					$books->or_where('publisher', 'LIKE', '%'.$si.'%');
					$books = $books->and_where_close();
				}
			}

			$count = $books->reset(FALSE)->count_all();

			$books->blimit($_REQUEST);

			if (isset($_REQUEST['sort'])) {
				$sort = $_REQUEST['sort'];
				$dir = isset($_REQUEST['dir'])?$_REQUEST['dir']:'ASC';

				if ($sort == 'code' && $dir == 'DESC') {
					$books->order_by('cat', 'DESC');
					$books->order_by('code', 'DESC');
					$books->order_by('copy', 'DESC');
				} else {
					if ($sort != 'code')
						$books->order_by($sort, $dir);
					$books->order_by('cat', 'ASC');
					$books->order_by('code', 'ASC');
					$books->order_by('copy', 'ASC');
				}
			}

			$result = $books->find_all();
			$book_data = array();
			while ($result->valid()) {
				array_push($book_data, $result->current()->as_array());
				$result->next();
			}

			$this->request->response = json_encode( array(
				"success" => true,
				"totalBooks" => $count,
				"books" => $book_data,
				"query" => $books->last_query()
			) );
		} catch (Exception $e) {
			$this->request->response = json_encode( array(
				"success" => false,
				"message" => $e->__toString()
			) );
		}
	}

	private static function check_fields($book, $fields) {
		if ($book->check())
			return TRUE;
		$err = array_intersect_key($book->validate()->errors(), $fields);
		if (count($err) == 0)
			return TRUE;

		return $err;
	}

	public function action_update() {
		header('Content-Type: application/json');
		try {
			$books = ORM::Factory('book');
			$books->bfilter($this->request->param(), 2);
			$value = $_REQUEST;
			if (isset($_POST['books']))
				$value = array_merge($value, json_decode($_POST['books'],TRUE));
			unset($value['id'], $value['cat'], $value['code'], $value['copy']); // SECURITY AUDIT
			$books->values($value);
			$err = self::check_fields($books, $value);
			if ($err === TRUE) {
				$books->save_all();
				$this->request->response = json_encode( array(
							"success" => true,
							"query" => $books->last_query()
							) );
			} else {
				$this->request->response = json_encode( array(
					"success" => false,
					"error" => $err
				) );
			}
		} catch (Exception $e) {
			$this->request->response = json_encode( array(
				"success" => false,
				"message" => $e->__toString()
			) );
		}
	}

	public function action_create() {
		// header('Content-Type: application/json');
		try {
			Database::instance()->query(NULL, "SET AUTOCOMMIT=0", NULL);
			Database::instance()->query(NULL, "BEGIN", NULL);

			$param = $this->request->param();
			if (isset($param['cat'])) {
				if (!isset($param['code'])) {
					$param['code'] = self::get_unused_code($param['cat']);
				}
				if (!isset($param['copy']) || ($param['copy']+0>0)) {
					$param['copy'] = 1;
				}

				$copies = isset($_REQUEST['copies'])?$_REQUEST['copies']:1;
				$value  = array_merge($param, $_REQUEST);
				unset($value['id']);
				$book   = new Model_Book;
				$book->values($value);
				$book->copy = 1;
				$value = $book->as_array();

				if ($book->check()) { 
					$books = self::create_books($value, $copies, $param['copy']+0);
					$this->request->response = json_encode( array(
								"success" => true,
								"books" => $books
								) );
				} else {
					$this->request->response = json_encode( array(
								"success" => false,
								"error" => $book->validate()->errors()
								) );
				}
			} else {
				throw new Kohana_Exception('No cats found!');
			}
			Database::instance()->query(NULL, "ROLLBACK", NULL);
		} catch (Exception $e) {
			Database::instance()->query(NULL, "ROLLBACK", NULL);
			$this->request->response = json_encode( array(
				"success" => false,
				"message" => $e->__toString()
			) );
		}

//		echo View::factory('profiler/stats');
	}

	private static function get_unused_code($cat) {
		$books = ORM::Factory('book');
		$books->where('cat', '=', $cat);
		$books->order_by('code', 'desc');
		$book = $books->find();

		if ($book)
			return $book->code + 1;
		else
			return 1;
	}

	private function check_unused_code($cat) {
		$books = ORM::Factory('book');
	}

	private function create_books($value, $copy, $start_copy) {
		$books = array();
		for ($c = $start_copy ; $c < $start_copy + $copy ; $c++) {
			$book = new Model_Book();
			$book->values($value);
			$book->copy = $c;
			$book->save();
			array_push($books, $book->as_array());
		}

		return $books;
	}
}
?>
