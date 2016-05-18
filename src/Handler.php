<?php namespace FergusInLondon\Events;

/*
 * This file is part of the FergusInLondon\Events package.
 *
 * (c) Fergus Morrow <fergus@fergus.london>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

class Handler {

	/** @var FergusInLondon\Events\Registry */
	private $registry;

	/** @var string */
	private $registryIdentifier;
	

	/**
	 * The constructor requires a Callable object, this is the actual event
	 *  handler. Note, you may override this, in conjunction with the "respond"
	 *  method, to avoid manually calling (+ binding) the closure.
	 *
	 * @param Callable  $action      The Handling action, responsible for responding to an event.
	 */
	public function __construct(Callable $action){
		$this->action = $action->bindTo($this, $this);
	}
	

	/**
	 * When an event occurs, this is the method which get calls. This is simply
	 *  a wrapper function around our Callable by default.
	 *
	 * @param array     $params      Parameters to be passed to the handling Callable, given to the event when it was triggered.
	 */
	public function respond($params = array()){
		return call_user_func_array($this->action, $params);
	}
	

	/**
	 * Upon registering a Handler with a Registry, this method gets called
	 *  by the Registry - and passes with it the Registry object and an
	 *  alphanumeric string which identifies the Handler. These two variables
	 *  can be used to subsequently cancel the Handler.
	 *
	 * @param Registry  $registry    Registry object the Handler has registered with.
	 * @param string    $identifier  An alphanumeric identifier, which allows the Registry to locate this Handler.
	 */
	public function associateRegistry(Registry $registry, $identifier){
		$this->registry = $registry;
		$this->registryIdentifier = $identifier;
	}
	

	/**
	 * Remove this Handler from the Registry.
	 */
	public function stopListening(){
		$this->registry->removeHandler($this->registryIdentifier);
	}
}