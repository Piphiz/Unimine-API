<?php

namespace App\Models;

use App\Services\HashValidator;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'link',
        'hash',
        'user_id',
    ];

    /**
     * @throws Exception
     */
    public function findByHashOrFail($hash)
    {
        if (empty($hash)) {
            throw new Exception('Hash não informado!');
        }

        if (!HashValidator::validate($hash)) {
            throw new Exception('Hash inválido!');
        }

        return $this->where('hash', $hash)->first();
    }

    /**
     * @throws Exception
     */
    public function findByLink($link)
    {
        return $this->where('link', $link)->first();
    }
}
