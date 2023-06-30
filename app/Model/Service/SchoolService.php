<?php

declare(strict_types=1);

// use Nette\Database\Table\Selection;

class SchoolService
{


    public function __construct(
		    private Nette\Database\Explorer $database
    ) 
    {

    }


    public function getSchool(int $id): ?School
    {
        return  School::create($this->database->table("school")->get($id));
    }

    public function getSchoolID(): ?School
    {
      return  School::actual($this->database->table("school")->where("active ?", 1)->order('id DESC')->limit(1));
    }

    public function allSchools(
        int $page, 
        int $offset, 
        int $lastPage
    ): array
    {
       
        $question = $this->database->table("school")->where("active ?", 1)->order("id DESC");
        if (count($question) > 12) {
          $question = $question->page($page, $offset, $lastPage);
        }

        return School::all($question);

    }
    

    public function lastPageSchools(int $offset): int 
    {
        $count = count($this->database->table("school")->order("id DESC"));
        return intdiv($count, $offset) + 1;
    }
   

    public function add(Nette\Utils\ArrayHash $values): void
    {
        $in = $this->database->table("school")->insert($values);
    }

    public function upd(Nette\Utils\ArrayHash $values): void
    {
        $id = $values->id;
        unset($values->id);
        $upd = $this->database->table("school")->where("id ?", $id)->update($values);

    }
    
    public function del(int $order_id): void
    {
        $this->database->table("school")->where("id ?", $order_id)->update([
          "active" => 0,
        ]);
    
    }

}

