<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Components\User\UserControlFactory;
use App\Components\User\UserControl;
use UserService;

final class AddUserPresenter extends Nette\Application\UI\Presenter
{
    
	public function __construct(
        private UserControlFactory $userControlFactory,
        private UserService $userService,
        private int $id = 0,
	) 
    {
		
	}
   
	
    public function handleDelete(int $id): void
    {
        $this->userService->del($id);
        $this->flashMessage("smazano");
    }
    
    public function handleUpdate(int $id): void
    {
        $this->id = $id;
        $this->createComponentUserControl();
        
    }


    public function renderDefault()
    {
        $this->template->items = $this->userService->allUsers();
        if ($this->isAjax()) {
            $this->removeComponent($this->getComponent("userControl"));
            $this->redrawControl("tbl-content");
            $this->redrawControl("component-content");
        }
    
    }

    protected function createComponentUserControl(): UserControl
	{
		$component = $this->userControlFactory->create($this->id);
        return $component;
    }

}
