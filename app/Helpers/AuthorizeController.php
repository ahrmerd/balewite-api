<?php

namespace App\Http\Controllers;

class BasicAuthorizeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Announcement::class, null, [
            'except' => ['index', 'show']
        ]);
    }
}
