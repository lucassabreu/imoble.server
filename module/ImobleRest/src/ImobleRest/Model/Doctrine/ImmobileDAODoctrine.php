<?php

namespace ImobleRest\Model\Doctrine;

use ImobleRest\Model\DAO\ImmobileDAOInterface;
use Core\Model\DAO\Doctrine\AbstractDoctrineDAO;

class ImmobileDAODoctrine extends AbstractDoctrineDAO implements ImmobileDAOInterface {
	
    public function __construct() {
        parent::__construct('ImobleRest\Entity\Immobile');
    }

}