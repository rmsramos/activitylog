<?php

use Rmsramos\Activitylog\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Calls a protected method, so the timeline entries can be exercised without mounting a Livewire component.
 */
function callProtected(object $object, string $method, mixed ...$arguments): mixed
{
    return (new ReflectionMethod($object, $method))->invoke($object, ...$arguments);
}
