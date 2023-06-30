<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\UI\Presenter;



abstract class BasePresenter extends Presenter
{
	
	public function startup()
    {
        parent::startup();

        if ($this->getUser()->isLoggedIn() === false) {
            $this->redirect("Home:default");
        }
    }
	
	
	protected function beforeRender(): void
	{
		parent::beforeRender();

		$this->redrawControl("flash-message");
		$this->redrawControl("tbl-content");
		
		
	}
}