<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\Order\OrderControlFactory;
use App\Components\Order\OrderControl;
use ProviderService;

final class AddOrderPresenter extends BasePresenter
{

    public function __construct(
        private OrderControlFactory $orderControlFactory,
        private ProviderService $providerService,
       
    )
    {
        parent::__construct();

    }

    public function renderDefault(): void
    {

        if ($this->isAjax()) {
            $this->removeComponent($this->getComponent("orderControl"));
            $this->redrawControl("component-content");
        }
        
    }

    protected function createComponentOrderControl(): OrderControl
	{
        $userID = $this->user->getIdentity()->getId();
		$component = $this->orderControlFactory->create($userID, 0);
        return $component;
    }

}
