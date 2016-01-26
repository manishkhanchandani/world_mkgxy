<?php

class abc
{
	private $a = 1;
	private $b = 2;
	protected $c = 3;
	protected $d = 4;
	public $e = 5;
	public $f = 6;
	function a()
	{
		echo $this->e;
	}
	
	function b()
	{
		
	}
}

$anything = new abc();
echo '<pre>';
print_r($anything->a());

?>