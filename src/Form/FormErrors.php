<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class FormErrors
{
    /**
     * @param FormInterface $form
     * @return string
     */
    public function getErrors(FormInterface $form): string
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $propertyPath = str_replace('data.', '', $error->getCause()->getPropertyPath());
            $errors[$propertyPath] = $error->getMessage();
        }

        $errors = array_values($errors);
        return $errors[0];
    }
}
