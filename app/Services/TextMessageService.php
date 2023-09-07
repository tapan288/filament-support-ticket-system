<?php

namespace App\Services;

use App\Models\User;
use App\Models\TextMessage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class TextMessageService
{
    public static function sendMessage(Collection $records, array $data)
    {
        $textMessages = collect([]);

        $records->map(function ($record) use ($data, $textMessages) {
            $textMessage = self::sendTextMessage($data, $record);

            $textMessages->push($textMessage);
        });

        TextMessage::insert($textMessages->toArray());
    }

    public static function sendTextMessage(array $data, User $user): array
    {
        $message = Str::replace('{name}', $user->name, $data['message']);

        // send the text message

        return [
            'message' => $message,
            'sent_by' => auth()?->id() ?? null,
            'status' => TextMessage::STATUS['PENDING'],
            'response_payload' => '',
            'sent_to' => $user->id,
            'remarks' => $data['remarks'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

    }
}
