<?php 

namespace Livro\Widgets\Dialog;

use Livro\Widgets\Base\Element;

class Message extends Element{
	public function __construct($type, $message){
		
		$div = new Element('div');
		$div->style = 'text-align: center;';

		if($type == 'info'){
			$div->class = 'alert alert-info';
		}
		else if($type == 'success'){
			$div->class = 'alert alert-success';
		}
		else if($type == 'danger'){
			$div->class = 'alert alert-danger';
		}
		else if($type == 'warning'){
				$div->class = 'alert alert-warning';
		}else{
			$div->class = 'alert alert-default';
		}
		$div->add($message);
		$div->show();
	}
}
