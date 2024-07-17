<?php

namespace App\Security\Voter;

use App\Entity\Employe;
use App\Repository\ProjetRepository;
use App\Repository\TacheRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjetVoter extends Voter
{
    public function __construct(
        private ProjetRepository $projetRepository,
        private TacheRepository $tacheRepository
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === "projet_view" || $attribute === "tache_view";
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        $user = $token->getUser();

        if ($attribute === "projet_view") {
            $projet = $this->projetRepository->find($subject);
        } else {
            $tache = $this->tacheRepository->find($subject);
            $projet = $tache->getProjet();
        }

        if (!$user instanceof Employe || !$projet) {
            return false;
        }

        return $user->isVerified() || $projet->getEmployes()->contains($user);
    }
}
