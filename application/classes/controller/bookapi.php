<?php
class Controller_BookAPI extends Controller_REST {
	public function action_index() {
		header('Content-Type: application/json');
		try {
			$books = ORM::Factory('book');
			$books->bfilter($this->request->param());

			$count = $books->reset(FALSE)->count_all();

			$books->blimit($_REQUEST);

			$result = $books->find_all();
			$book_data = array();
			while ($result->valid()) {
				array_push($book_data, $result->current()->as_array());
				$result->next();
			}

			$this->request->response = json_encode( array(
				"success" => true,
				"totalRows" => $count,
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

			$cat = $this->request->param('cat');
			$code = $this->request->param('code');
			$copy = $this->request->param('copy');

			if ( $this->request->param('cat') ) {
				$copies = isset($_REQUEST['copies'])?$_REQUEST['copies']:1;
				$value  = array_merge($this->request->param(), $_REQUEST);
				unset($value['id']);
				$book   = new Model_Book;
				$book->values($value);
				$book->copy = 1;
				$value = $book->as_array();

				if ($book->check()) { 
					$books = self::create_books($value, $copies);
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

	private function get_unused_code($cat) {
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

	private function create_books($value, $copy, $start_copy = 1) {
		$books = array();
		for ($c = $start_copy ; $c <= $copy ; $c++) {
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
