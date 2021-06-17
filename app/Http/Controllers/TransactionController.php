<?php

namespace App\Http\Controllers;

use App\Enum\TransactionTypes;
use App\Transaction;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'value' => 'required|numeric',
                    'account_id' => 'required|exists:accounts,id',
                    'type' => [
                        'required',
                        Rule::in(array_keys(TransactionTypes::TYPES)),
                    ],
                ],
                [
                    'value.required' => 'Você deve preencher o valor da transação.',
                    'value.numeric' => 'Você deve preencher corretamente o valor da transação (númerico).',

                    'account_id.required' => 'Você deve informar a conta da transação.',
                    'account_id.exists' => 'A conta informada é inválida.',

                    'type.required' => 'Você deve preencher o tipo de transação.',
                    'type.in' => 'Você deve preencher o tipo de transação corretamente: ' .  implode(", ", TransactionTypes::TYPES),

                ]
            );

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            return Transaction::create($request->all());
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
