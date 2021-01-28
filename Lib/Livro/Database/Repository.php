<?php
namespace Livro\Database;

use Exception;

final class Repository{
	private $activeRecord; //Classe manipulada pelo repositório

	function __construct($class){
		$this->activeRecord = $class;
	}
	function load(Criteria $criteria){
		$sql = "SELECT * FROM ".constant($this->activeRecord.'::TABLENAME');

		//Obtém a cláusula WHERE do objeto criteria
		if($criteria){
			$expression = $criteria->dump();
			if($expression){
				$sql .= ' WHERE '. $expression;
			}
			//Obtém as propiedades do critério
			$order  = $criteria->getProperty('order');
			$limit  = $criteria->getProperty('limit');
			$offset = $criteria->getProperty('offset');

			//Obtém ordenação do SELECT
			if($order){
				$sql .= ' ORDER '.$order;
			}
			if($limit){
				$sql .= ' LIMIT '.$limit;
			}
			if($offset){
				$sql .= ' OFFSET '.$offset;
			}
		}

		//Obtém transação ativa
		if($conn = Transaction::get()){
			Transaction::log($sql); //Registra a mensagem de log

			//Execulta a consulta no banco de dados
			$result = $conn->Query($sql);
			$results = array();

			if ($result) {
				//Percorre os resultados da consulta, retornando um objeto
				while($row = $result->fetchObject($this->activeRecord)){
					//Armazena no array $results
					$results[] = $row;
				}
			}
			return $results;
		}else{
			throw new Exception('Não há transação ativa!!');
		}
	}

	function delete(Criteria $criteria){
		$expression = $criteria->dump();
		$sql = "DELETE FROM ".constant($this->activeRecord.'::TABLENAME');
		if($expression){
			$sql .= ' WHERE '.$expression;
		}

		//Obtém transação ativa
		if($conn = Transaction::get()){
			Transaction::log($sql); //Registra mensagem no log
			$result = $conn->exec($sql); //Execulta a instrução DELETE
			return $result;
		}else{
			throw new Exception('Não há transação ativa!!');
		}
	}

	function count(Criteria $criteria){
		$expression = $criteria->dump();
		$sql = "SELECT count(*) FROM ".constant($this->activeRecord.'::TABLENAME');

		if($expression){
			$sql .= ' WHERE '.$expression;
		}

		//Obtém a transação ativa
		if($conn = Transaction::get()){
			Transaction::log($sql); //Registra a mensagem de log
			$result = $conn->query($sql); //Execulta intrução de SELECT
			
			if($result){
				$row = $result->fetch();
			}
			return $row[0]; //Retorna o resultado
		}else{
			throw new Exception('Não há transação ativa!!');
		}
	}
}