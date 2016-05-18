<?php

namespace spec\FergusInLondon\Events;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RegistrySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
		$this->shouldHaveType('FergusInLondon\Events\Registry');
    }

	function it_can_accept_handler($handler)
	{
		$this->callOnWrappedObject('associateRegistry', [$registry, 'eve_0']);

	}

	function it_should_execute_callable_upon_trigger($handler)	{
		$handler->beADoubleOf('FergusInLondon\Events\Handler');
	}
	
	function it_should_pass_parameters_to_handler($handler)
	{
		
	}
	
	function it_should_execute_correct_handlers($eventAHandler, $eventBHandler)
	{
		
	}
	
	function it_can_clear_all_handlers($eventAHandler, $eventBHandler)
	{
		
	}
	
	function it_can_clear_individual_handlers($eventAHandler, $eventBHandler)
	{
		
	}
}
