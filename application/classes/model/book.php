<?php
class Model_Book extends ORM
{
	protected $_filters = array(
		TRUE => array('trim' => NULL),
		'isbn' => array('ISBN::clean' => NULL)
	);
	protected $_rules = array(
		'cat' => array('not_empty' => NULL),
		'isbn' => array('ISBN::verify' => NULL)
	);

	public function bfilter($param, $selectivity_limit = 0) {
		$id = isset($param['id'])?$param['id']:NULL;
		$cat = isset($param['cat'])?$param['cat']:NULL;
		$code = isset($param['code'])?$param['code']:NULL;
		$copy = isset($param['copy'])?$param['copy']:NULL;

		$specific = 0;
		if ($cat) {
			$this->where('cat', '=', $cat); 
			$specific++;
			if ($code) {
				$this->where('code', '=', $code);
				$specific++;
				if ($copy) {
					$this->where('copy', '=', $copy); 
					$specific++;
				}
			}
		} else if ($id) {
			$this->where('id', '=', $id); 
			$specific+=4;
		}
		if ($specific < $selectivity_limit)
			throw new Exception('not specific enough');
		return $this;
	}

	public function blimit(&$limit) {
		$start  = isset($limit['start'])  ? $limit['start']  :  0;
		$count  = isset($limit['limit'])  ? $limit['limit']  : 20;

		$this->offset($start);
		$this->limit($count);
		return $this;
	}
}
?>
