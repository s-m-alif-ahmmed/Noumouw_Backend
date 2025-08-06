<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function __construct($resource, $correctAnswers)
    {
        parent::__construct($resource);
        $this->correctAnswers = $correctAnswers;
    }

    public function toArray(Request $request): array
    {
        return [
            'total_questions' => $this->resource->questions->count(),
            'correct_answers' => $this->correctAnswers,
        ];
    }
}
