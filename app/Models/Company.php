<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'country', 'sector', 'email', 'password', 'phone', 'logo',
        'status', 'rejection_reason', 'confirmed', 'motivation', 'sectors_interest', 
        'wants_btob', 'wants_btog', 'other_meetings'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'sectors_interest' => 'array',
        'wants_btob' => 'boolean',
        'wants_btog' => 'boolean',
        'confirmed' => 'boolean',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function sentMeetingRequests()
    {
        return $this->hasMany(MeetingRequest::class, 'sender_company_id');
    }

    public function receivedMeetingRequests()
    {
        return $this->hasMany(MeetingRequest::class, 'receiver_company_id');
    }
}