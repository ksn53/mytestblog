<?php
namespace App;

use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\Length as LengthConstraint;
use Symfony\Component\Validator\Constraints\Regex as RegexConstraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\Validation;

class ValidatorController
{
    public function emailValidate($email)
    {
        $validator = Validation::createValidator();
        $emailConstraint = new EmailConstraint();
        $notBlankConstraint = new NotBlankConstraint();
        $errors = $validator->validate(
            $email,
            [$emailConstraint, $notBlankConstraint]
        );
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function namepasswdValidate($name)
    {
        $validator = Validation::createValidator();
        $lenConstraint = new LengthConstraint(['max' => 15, 'min' => 3]);
        $notBlankConstraint = new NotBlankConstraint();
        $regexConstraint = new RegexConstraint(['pattern' => '/^[a-zA-Z\p{Cyrillic}0-9\s\-]+$/u']);
        $errors = $validator->validate(
            $name,
            [$lenConstraint, $notBlankConstraint, $regexConstraint]
        );
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function fileValidate($file)
    {
        $validator = Validation::createValidator();
        $typeSize = new FileConstraint([
            'maxSize' => '2048k',
            'mimeTypes' => ['image/jpg', 'image/jpeg']
        ]);
        $errors = $validator->validate($file, [$typeSize]);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
}
