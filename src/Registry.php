<?php namespace FergusInLondon\Events;

/*
 * This file is part of the FergusInLondon\Events package.
 *
 * (c) Fergus Morrow <fergus@fergus.london>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

class Registry {
	
	/** @var FergusInLondon\Events\Handler[] */
	private $handlers = array();


	/**
	 * Registers a new handler for an event based upon an identifying
	 *  string. 
	 *
	 * @param  string   $ident              Alphanumeric identifier for the event.
	 * @param  Handler  $listener           Handler object to respond to events.
	 *
	 * @return self
	 */
	public function registerListener($ident, Handler $listener){
		if (!is_array($this->handlers[$ident])) {
			$this-handlers[$ident] = array();
		}
		
		
		$count = array_push($this->handlers[$ident], $listener);
		$listener->associateRegistry($this, sprintf("%s_%d", $ident, $count-1));
		return $this;
	}

	
	/**
	 * Triggers an event based upon the identifying string for that
	 *  event. Optionally accepts an array of parameters to pass on
	 *  to the handling method.
	 *
	 * @param  string   $ident              Alphanumeric identifier for the event.
	 * @param  mixed[]  $params             Optional: parameters to pass to the handler object.
	 */
	public function trigger($ident, $params = array()){
		if (is_array($this->handlers[$ident])) {
			foreach ($this->handlers[$ident] as $l){
				call_user_func_array([$l, "respond"], [$params]);
			}
		}
	}


	/**
	 * Removes a handler, this should only be called from the Handler
	 *  object as it depends upon an identifier which is provided to
	 *  the Handler object and stored as a private attribute.
	 *
	 * @param  string   $handlerIdentifier  Alphanumeric identifier for handler to remove from the registry.
	 */
	public function removeHandler($handlerIdentifier){
		$identParts = explode('_', $handlerIdentifier);
		unset( $this->handlers[ $identParts[0] ][ $identParts[1] ] );
	}


	/**
	 * Clears handlers for a given event if an identifier is provided,
	 *  alternatively, clears all registered handlers if no identifier
	 *  is available.
	 *
	 * @param  string   $ident              Alphanumeric identifier for the event to clear the handlers from.
	 */
	public function clearHandlers($ident = null){
		if (!is_null($ident) && is_array($this->handlers[$ident])) {
			$this->handlers[$ident] = array();
		} else if(is_null($ident)){			
			$this->handlers = array();
		}
	}
}