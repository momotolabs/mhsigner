<?php

namespace Momotolabs\Mhsigner;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Src\exceptions\NotMatchException;



class ValidateCert
{
    public string $cert = '';

    /**
     * @param $cert
     */
    public function __construct($cert)
    {
        $this->cert = $cert;
    }

    /**
     * @return mixed
     */
    protected function validate(): mixed
    {
        $fileName = $this->cert.'.crt';
        $cert = null;
        try {
            if (! Storage::disk('local')->exists($fileName)) {
                throw new FileNotFoundException();
            }
            $cert = Storage::disk('local')->get($fileName);
            $xmlRead = simplexml_load_string($cert);

            return json_decode(
                json_encode(
                    $xmlRead,
                    JSON_THROW_ON_ERROR
                ),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $cert;
        }
    }

    /**
     * @param $password
     * @return object
     * @throws NotMatchException
     */
    public function getCertValidate($password): object
    {
        $dataCert = $this->validate();
        $encryptData = \hash('sha512', $password);
        if ($encryptData !== $dataCert->privateKey->clave) {
            throw new NotMatchException('Password not matched');
        }

        return $dataCert;
    }
}
