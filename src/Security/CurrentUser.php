<?php

namespace App\Security;

use App\Repository\BandRepository;
use App\Repository\VenueRepository;

class CurrentUser
{

    private int $userId;

    private string $role;

    private Object $entity;


    public function __construct($userId, $roles, BandRepository $bandRepository, VenueRepository $venueRepository)
    {
        if (in_array("venue", $roles)) {
            $this->role = "venue";
            $this->entity = $venueRepository->findOneBy(['user' => $userId]);
        } elseif (in_array("band", $roles)) {
            $this->role = "band";
            $this->entity = $bandRepository->findOneBy(['user' => $userId]);
        } else {
            $this->role = null;
            $this->entity = null;
        }
    }

    /**
     * Get the value of userId
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Get the value of role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get the value of entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Get the value of entity
     */
    public function getEntityId()
    {
        return $this->entity->getId();
    }
}
