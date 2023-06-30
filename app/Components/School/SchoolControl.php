<?php

declare(strict_types=1);

namespace App\Components\School;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use SchoolService;

/**
 * @property-read SchoolControlTemplate $template
 */

 final class SchoolControl extends Control
 {
    
    public function __construct(
        private SchoolService $schoolService,
        private int $id,
    ) 
    {

    }

    public function render(): void
	{
		$this->template->setFile(__DIR__ . '/SchoolControl.latte');
		$this->template->render();
	}

    protected function createComponentSchoolControl(): Form
	{
        $obj = $this->schoolService->getSchool($this->id);
        
        $form = new Form;
        $form->setHtmlAttribute('class', 'ajax');
        $form->addText('name', 'název školy: *')
            ->setRequired('Zadejte prosím název')
            ->addRule(Form::MinLength, 'Jméno musí mít alespoň %d znaků', 4)
            ->setHtmlAttribute('class', 'schoolname');
        $form->addText('street', 'ulice: *')
            ->setRequired('Zadejte prosím ulici a číslo')
            ->addRule(Form::MinLength, 'Ulice musí mít alespoň %d znaků', 4)
            ->setHtmlAttribute('class', 'address');
        
        $form->addText('city', 'město: *')
            ->setRequired('Zadejte prosím město')
            ->setHtmlAttribute('class', 'address');

        $form->addInteger('zip', 'PSČ: *')
            ->setRequired('Zadejte prosím PSČ')
            ->addRule(Form::Length, 'Musí obsahovat %d čísel', 5);
           
        $form->addText('country', 'země: ')
            ->setDefaultValue("Česká republika");
  
        $form->addText('phone', 'telefon: *')
            ->setRequired('Zadejte platné telefonní číslo')
            ->setOption('description', '(bez mezer)');
  
        $form->addEmail('email', 'e-mail: *')
            ->setRequired()
            ->addRule($form::Email)
            ->setHtmlAttribute('class', 'address');

        $form->addInteger('ic', 'IČ: ')
            ->setOption('description', '(bez mezer)');
          
        
        $form->addText('dic', 'DIČ: ')
            ->setOption('description', '(bez mezer)');

        $form->addText('www', 'web: ')
            ->setHtmlAttribute('class', 'address');

        $form->addText('director', 'kontaktni osoba: ')
            ->setHtmlAttribute('class', 'address');

        $form->addText('account_number', 'číslo účtu: ')
            ->setHtmlAttribute('class', 'address');
   
        $form->addHidden('active', 1);
        $form->addHidden('id', $this->id);

        if ($obj !== null) {
            $form->setDefaults($obj);
            $form->addSubmit('send', 'upravit záznam');
        } else {
            $form->addSubmit('send', 'přidat záznam');
        }

		$form->onSuccess[] = [$this, 'addSchoolSucceeded'];

		return $form;

	}


    public function addSchoolSucceeded(Form $form): void
    {
		$values = $form->getValues();
        if ($values->id != 0) {
            $this->schoolService->upd($values);
            $this->presenter->flashMessage('Úprava firmy proběhla úspěšně.');
        } else {
            $this->schoolService->add($values);
            $this->presenter->flashMessage('Přidání firmy proběhlo úspěšně.');
        }
        
        $form->getPresenter()->payload->closeForm = true;
       
    }

 }