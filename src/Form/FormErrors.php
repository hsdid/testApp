<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

class FormErrors
{
    /**
     * @param FormInterface $form
     * @return array
     */
    public function getErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true, true) as $error) {
            $propertyPath = str_replace('data.', '', $error->getCause()->getPropertyPath());
            $errors[$propertyPath] = $error->getMessage();
        }
        return $errors;
    }
}
