<?php 
namespace ImobleRest\Service;

use Core\Model\DAO\Exception\DAOException;
use Core\Model\Entity\Entity;
use Core\Service\AbstractDAOService;
use ImobleRest\Entity\Immobile;
use ImobleRest\Entity\ImmobileItem;
use ImobleRest\Model\DAO\ImmobileItemDAOInterface;

class ImmobileItemService extends AbstractDAOService implements ImmobileItemDAOInterface {

    protected $immobileDAOService = null;

    public function getImmobileDAOService () {
        if ($this->immobileDAOService === null)
            $this->immobileDAOService = $this->getService("ImobleRest\Service\ImmobileService");

        return $this->immobileDAOService;
    }

    public function getImmobile($id) {
        return $this->getImmobileDAOService()->findById($id);
    }

    public function getAllItemOfImmobile (Immobile $immobile) {
        if ($immobile === null)
            throw new Exception("invalid parameter");

        return $this->dao->getAllItemOfImmobile($immobile);
    }

	public function save($ent, array $values = null) {
        /* @var $immobileItem ImmobileItem */

        if ($ent === null) {
            $immobileItem = new ImmobileItem;
            unset($values['id']);
        } else {
            if (!($ent instanceof ImmobileItem)) {
                $immobileItem = $this->findById($ent);
            } else {
                $immobileItem = $ent;
            }
        }

        if ($values['immobile'] instanceof Immobile) {
            $immobile = $this->getImmobile($values['immobile']->id);
        } else {
            $immobile = $this->getImmobile($values['immobile']);
        }

        if ($immobileItem->immobile !== null 
        &&  $immobileItem->immobile->id != $immobile->id)
            throw new DAOException(sprintf("O imóvel do item não pode ser alterado !"));

        $values['immobile'] = $immobile;

        if (!isset($values['status']) || 
            ($values['status'] !== "selled" && $values['status'] !== "to_sell" 
          && $values['status'] !== "rented" && $values['status'] !== "to_rent"))
            throw new DAOException(sprintf("Deve ser informado a Situação do Item !"));

        if (!isset($values['name']) || trim($values['name']) === "")
            throw new DAOException(sprintf("Deve ser informado o nome para o Item !"));
        else
            $values['name'] = trim($values['name']);

        if (!isset($values['description']))
            $values['description'] = "";

        $immobileItem->setData($values);
     	$immobileItem = parent::save($immobileItem);

        return $immobileItem;
    }
}