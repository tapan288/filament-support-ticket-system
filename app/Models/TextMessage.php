<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TextMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'response_payload',
        'status',
        'remarks',
        'sent_by',
        'sent_to',
    ];

    const STATUS = [
        "PENDING" => "PENDING",
        "SUCCESS" => "SUCCESS",
        "FAILED" => "FAILED",
    ];

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function sentTo()
    {
        return $this->belongsTo(User::class, 'sent_to');
    }
}
