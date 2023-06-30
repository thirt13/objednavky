<?php

declare(strict_types=1);

namespace App\Components\User;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use UserService;

/**
 * @property-read SignInControlTemplate $template
 */

 final class UserControl extends Control
 {

	public function __construct(
        private UserService $userService,
        private int $id,
    ) 
    {

    }

    public function render(): void
	{
		$this->template->setFile(__DIR__ . '/UserControl.latte');
		$this->template->render();
	}

    protected function createComponentUserControl(): Form
	{
        $roles = [
			        'admin' => 'administrátor',
			        'member' => 'uživatel',
			     ];
		$obj = $this->userService->getUser($this->id);		
		
		$form = new Form;
		$form->setHtmlAttribute('class', 'ajax');

		$form->addText('name', 'jméno:')
			->setRequired('Prosím vyplňte celé jméno.');

		$form->addText('username', 'uživatelské jméno:')
			->setRequired('Prosím vyplňte uživatelské jméno.');

		$form->addPassword('password', 'heslo:');

		$form->addSelect('role', 'funkce:', $roles)
			->setPrompt('Zvolte funkci uživatele')
			->setRequired('zvolte funkci');
		
		$form->addCheckbox('active', 'aktivní uživatel')
			->setDefaultValue('true');
		
		$form->addHidden('id', $this->id);

		if ($obj !== null) {
			$form->setDefaults($obj);
			$form->addSubmit('send', 'upravit uživatele');
		} else {
			$form->addSubmit('send', 'přidat uživatele');
		}

		$form->onSuccess[] = [$this, 'userFormSucceeded'];

		return $form;

	}


    public function userFormSucceeded(Form $form): void
    {
		$values = $form->getValues();
		if ($values->id != 0) {
            $this->userService->upd($values);
            $this->presenter->flashMessage('Úprava uživatele proběhla úspěšně.');
        } else {
            $this->userService->add($values);
            $this->presenter->flashMessage('Přidání uživatele proběhlo úspěšně.');
        }
        
        $form->getPresenter()->payload->closeForm = true;


    }

 }