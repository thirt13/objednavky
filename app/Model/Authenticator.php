<?php

namespace App\Model;


use Nette;
use Nette\Security\SimpleIdentity;
use Nette\Security\IIdentity;
use UserService;

class Authenticator implements Nette\Security\Authenticator, Nette\Security\IdentityHandler
{
	

	public function __construct(
        private UserService $userService,
		private Nette\Security\Passwords $passwords
	) 
	{
	
	}

	public function authenticate(string $username, string $password): SimpleIdentity
	{
		$row = $this->userService->findUser($username);
        
		if (!$row) {
			throw new Nette\Security\AuthenticationException("User not found.");
		}

		if (!$this->passwords->verify($password, $row->password)) {
			throw new Nette\Security\AuthenticationException("Invalid password.");
		}
		$now = new \DateTime();
		$this->userService->loginHistory($row->id, $now);
		
		return new SimpleIdentity(
			$row->id,
			$row->role,
			["username" => $row->username, "name" => $row->name, "date" => $now->format("d. m. Y  H:i:s")]
		);
	}
    
	public function sleepIdentity(IIdentity $identity): IIdentity
	{
		// zde lze pozměnit identitu před zápisem do úložiště po přihlášení,
		return $identity;
	}

	public function wakeupIdentity(IIdentity $identity): ?IIdentity
	{
		// aktualizace rolí v identitě
		// $userId = $identity->getId();
		// $identity->setRoles($this->facade->getUserRoles($userId));
		 return $identity;
		//return null;
	}

}
