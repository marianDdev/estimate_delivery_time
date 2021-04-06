<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ZipCodesRepository
{
    public function getZipCode(int $zipCode)
    {
        $code = DB::table("zip_codes")->select("zip_code")->where("zip_code", $zipCode)->first();

        if($code === null) {
            return ModelNotFoundException::class;
        }

        return $code;
    }
}
