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
		$handler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['spec.test', $handler]);
	}
	
	function it_should_associate_with_handler($handler)
	{
		$handler->beADoubleOf('FergusInLondon\Events\Handler');
		
		$this->callOnWrappedObject('registerHandler', ['spec.test', $handler]);
		$handler->associateRegistry(Argument::cetera())->shouldHaveBeenCalled();
	}

	function it_should_execute_callable_upon_trigger($handler)	{
		$handler->beADoubleOf('FergusInLondon\Events\Handler');
		
		$this->callOnWrappedObject('registerHandler', ['spec.test', $handler]);
		$this->callOnWrappedObject('trigger', ['spec.test']);
		
		$handler->respond(Argument::cetera())->shouldHaveBeenCalled();
	}
	
	function it_should_pass_parameters_to_handler($handler)
	{
		$expectedParams = [1, 2, 3];
		$handler->beADoubleOf('FergusInLondon\Events\Handler');
		
		$this->callOnWrappedObject('registerHandler', ['spec.test', $handler]);
		$this->callOnWrappedObject('trigger', ['spec.test', $expectedParams]);
		
		$handler->respond($expectedParams)->shouldHaveBeenCalled();
	}
	
	function it_should_execute_correct_handlers($eventAHandler, $eventBHandler)
	{
		$eventAHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.a', $eventAHandler]);

		$eventBHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.b', $eventBHandler]);
		
		$this->callOnWrappedObject('trigger', ['event.a']);
		$eventAHandler->respond(Argument::any())->shouldHaveBeenCalled();
		$eventBHandler->respond(Argument::any())->shouldNotHaveBeenCalled();
		
		$this->callOnWrappedObject('trigger', ['event.b']);
		$eventAHandler->respond(Argument::any())->shouldHaveBeenCalled();
		$eventBHandler->respond(Argument::any())->shouldHaveBeenCalled();
	}
	
	function it_can_clear_all_handlers($eventAHandler, $eventBHandler)
	{
		$eventAHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.a', $eventAHandler]);

		$eventBHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.b', $eventBHandler]);
		
		$this->callOnWrappedObject('clearHandlers');
		
		$this->callOnWrappedObject('trigger', ['event.a']);
		$eventAHandler->respond(Argument::any())->shouldNotHaveBeenCalled();

		$this->callOnWrappedObject('trigger', ['event.b']);
		$eventBHandler->respond(Argument::any())->shouldNotHaveBeenCalled();
	}
	
	function it_can_clear_handlers_for_an_individual_event($eventAHandler, $eventBHandler)
	{
		$eventAHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.a', $eventAHandler]);

		$eventBHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.b', $eventBHandler]);
		
		$this->callOnWrappedObject('clearHandlers', ['event.a']);
		
		$this->callOnWrappedObject('trigger', ['event.a']);
		$eventAHandler->respond(Argument::any())->shouldNotHaveBeenCalled();

		$this->callOnWrappedObject('trigger', ['event.b']);
		$eventBHandler->respond(Argument::any())->shouldHaveBeenCalled();
	}
	
	function it_can_remove_an_individual_handler($eventAHandler, $eventBHandler)
	{
		$eventAHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.a', $eventAHandler]);

		$eventBHandler->beADoubleOf('FergusInLondon\Events\Handler');
		$this->callOnWrappedObject('registerHandler', ['event.b', $eventBHandler]);
		
		
		// Big assumption on internal logic here, not nice and goes somewhat against
		//  the whole idea of BDD.
		$eventAHandler->associateRegistry(Argument::any(), 'event.a_0')->shouldHaveBeenCalled();
		$this->callOnWrappedObject('removeHandler', ['event.a_0']);
		// EOF.

		$this->callOnWrappedObject('trigger', ['event.a']);
		$eventAHandler->respond(Argument::any())->shouldNotHaveBeenCalled();

		$this->callOnWrappedObject('trigger', ['event.b']);
		$eventBHandler->respond(Argument::any())->shouldHaveBeenCalled();
	}
}
