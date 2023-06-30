<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Order\OrderControl;
use App\Components\Order\OrderControlFactory;
use App\Components\Company\CompanyControl;
use Nette\Application\Attributes\Persistent;
use OrderService;

final class ArchivePresenter extends BasePresenter
{

    private $offset=14;

  

    public function __construct(
        private OrderControlFactory $orderControlFactory,
        private OrderService $orderService,
        private int $id = 0,
    )
    {
        parent::__construct();
    }

    public function renderDefault(int $year, int $page = 1): void
    {
        $lastPage = 0;

        $this->template->page = $page;
        $this->template->year = $year;
    
        $this->template->items = $this->orderService->allOrders($page, $this->offset, $lastPage, $year);
        $this->template->lastPage = $this->orderService->lastPageOrders($this->offset, $year);
        
        if ($this->isAjax()) {
            $this->removeComponent($this->getComponent("orderControl"));
            $this->redrawControl("tbl-content");
            $this->redrawControl("component-content");
        }
        
    }

    protected function createComponentOrderControl(): OrderControl
	{
        $userID = $this->user->getIdentity()->getId();
		$component = $this->orderControlFactory->create($userID, $this->id);
        return $component;
    }

}
