<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingRequest extends Model
{
    protected $fillable = [
        'sender_company_id', 'receiver_company_id', 
        'meeting_type', 'message', 'status'
    ];

    public function sender()
    {
        return $this->belongsTo(Company::class, 'sender_company_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Company::class, 'receiver_company_id');
    }
}
