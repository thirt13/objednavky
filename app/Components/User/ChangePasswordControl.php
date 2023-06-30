<?php

declare(strict_types=1);

namespace App\Components\User;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use UserService;

/**
 * @property-read ChangePasswordControlTemplate $template
 */

 final class ChangePasswordControl extends Control
 {
    
	public function __construct(
        private UserService $userService,
        private int $id,
    ) 
    {

    }

    public function render(): void
	{
		$this->template->setFile(__DIR__ . '/ChangePasswordControl.latte');
		$this->template->render();
	}

    protected function createComponentChangePasswordControl(): Form
	{
      
		$obj = $this->userService->getUser($this->id);		
		
		$form = new Form;
		$form->setHtmlAttribute('class', 'ajax');

		$form->addPassword('password')
			->setRequired('Prosím vyplňte své PŮVODNÍ heslo.');
			
        $form->addPassword('passwordNew')
			->setRequired('Prosím vyplňte své NOVÉ heslo.')
			->addRule(Form::MinLength, 'Heslo musí mít alespoň %d znaků.', 6);

		$form->addHidden('id', $this->id);

		$form->addSubmit('send', 'ZMĚNIT HESLO');

		$form->onSuccess[] = [$this, 'changePasswordFormSucceeded'];

		return $form;

	}


    public function changePasswordFormSucceeded(Form $form): void
    {
		$values = $form->getValues();
		$isOK = $this->userService->updatePassword($values);
		if ($isOK) {
			$this->presenter->flashMessage('Změna hesla proběhla úspěšně.');
		}
		
    }

 }