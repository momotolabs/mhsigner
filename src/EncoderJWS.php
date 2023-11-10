<?php

namespace Momotolabs\Mhsigner;

use Namshi\JOSE\SimpleJWS;

class EncoderJWS extends SimpleJWS
{
    protected $payload;

    /**
     * @param  array  $payload
     * @return EncoderJWS|$this
     */
    public function setPayload(array $payload): EncoderJWS|static
    {
        $this->payload = $payload;

        return $this;
    }
}
