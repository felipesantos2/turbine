<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        // returns a json automatically
        return UserResource::collection(User::all());
    }

    // public function create()
    // {
    //     //
    // }

    public function store(Request $request): Response|HttpResponse|FacadesResponse|ClientResponse
    {
        $credentials = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'name.required'     => 'Your name is required, my friend!',
            'email.required'    => 'Email is required!',
            'email.required'    => 'Email is required!',
            'email.email'       => 'Email is a not valid!',
            'password.required' => 'The password is required too!',
        ]);

        try {
            User::create($credentials);
        } catch (Exception $erro) {
            // return exception
            $response = [
                'data' => [
                    'message' => $erro->getMessage(),
                    'status'  => [
                        'status_text' => Response::$statusTexts[Response::HTTP_CONFLICT],
                        'status_code' => Response::HTTP_CONFLICT,
                    ],
                ],
            ];

            return response($response, Response::HTTP_CONFLICT, [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ]);

        }

        // user has created
        $response = [
            'data' => [
                'message' => 'User created Successfully',
                'status'  => [
                    'status_text' => Response::$statusTexts[Response::HTTP_OK],
                    'status_code' => Response::HTTP_OK,
                ],
            ],
        ];

        return response($response, Response::HTTP_OK, [
            'Accept'       => 'plain/text',
            'Content-Type' => 'application/json',
        ]);
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
