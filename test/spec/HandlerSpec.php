<?php

namespace spec\FergusInLondon\Events;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HandlerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
		$this->beConstructedWith(function(){
			return true;
		});
		
		$this->shouldHaveType('FergusInLondon\Events\Handler');
    }

	function it_should_execute_callable_from_respond()
	{
		$this->beConstructedWith(function(){
			throw new \Exception("Executed Callback");
		});

		//
		$this
			->shouldThrow(new \Exception("Executed Callback"))
			->during('respond');
	}
	
	function it_should_pass_parameters_to_callable()
	{
		$this->beConstructedWith(function($out){
			throw new \Exception($out);
		});

		//
		$this
			->shouldThrow(new \Exception("Parameters passed"))
			->during('respond', [["Parameters passed"]]);
	}

	function it_should_give_callable_object_scope($registry)	{
		$this->beConstructedWith(function(){
			throw new \Exception( $this->registryIdentifier );
		});
		
		$registry->beADoubleOf('FergusInLondon\Events\Registry');
		$this->callOnWrappedObject('associateRegistry', [$registry, 'eve_0']);
		
		$this->shouldThrow(new \Exception('eve_0'))->during('respond');
	}
	
	function it_should_be_associated_with_registry($registry)
	{
		$this->beConstructedWith(function(){
			return true;
		});
		
		//
		$registry->beADoubleOf('FergusInLondon\Events\Registry');
		$this->callOnWrappedObject('associateRegistry', [$registry, 'eve_0']);
	}
	
	function handler_should_remove_from_registry($registry)
	{
		$this->beConstructedWith(function(){
			return true;
		});

		//
		$registry->beADoubleOf('FergusInLondon\Events\Registry');
		$this->callOnWrappedObject('associateRegistry', [$registry, 'eve_0']);

		//
		$this->callOnWrappedObject('stopListening');
		$registry->removeHandler('eve_0')->shouldHaveBeenCalled();
	}
}
