<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class MovieValidator extends AbstractValidator
{
    protected function getConstraint(): Collection
    {
        return new Collection([
            'title' => $this->getTitleRules(),
            'year' => $this->getYearRules(),
            'tags' => $this->getTagsRules()
        ]);
    }

    private function msgNotBlank(): string
    {
        return 'Поле обязательно к заполнению';
    }

    private function msgNotNull(): string
    {
        return 'Поле не должно быть равным null';
    }

    private function msgIncorrectYear(): string
    {
        return 'Поле не должно быть равным null';
    }

    private function getTitleRules(): array
    {
        return [
            new Assert\NotNull(['message' => $this->msgNotNull()]),
            new Assert\NotBlank(['message' => $this->msgNotBlank()]),
            new Assert\Length(['min' => 1, 'max' => 255, 'minMessage' => 'Слишком короткое название', 'maxMessage' => 'Слишком длинное название'])
        ];
    }

    private function getYearRules(): array
    {
        return [
            new Assert\NotNull(['message' => $this->msgNotNull()]),
            new Assert\NotBlank(['message' => $this->msgNotBlank()]),
            new Assert\Type(['type' => 'integer', 'message' => 'Поле должно быть числом']),
            new Assert\Range(['min' => 1900, 'max' => 2100, 'minMessage' => $this->msgIncorrectYear(), 'maxMessage' => $this->msgIncorrectYear()])
        ];
    }

    private function getTagsRules(): array
    {
        return [
            new Assert\NotNull(['message' => $this->msgNotNull()]),
            new Assert\Type(['type' => 'array', 'message' => 'Поле должно быть массивом']),
            new Assert\Count(['min' => 1, 'minMessage' => 'У фильма должен быть хотя бы 1 тэг', 'max' => 255, 'maxMessage' => 'Много тэгов'])
        ];
    }
}