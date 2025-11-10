<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['company_id', 'name', 'function', 'email', 'phone'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}