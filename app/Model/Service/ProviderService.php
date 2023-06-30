<?php

declare(strict_types=1);

class ProviderService
{


    public function __construct(
		  private Nette\Database\Explorer $database
    ) 
    {

    }


    public function getProvider(int $id): ?Provider
    {
        return  Provider::create($this->database->table("providers")->get($id));
    }

    public function allProviders(
        int $page, 
        int $offset, 
        int $lastPage, 
        string $search
    ): array
    {
        $question = $this->database->table("providers")->where("name LIKE ? AND active ?", $search, 1);
        if (count($question) > 10) {
          $question = $question->page($page, $offset, $lastPage);
        }
        return Provider::all($question);
    }
    
    public function allProvidersSelectOption(): array 
    {
    
        $question = $this->database->table("providers")->where("active ?", 1);
        $arr=[];
        foreach($question as $item) {
            $arr[$item->id] = $item->name;
        }
        return $arr;
    
    }

    public function lastPageProviders(
        int $offset, 
        string $search
    ): int 
    {
      $count = count($this->database->table("providers")->where("name LIKE ? AND active ?", $search, 1));
      return intdiv($count, $offset) + 1;
    }
   

    public function add(Nette\Utils\ArrayHash $values): void
    {
      $in = $this->database->table("providers")->insert($values);
    
    }

    public function upd(Nette\Utils\ArrayHash $values): void
    {
      $id = $values->id;
      unset($values->id);
      $upd = $this->database->table("providers")->where("id ?", $id)->update($values);

    }
    
    public function del(int $idCompany): void
    {
      $this->database->table("providers")->where("id ?", $idCompany)->update([
        "active" => 0,
      ]);
    
    }


    
    public function transform(): void
    {
      $selection = $this->database->table("provider");
      foreach ($selection as $item){
        $this->database->table("providers")->insert([
          "id" => $item->id,
          "name" => $item->name,
          "street" => $item->street,
          "zip" => $item->zip,
          "city" => $item->city,
          "country" => $item->country,
          "ic" => $item->ic,
          "dic" => $item->dic,
          "phone" => $item->phone,
          "email" => $item->email,
          "www" => $item->www,
          "director" => $item->director,
          "created_at" => $item->date_in,
          "account_number" => $item->account_number,
          "users_id" => $item->id_u,
          "active" => 1,
         
        ]);

      }
     
    }



}

