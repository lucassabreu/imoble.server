<?php
namespace ImobleRest\Model\DAO;

use ImobleRest\Entity\Immobile;
use ImobleRest\Entity\ImmobileItem;
use Core\Model\DAO\DAOInterface;

interface ImmobileItemDAOInterface extends DAOInterface {
	public function getAllItemOfImmobile (Immobile $immobile);
}