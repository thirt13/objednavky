<?php

declare(strict_types=1);

namespace App\Components\Order;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use ProviderService;
use OrderService;
use SchoolService;

/**
 * @property-read OrderControlTemplate $template
 */

 final class OrderControl extends Control
 {
    
    public function __construct(
        private ProviderService $providerService,
        private OrderService $orderService,
        private SchoolService $schoolService, 
        private int $userID,
        private int $id,
    ) 
    {

    }

    public function render(): void
	{
		$this->template->setFile(__DIR__ . '/OrderControl.latte');
		$this->template->render();
	}

    protected function createComponentOrderControl(): Form
	{
        $order_number = $this->orderService->maxNumber();
        $providers = $this->providerService->allProvidersSelectOption();
        $obj = $this->orderService->getOrder($this->id);
        //
        $o_n = $order_number !== null ? $order_number+1 : 1;
        $form = new Form;
        $form->setHtmlAttribute('class', 'ajax');

        $form->addInteger('order_number', 'číslo objednávky *')
            ->setRequired('Zadejte číslo objednávky')
            ->setDefaultValue($o_n)
            ->addRule($form::Range, 'Úroveň musí být v rozsahu mezi %d a %d.', [0, 1000]);

        $form->addSelect('providers_id', 'název firmy', $providers)
            ->setRequired()
            ->setPrompt('--vyberte firmu--');
       
        $form->addTextArea('order_item', 'objednávka*')
            ->setHtmlAttribute('class', 'textarea-big')
            ->setRequired('Zadejte, co chcete objednat');
    
        $form->addText('finish', 'termín dodání');

        $form->addInteger('price', 'předběžná cena*')
            ->setRequired('Zadejte předběžnou cenu');

        $form->addText('director', 'kontaktní osoba')
            ->setHtmlAttribute('class', 'address');

        $form->addTextArea('detail', 'detail objednávky')
            ->setHtmlAttribute('class', 'textarea-big')
            ->setDefaultValue('Po splnění objednávky, zašlete fakturu na adresu odběratele. Na faktuře uvádějte plné znění názvu školy bez zkratek.');

        $form->addHidden("year", \Date("Y"));
        $form->addHidden("id", $this->id);
        
        
        $form->addCheckbox('active', 'ukončená transakce')
            ->setHtmlAttribute('class', 'settings-uprava');

        if ($obj !== null) {
            $form->addHidden("school_id");
            $form->addHidden("users_id", $obj->users_id);
            $form->setDefaults($obj);
            $c = $obj->active == 0 ? 1:0;
            $form["active"]->setDefaultValue($c);
            $form->addSubmit('send', 'upravit firmu');
           
        } else {
            $school = $this->schoolService->getSchoolID();
            bdump($school);
            $form->addHidden("school_id", $school->id);
            $form->addHidden("users_id", $this->userID);
            $form->addSubmit('send', 'přidat firmu');
        }

		$form->onSuccess[] = [$this, 'addOrderSucceeded'];

		return $form;

	}


    public function addOrderSucceeded(Form $form): void
    {
		$values = $form->getValues();
        if ($values->id != 0) {
            $id = (int) $values->id;
            $this->orderService->upd($values);
            $this->presenter->flashMessage('Úprava firmy proběhla úspěšně.');
            $this->createPDF($id);
        } else {
            $newId = $this->orderService->add($values);
            $this->presenter->flashMessage('Vytvoření nové objednávky proběhlo úspěšně.');
            $this->createPDF($newId);
        }
        
        $form->getPresenter()->payload->closeForm = true;
    }

    private function createPDF(int $id): void 
    {
        $template = $this->createTemplate();
	    $template->setFile(__DIR__ . "\pdf.latte");
        $template2 = $this->createTemplate();
	    $template2->setFile(__DIR__ . "\pdf2.latte");

        $order = $this->orderService->getOrder($id);
        $school = $this->schoolService->getSchool($order->school_id);
        $provider = $this->providerService->getProvider($order->providers_id);

        $template->school = $school;
        $template->provider = $provider;
        $template->order = $order;

        $mpdf = new \Mpdf\Mpdf();
	    $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML((string) $template);
        $file_name = sprintf("%03d", $order->order_number).$order->year.'_objednavka'.$order->id;

        $mpdf->OutputFile('objednavky\\'.$order->year.'\\'.$file_name.'.pdf');

        $template2->school = $school;
        $template2->provider = $provider;
        $template2->order = $order;

        $mpdf2 = new \Mpdf\Mpdf();
	    $mpdf2->SetDisplayMode('fullpage');
        $mpdf2->WriteHTML((string) $template2);
        $file_name2 = $file_name.'-1';
        $mpdf2->OutputFile('objednavky\\'.$order->year.'\\'.$file_name2.'.pdf');

    }

 }