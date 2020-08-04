<?php

namespace App\Http\Resources;

class MsgResource
{
    public static function make($title, $status, $details)
    {
        return response()->json(
            [
                'title' => $title,
                'status' => $status,
                'details' => $details,
            ],
            $status
        );
    }
}