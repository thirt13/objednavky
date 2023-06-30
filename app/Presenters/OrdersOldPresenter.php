<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Order\OrderControl;
use App\Components\Order\OrderControlFactory;
use OrderService;

final class OrdersOldPresenter extends BasePresenter
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

    public function handleDelete(int $id): void
    {
        $this->orderService->del($id);
        $this->flashMessage("smazano");
    }
    
    public function handleUpdate(int $id): void
    {
        $this->id = $id;
     
        $this->createComponentOrderControl();
        $this->redrawControl("component-content");
       
    }

    public function renderDefault(int $page = 1): void
    {
        $lastPage = 0;
        $year = Date("Y") - 1;
        $this->template->page = $page;
    
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
