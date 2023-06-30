<?php

declare(strict_types=1);


class UserService
{

    public function __construct(
		private Nette\Database\Explorer $database,
        private Nette\Security\Passwords $passwords,
    ) 
    {

    }

    public function getUser(int $id): ?User
    {
        return  User::create($this->database->table("users")->get($id));

    }

    public function findUser(string $username): ?User
    {
        return  User::create($this->database->table("users")->where("username ? AND active ?", $username, 1)->fetch());

    }

    public function loginHistory(int $id, DateTime $now): void 
    {
        $this->database->table("history_login")->insert([
            "users_id" => $id,
            "last_login" => $now
        ]);
    }

    public function allUsers(): array
    {
       
        $question = $this->database->table("users")->order("name ASC, role ASC");
        return User::all($question);

    }



    public function add(Nette\Utils\ArrayHash $values): void
    {
      
        if ($values->password != "") {
           $values->password = $this->passwords->hash($values->password);    
        }
        $in = $this->database->table("users")->insert($values);

    
    }

    public function upd(Nette\Utils\ArrayHash $values): void
    {
        $id = $values->id;
        unset($values->id);
        if ($values->password != "") {
            $values->password = $this->passwords->hash($values->password);    
        }
        $upd = $this->database->table("users")->where("id ?", $id)->update($values);

    }

    public function del(int $idUser): void
    {
        $this->database->table("users")->where("id ?", $idUser)->delete();
    }

    public function updatePassword(Nette\Utils\ArrayHash $values): bool 
    {
       
        $obj = $this->getUser((int)$values->id);

        if (!$this->passwords->verify($values->password, $obj->password)) {
			throw new \Nette\Security\AuthenticationException("Invalid password.");
		}
		if ($values->passwordNew != "") {
            $this->database->table("users")->where("id ?", $values->id)->update([
                "password" => $this->passwords->hash($values->passwordNew),
            ]);
		  
	   } 
        
        return true;
        
       
    }

}

