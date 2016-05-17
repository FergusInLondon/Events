<?php namespace FergusInLondon\Events;

class Handler {

	private $registry;

	private $registryIdentifier;
	

	public function __construct(Callable $c){
		$this->action = $c->bindTo($this, $this);
	}
	

	public function respond($params){
		return call_user_func_array($this->action, $params);
	}
	

	public function associateRegistry(Registry $registry, $identifier){
		$this->registry = $registry;
		$this->registryIdentifier = $identifier;
	}
	

	public function stopListening(){
		$this->registry->removeHandler($this->registryIdentifier);
	}
}