<?php

declare(strict_types=1);

namespace App\Components\Sign;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

/**
 * @property-read SignInControlTemplate $template
 */

 final class SignInControl extends Control
 {
    
	/** @var callable[] */
	public array $onChange = [];

    public function render(): void
	{
		$this->template->setFile(__DIR__ . '/SignInControl.latte');
		$this->template->render();
	}

    protected function createComponentSignInControl(): Form
	{
        $form = new Form;

		$form->addText('username', '')
			->setRequired('Prosím vyplňte své uživatelské jméno.')
			->setHtmlAttribute('placeholder', 'uživatelské jméno');

		$form->addPassword('password', '')
			->setRequired('Prosím vyplňte své heslo.')
			->setHtmlAttribute('placeholder', 'heslo');

		$x = $form->addSubmit('send', 'Přihlásit');

		$x->onClick[] = function (Form $form): void 
			{
				$this->onChange($form);
			};

		return $form;

	}

 }