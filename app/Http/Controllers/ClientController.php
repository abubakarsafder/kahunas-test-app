<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function getClientProfile(Request $request)
    {
        $client = User::find($request->id);

        if (empty($client)) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => 'Client not found.']
                    ],
                    'data' => []
                ],
                404
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Client Profile.']
                ],
                'data' => [
                    'client' => $client
                ]
            ],
            200
        );
    }

    public function updateClientProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => $validator->errors()->all()[0]]
                    ],
                    'data' => []
                ],
                400
            );
        }

        $client = User::find($request->id);

        if (empty($client)) {
            return response()->json(
                [
                    'status' => 'error',
                    'messages' => [
                        ['type' => 'error', 'message' => 'Client not found.']
                    ],
                    'data' => []
                ],
                404
            );
        }

        $client->update($validator->validated());

        return response()->json(
            [
                'status' => 'success',
                'messages' => [
                    ['type' => 'success', 'message' => 'Client updated successfully.']
                ],
                'data' => [
                    'client' => $client
                ]
            ],
            200
        );
    }

    // public function createClientProfile(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:clients,email',
    //         'client_id' => 'nullable|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(
    //             [
    //                 'status' => 'error',
    //                 'messages' => [
    //                     ['type' => 'error', 'message' => $validator->errors()->all()[0]]
    //                 ],
    //                 'data' => []
    //             ],
    //             400
    //         );
    //     }

    //     $client = Client::create($validator->validated());

    //     return response()->json(
    //         [
    //             'status' => 'success',
    //             'messages' => [
    //                 ['type' => 'success', 'message' => 'Client created successfully.']
    //             ],
    //             'data' => [
    //                 'client' => $client
    //             ]
    //         ],
    //         201
    //     );
    // }

    // public function deleteClientProfile(Request $request)
    // {
    //     $client = Client::find($request->id);
    //     if (empty($client)) {
    //         return response()->json(
    //             [
    //                 'status' => 'error',
    //                 'messages' => [
    //                     ['type' => 'error', 'message' => 'Client not found.']
    //                 ],
    //                 'data' => []
    //             ],
    //             404
    //         );
    //     }

    //     $client->delete();

    //     return response()->json(
    //         [
    //             'status' => 'success',
    //             'messages' => [
    //                 ['type' => 'success', 'message' => 'Client deleted successfully.']
    //             ],
    //             'data' => []
    //         ],
    //         200
    //     );
    // }
}
