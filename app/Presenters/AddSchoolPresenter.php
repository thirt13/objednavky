<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\School\SchoolControlFactory;
use App\Components\School\SchoolControl;
use SchoolService;

final class AddSchoolPresenter extends BasePresenter
{

    private $offset = 12;

   
    public function __construct(
        private SchoolControlFactory $schoolControlFactory,
        private SchoolService $schoolService,
        private int $id = 0,
    )
    {
        parent::__construct();
    }


    public function handleDelete(int $id): void
    {
        $this->schoolService->del($id);
        $this->flashMessage("smazano");
    }
    
    public function handleUpdate(int $id): void
    {
        $this->id = $id;
        $this->createComponentSchoolControl();
        
    }

    public function renderDefault(int $page = 1): void
    {
        $lastPage = 0;
        $this->template->page = $page;

        $this->template->items = $this->schoolService->allSchools($page, $this->offset, $lastPage);
        $this->template->lastPage = $this->schoolService->lastPageSchools($this->offset);

        if ($this->isAjax()) {
            $this->removeComponent($this->getComponent("schoolControl"));
            $this->redrawControl("tbl-content");
            $this->redrawControl("component-content");
        }
        
    }

    protected function createComponentSchoolControl(): SchoolControl
	{
		$component = $this->schoolControlFactory->create($this->id);
        return $component;
    }

}
