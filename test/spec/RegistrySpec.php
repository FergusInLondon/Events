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

	function can_accept_handler()
	{
		
	}

	function trigger_should_execute_callable()
	{
		
	}
	
	function can_clear_all_handlers()
	{
		
	}
	
	function can_clear_individual_handlers()
	{
		
	}
}
