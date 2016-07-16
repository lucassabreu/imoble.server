<?php

namespace ImobleRest\Model\Doctrine;

use ImobleRest\Entity\Immobile;
use ImobleRest\Model\DAO\ImmobileItemDAOInterface;
use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;

class ImmobileItemDAODoctrine extends AbstractDoctrineDAO implements ImmobileItemDAOInterface {
	
    public function __construct() {
        parent::__construct('ImobleRest\Entity\ImmobileItem');
    }

    public function getAllItemOfImmobile (Immobile $immobile) {
        if ($immobile === null)
            throw new Exception("invalid parameter");

        return $this->getQuery(['immobile' => $immobile])->getQuery()->execute();
    }
}