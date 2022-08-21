<?php

declare(strict_types=1);

namespace App\Service\Catalog;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorMessageHelper
{
    public function __construct(private null|bool $isValid = null)
    {
    }

    public function getMessageFromErrors(ConstraintViolationListInterface $errors): array
    {
        $errorMessages = [];

        foreach ($errors as $error){
            $errorMessages[] = $error->getMessage();
        }

        $this->setIsValid($errorMessages);

        return $errorMessages;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    private function setIsValid(array $errorMessages): void
    {
        $this->isValid = (empty($errorMessages) && !isset($this->isValid))
            || (empty($errorMessages) && isset($this->isValid) && $this->isValid);
    }
}
