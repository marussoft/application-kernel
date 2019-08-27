<?php

namespace Marussia\ApplicationKernel;

use Marussia\ApplicationKernel\Request;

interface KernelExtensionInterface
{
    public function handle(Request $request) : void;
}
