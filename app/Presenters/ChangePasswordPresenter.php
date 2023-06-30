<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Components\User\ChangePasswordControlFactory;
use App\Components\User\ChangePasswordControl;
use UserService;

final class ChangePasswordPresenter extends Nette\Application\UI\Presenter
{
    
    protected function startup()
    {
        parent::startup();

        if ($this->getUser()->isInRole("admin")) {
            $this->redirect("Home:default");
        }
        
       
    }
    


	public function __construct(
        private ChangePasswordControlFactory $changePasswordControlFactory,
        private UserService $userService,
        private int $id = 0,
	) {
		
	}
   
    public function renderDefault()
    {
        $this->id = $this->user->getIdentity()->getId();
        if ($this->isAjax()) {
            $this->removeComponent($this->getComponent("changePasswordControl"));
            $this->redrawControl("component-content");
        }
    }

    protected function createComponentChangePasswordControl(): ChangePasswordControl
	{
		$component = $this->changePasswordControlFactory->create($this->id);
        return $component;
    }

}
