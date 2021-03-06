<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Questionnaire\{DeleteQuestionnaireRequest, StoreQuestionnaireRequest, UpdateQuestionnaireRequest};
use App\Http\Resources\QuestionnaireResource;
use App\Models\Questionnaire;
use Illuminate\Support\Facades\Log;

class QuestionnaireController extends Controller
{
    public function index()
    {
        $questionnaires = Questionnaire::with([
            'topics',
            'questions',
        ])->paginate();

        return QuestionnaireResource::collection($questionnaires);
    }

    public function show(Questionnaire $questionnaire)
    {
        $questionnaire->load([
            'topics',
            'questions',
        ]);

        return new QuestionnaireResource($questionnaire);
    }

    public function store(StoreQuestionnaireRequest $request)
    {
        $questionnaire = Questionnaire::create($request->validated());
        $questionnaire->topics()->sync($request->topics);

        return new QuestionnaireResource($questionnaire);
    }

    public function update(UpdateQuestionnaireRequest $request, Questionnaire $questionnaire)
    {
        $questionnaire->update($request->validated());
        $questionnaire->topics()->sync($request->topics);

        return new QuestionnaireResource($questionnaire);
    }

    public function destroy(DeleteQuestionnaireRequest $request, Questionnaire $questionnaire)
    {
        Log::debug($request);

        $questionnaire->topics()->sync([]);
        $questionnaire->delete();

        return response()->noContent();
    }
}
