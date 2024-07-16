<?php

namespace App\Security\Voter;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
use App\Repository\ProjetRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjetVoter extends Voter
{
    public const EDIT = 'PROJET_EDIT';
    public const VIEW = 'PROJET_VIEW';

    public function __construct(private ProjetRepository $projetRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof Employe) {
            return false;
        }
        $projet = $this->projetRepository->find($subject);
        if($projet->isArchive()) return false;
        // si le projet est associé au user return true

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            // case self::EDIT:
            //     return  $projet->getEmployes()->contains($user);

                // logic to determine if the user can EDIT
                // return true or false
                // break;
            case self::VIEW:
                // dd($user->getProjets()->contains($projet));
                return  $user->getProjets()->contains($projet);
                // logic to determine if the user can VIEW
                // return true or false
                // break;
        }

        return false;
    }
}
