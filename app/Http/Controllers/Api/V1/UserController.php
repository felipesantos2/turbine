<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        // returns a json automatically
        $users = User::query();
        // localhost/api/users?id=name&order=desc
        // localhost/api/users?orderby=id&order=asc&limit=2
        
        $orderby = $request->query('orderby');
        $order = $request->query('order');
        $limit = $request->query('limit');

        if ($orderby && $order) {
            $users->orderby($orderby, $order);
        }

        if ($limit) {
            $users->limit($limit);
        }

        $users = $users->get();

        return UserResource::collection($users) ;
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
            $created = User::create($credentials);
        } catch (Exception $erro) {
            // return is was error
            $response = [
                'data' => [
                    'message' => 'The user was not created!', // $erro->getMessage(),
                    'status'  => [
                        'status_text' => Response::$statusTexts[Response::HTTP_CONFLICT],
                        'status_code' => Response::HTTP_CONFLICT,
                    ],
                    'request' => [
                        'uri'    => $request->path(),
                        'url'    => $request->url(),
                        'method' => $request->method(),
                    ],
                ],
            ];

            return response($response, Response::HTTP_CONFLICT, [
                'Content-Type'         => 'application/json',
                'X-Header-Author-Name' => 'Felipe Pinheiro dos Santos',
            ]);
        }

        // user has created
        $response = [
            'data' => [
                'message' => 'User created Successfully',
                'status'  => [
                    'status_text' => Response::$statusTexts[Response::HTTP_CREATED],
                    'status_code' => Response::HTTP_CREATED,
                ],
                'request' => [
                    'uri'    => $request->path(),
                    'url'    => $request->url(),
                    'method' => $request->method(),
                    'body'   => [
                        'user' => new UserResource($created),
                    ],
                ],
            ],
        ];

        return response($response, Response::HTTP_CREATED, [
            'Content-Type'         => 'application/json',
            'X-Header-Author-Name' => 'Felipe Pinheiro dos Santos',
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
