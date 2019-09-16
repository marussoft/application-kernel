<?php

namespace Marussia\ApplicationKernel\Contracts;

use Marussia\ApplicationKernel\Request;

interface KernelExtensionInterface
{
    public function handle(Request $request) : void;
}
