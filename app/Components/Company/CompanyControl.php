<?php

declare(strict_types=1);

namespace App\Components\Company;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use ProviderService;

/**
 * @property-read CompanyControlTemplate $template
 */

 final class CompanyControl extends Control
 {
    
    public function __construct(
        private ProviderService $providerService,
        private int $userID,
        private int $id,
    ) 
    {

    }

    public function render(): void
	{
		$this->template->setFile(__DIR__ . '/CompanyControl.latte');
		$this->template->render();
	}

    protected function createComponentCompanyControl(): Form
	{
        $obj = $this->providerService->getProvider($this->id);
        
        $form = new Form;
        $form->setHtmlAttribute('class', 'ajax');
        
        $form->addText('name', 'název firmy: *')
            ->setRequired('Zadejte prosím název')
            ->addRule(Form::MIN_LENGTH, 'Jméno musí mít alespoň %d znaků', 4)
            ->setHtmlAttribute('class', 'schoolname');
        
        $form->addText('street', 'ulice: *')
            ->setRequired('Zadejte prosím ulici a číslo')
            ->setHtmlAttribute('class', 'address');
        
        $form->addText('city', 'město: *')
            ->setRequired('Zadejte prosím město')
            ->setHtmlAttribute('class', 'address');

        $form->addInteger('zip', 'PSČ: *')
            ->setRequired('Zadejte prosím PSČ')
            ->addRule(Form::Length, 'Musí obsahovat %d čísel', 5);

        $form->addText('country', 'země: ');
  
        $form->addText('phone', 'telefon: *')
            ->setRequired('Zadejte platné telefonní číslo')
            ->setOption('description', '(bez mezer)');
  
        $form->addEmail('email', 'e-mail: *')
            ->setRequired()
            ->setHtmlAttribute('class', 'address');

        $form->addInteger('ic', 'IČ: *')
            ->setRequired('Zadejte prosím IČ')
            ->setOption('description', '(bez mezer)');
        
        $form->addText('dic', 'DIČ: ')
            ->setOption('description', '(bez mezer)');

        $form->addText('www', 'web: ')
            ->setHtmlAttribute('class', 'address');

        $form->addText('director', 'kontaktni osoba: ')
            ->setHtmlAttribute('class', 'address');

        $form->addText('account_number', 'číslo účtu: ')
            ->setHtmlAttribute('class', 'address');
   
        $form->addHidden('users_id', $this->userID);
        $form->addHidden('active', 1);
        $form->addHidden('id', $this->id);

        if ($obj !== null) {
            $form->setDefaults($obj);
            $form->addSubmit('send', 'upravit firmu');
        } else {
            $form->addSubmit('send', 'přidat firmu');
        }

		$form->onSuccess[] = [$this, 'addCompanySucceeded'];

		return $form;

	}


    public function addCompanySucceeded(Form $form): void
    {
		$values = $form->getValues();
        if ($values->id != 0) {
            $this->providerService->upd($values);
            $this->presenter->flashMessage('Úprava firmy proběhla úspěšně.');
        } else {
            $this->providerService->add($values);
            $this->presenter->flashMessage('Přidání firmy proběhlo úspěšně.');
        }
        
        $form->getPresenter()->payload->closeForm = true;
       
    }

 }