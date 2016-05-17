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
}
