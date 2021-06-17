<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $users = User::orderBy('name');

            if (!is_null($request->q) && !empty($request->q)) {
                $users->where('name', 'LIKE', "$request->q%")
                    ->orWhere('document', 'LIKE', "$request->q%");
            }

            return $users->paginate(10);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|min:3|max:255',
                    'document' => 'required|unique:users|digits:11',
                    'email' => 'required|unique:users|email',
                    'telephone' => 'required|min:10',
                    'password' => 'required|min:6',
                ],
                [
                    'name.required' => 'Você precisa preencher um nome.',
                    'name.min' => 'Precisa conter mais de 3 caracteres.',
                    'name.max' => 'Você excedeu o número de caracteres.',

                    'document.required' => 'Você precisa preencher o cpf.',
                    'document.unique' => 'Este cpf já existe.',
                    'document.digits' => 'Você precisa preencher os 11 dígitos do cpf.',

                    'email.required' => 'Você precisa preencher o email.',
                    'email.unique' => 'Este email já existe.',
                    'email.email' => 'Você precisa preencher um email válido.',

                    'telephone.required' => 'Você precisa preencher o telefone.',
                    'telephone.min' => 'Este número não é válido. Você precisa preencher no mínimo 10 números',

                    'password.required' => 'Você precisa digitar a sua senha',
                    'password.min' => 'Sua senha é muito curta',
                ]
            );

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            return User::create($request->all());
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
