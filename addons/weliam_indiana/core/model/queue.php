<?php
/**
 * PHP Class for queue
 * @author yangqijun@live.cn
 * @copyright DataFrog Beijingbei  Ltd. 2011-09-01
 */
class Welian_Indiana_Queue {

	public $length = 12;
	public $queue = array();
	public $delimiter = ',';

	function __construct($queue = array()) {
		$this -> queue = $queue;

	}

	/**
	 * @desc start queue
	 * @param String  $param  new queue element
	 */
	public function run($param) {

		if (!is_array($this -> queue)) {
			$this -> strToQue();
		}
		$currentlength = $this -> countqueue();
		//Count  the  queue length

		if ($currentlength < $this -> length && $this -> length > 0) {
			$this -> queAdd($param);
		} else if ($this -> length == 0) {
			$param = empty($param) ? 0 : $param;
			$this -> queue[] = $param;
		} else {
			echo $currentlength;
			for ($i = 0; $i < $currentlength - $this -> length + 1; $i++) {
				$this -> queRemove();
			}
			$this -> queAdd($param);
		}

		return $this -> queue;

	}

	/**
	 * String like this "22,23,24"  convert to array to do queue
	 * @param String $string
	 * @param String $delimiter
	 */
	public function strToQue() {

		$this -> queue = trim($this -> queue);
		if (empty($this -> queue) || is_null($this -> queue)) {
			$this -> queue = array();
		} else {
			$this -> queue = explode($this -> delimiter, $this -> queue);
		}

	}

	/**
	 * insert $node into queue
	 * @param string $node
	 */
	private function queAdd($node) {

		array_push($this -> queue, $node);
		$this -> countqueue();
	}

	private function queRemove() {
		$node = array_shift($this -> queue);
		$this -> countqueue();
		return $node;
	}

	private function countqueue() {
		$currentlength = count($this -> queue);

		return $currentlength;
	}

	function __destruct() {
		unset($this -> queue);
	}

}

//example
/*$str=" "; //array(1,2,3,4,5,6)
 $obj=new Queue ($str);
 $obj->length=10;  // 队列元素长度
 $obj->delimiter=',';  //如果队列是字符串，则元素直接的分隔符为|
 $a=$obj->run('91');   //要添加到队列中的元素
 $a=$obj->run('92');   //要添加到队列中的元素
 $a=$obj->run('93');   //要添加到队列中的元素
 $a=$obj->run('94');   //要添加到队列中的元素
 $a=$obj->run('95');   //要添加到队列中的元素
 $a=$obj->run('96');   //要添加到队列中的元素
 $a=$obj->run('97');   //要添加到队列中的元素
 $a=$obj->run('98');   //要添加到队列中的元素

 print_r($a);
 */
?>