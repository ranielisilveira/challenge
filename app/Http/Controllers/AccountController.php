<?php

namespace App\Http\Controllers;

use App\Account;
use App\Enum\AccountTypes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use Exception;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function show($id)
    {
        try {
            $account = Account::with([
                'transactions',
                'user'
            ])->findOrFail($id);

            if ($account->type == AccountTypes::Company) {
                $account->makeHidden(['user']);
            } else {
                $account->makeHidden([
                    'corporate_name',
                    'trade_name',
                    'corporate_document'
                ]);
            }
            return $account;
        } catch (ModelNotFoundException $e) {
            return response([
                'message' => "A conta solicitada não existe.",
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
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
                    'agency' => 'required',
                    'number' => 'required',
                    'digit' => 'required',
                    'user_id' => 'required|exists:users,id',
                    'type' => [
                        'required',
                        Rule::in(array_keys(AccountTypes::TYPES)),
                    ],
                    'corporate_name' => [
                        Rule::requiredIf($request->type == AccountTypes::Company)
                    ],
                    'trade_name' => [
                        Rule::requiredIf($request->type == AccountTypes::Company)
                    ],
                    'corporate_document' => [
                        Rule::requiredIf($request->type == AccountTypes::Company)
                    ],
                ],
                [
                    'agency.required' => 'Você deve preencher uma agência.',
                    'number.required' => 'Você deve preencher o número da conta.',
                    'digit.required' => 'Você deve preencher os dígitos.',
                    'user_id.required' => 'Você deve informar um usuário válido.',
                    'user_id.exists' => 'O usuário informado é inválido.',
                    'type.required' => 'Você deve preencher o tipo de conta (Empresarial ou Pessoal).',
                    'type.in' => 'Você deve preencher o tipo de conta corretamente: Empresarial (1) ou Pessoal (2).',
                    'corporate_name.required' => 'Você deve preencher a razão social da empresa.',
                    'trade_name.required' => 'Você deve preencher o nome fantasia da empresa.',
                    'corporate_document.required' => 'Você deve preencher o CNPJ da empresa.',
                ]
            );

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $this->validateUniqueAccount($request);

            $newAccount = Account::create($request->all());

            return $this->show($newAccount->id);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function validateUniqueAccount(Request $request)
    {
        try {
            $account = Account::where([
                'user_id' => $request->user_id,
                'type' => $request->type
            ])->exists();

            if ($account) {
                throw new \Exception("Já existe uma conta do tipo selecionado para este usuário.");
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
