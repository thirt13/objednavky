<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Company\CompanyControlFactory;
use App\Components\Company\CompanyControl;
use Nette\Application\Attributes\Persistent;
use ProviderService;

final class AddCompanyPresenter extends BasePresenter
{

    private $offset=12;

    #[Persistent] 
    public string $search = "%";

    public function __construct(
        private CompanyControlFactory $companyControlFactory,
        private ProviderService $providerService,
        private int $id = 0,
    )
    {
        parent::__construct();
    }

    public function handleSearch(string $search): void 
    {   
       
        $lastPage = 0;
        $page = 1;
        $this->search = "%".$search."%";
        if (strlen($search) >= 1) {
           
            $this->template->page = $page;
            $this->template->items = $this->providerService->allProviders($page, $this->offset, $lastPage, $this->search);
            $this->template->lastPage = $this->providerService->lastPageProviders($this->offset, $this->search);

        } else{
            $this->search = "%";
        }

        
    }

    public function handleDelete(int $id): void
    {
        $this->providerService->del($id);
        $this->flashMessage("smazano");
    }
    
    public function handleUpdate(int $id): void
    {
        $this->id = $id;
        $this->createComponentCompanyControl();
        
    }

    public function renderDefault(int $page = 1): void
    {
        $lastPage = 0;
        //$this->providerService->transform();
        $this->template->page = $page;
        if (!$this->isAjax()) {
            $this->search = "%";
            
        }

        $this->template->items = $this->providerService->allProviders($page, $this->offset, $lastPage, $this->search);
        $this->template->lastPage = $this->providerService->lastPageProviders($this->offset, $this->search);
        if ($this->isAjax()) {
            $this->removeComponent($this->getComponent("companyControl"));
            $this->redrawControl("tbl-content");
            $this->redrawControl("component-content");
        }
        
    }

    protected function createComponentCompanyControl(): CompanyControl
	{
        $userID = $this->user->getIdentity()->getId();
		$component = $this->companyControlFactory->create($userID, $this->id);
        return $component;
    }

}
