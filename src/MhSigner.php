<?php

namespace Momotolabs\Mhsigner;

use Illuminate\Support\Facades\Facade;

class MhSigner extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mh-signer';
    }
}
