<?php

namespace Momotolabs\Mhsigner;

use Illuminate\Support\Facades\Log;

class Signer
{
    /**
     * @param  array  $data
     * @return string
     */
    public static function make(array $data): string
    {
        try {
            $certData = (new ValidateCert($data['nit']))->getCertValidate($data['password']);
            $jws = new EncoderJWS(['alg' => 'RS512',]);
            $key = self::formatKey($certData->privateKey->encodied,true);
            $jws->setPayload($data['docJson']);
            $jws->sign($key);
            return $jws->getTokenString();
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return 'error to sign data';
        }
    }

    /**
     * @param $code
     * @param $privateKey
     * @return string
     */
    private static function formatKey($code, $privateKey): string
    {
        return $privateKey ?
            "-----BEGIN PRIVATE KEY-----\n".(string) $code."\n-----END PRIVATE KEY-----"
            :
            "-----BEGIN PUBLIC KEY-----\n".(string) $code."\n-----END PUBLIC KEY-----";
    }
}
