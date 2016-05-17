<?php namespace FergusInLondon\Events;

class Registry {

	private $handlers = array();

	
	public function trigger($ident, $params = array()){
		if (is_array($this->handlers[$ident])) {
			foreach ($this->handlers[$ident] as $l){
				call_user_func_array([$l, "respond"], [$params]);
			}
		}
	}


	public function registerListener($ident, EventListener $listener){
		if (!is_array($this->handlers[$ident])) {
			$this-handlers[$ident] = array();
		}
		
		
		$count = array_push($this->handlers[$ident], $listener);
		$listener->associateRegistry($this, sprintf("%s_%d", $ident, $count-1));
		return $this;
	}


	public function clearHandlers($ident = null){
		if (!is_null($ident) && is_array($this->handlers[$ident])) {
			$this->handlers[$ident] = array();
		} else if(is_null($ident)){			
			$this->handlers = array();
		}
	}


	public function removeHandler($handlerIdentifier){
		$identParts = explode('_', $handlerIdentifier);
		unset( $this->handlers[ $identParts[0] ][ $identParts[1] ] );
	}
}