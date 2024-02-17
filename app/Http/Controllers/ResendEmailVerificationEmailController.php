<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ResendEmailVerificationEmailController extends Controller
{
    public function store(Request $request): null|UserResource|Response
    {
        Validator::make($request->input(), [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ])->validate();

        /** @var User $user */
        $user = User::query()->where('email', $request->get('email'))->whereNull('email_verified_at')->first();

        $user?->sendEmailVerificationNotification();

        return response([
            'message' => 'Email verification email sent.',
        ]);
    }
}
