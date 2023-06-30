<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Sign\SignInControlFactory;
use App\Components\Sign\SignInControl;
use Nette\Application\UI\Presenter;
use Nette;


final class HomePresenter extends Presenter
{

    public function __construct(
        private SignInControlFactory $signInControlFactory
    )
    {
        parent::__construct();
    }

    public function renderDefault()
    {
        $this->setLayout("home.signIn");
        $this->template->setFile(__DIR__ . "\..\Components\Sign\SignInControl.latte");
        
    }

    public function actionSignOut()
    {
        $this->getUser()->logout(true);
        $this->flashMessage("Jste úspěšně odhlášeni.");
        $this->redirect("default");
    }

    protected function createComponentSignInControl(): SignInControl
	{
		$component = $this->signInControlFactory->create();
		$component->onChange[] = function (Nette\Application\UI\Form $form) {
            try 
            {   
                $values = $form->getValues();
                $this->getUser()->login($values->username, $values->password);
                $this->redirect("Admin:default");
    
            } catch (\Nette\Security\AuthenticationException $e) {
                $this->flashMessage("Nesprávné přihlašovací jméno nebo heslo.");
            }
		};

		return $component;
	}

}
