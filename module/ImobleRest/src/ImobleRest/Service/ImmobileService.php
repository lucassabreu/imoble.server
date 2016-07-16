<?php 
namespace ImobleRest\Service;

use Core\Model\DAO\Exception\DAOException;
use Core\Model\Entity\Entity;
use Core\Service\AbstractDAOService;
use ImobleRest\Entity\Immobile;
use ImobleRest\Model\DAO\ImmobileDAOInterface;

class ImmobileService extends AbstractDAOService implements ImmobileDAOInterface {

    protected $immobileItemDAOService = null;

    public function getimmobileItemDAOService () {
        if ($this->immobileItemDAOService === null)
            $this->immobileItemDAOService = $this->getService("ImobleRest\Service\ImmobileService");

        return $this->immobileItemDAOService;
    }

	public function save($ent, array $values = null) {
        /* @var $immobile immobile */

        if ($ent === null) {
            $immobile = new Immobile;
            unset($values['id']);
        } else {
            if (!($ent instanceof Immobile)) {
                $immobile = $this->findById($ent);
            } else {
                $immobile = $ent;
            }

            $values['type'] = $immobile->type;
        }

        $values['items'] = $immobile->items;

        if (!isset($values['type']) || 
            ($values['type'] !== "house" && $values['type'] !== "subdivision" && $values['type'] !== "building"))
            throw new DAOException(sprintf("Deve ser informado o Tipo do Imóvel"));

        if (!isset($values['status']) || 
            ($values['status'] !== "selled" && $values['status'] !== "to_sell" 
          && $values['status'] !== "rented" && $values['status'] !== "to_rent"))
            throw new DAOException(sprintf("Deve ser informado a Situação do Imóvel !"));

        if (!isset($values['name']) || trim($values['name']) === "")
            throw new DAOException(sprintf("Deve ser informado o nome para o Imóvel !"));
        else
            $values['name'] = trim($values['name']);

        if (!isset($values['value']) || !is_numeric($values['value']))
            throw new DAOException(sprintf("Valor do Imóvel não esta válido !"));
        else
            $values['value'] = (float)$values['value'];

        if ($values['value'] <= 0) 
            throw new DAOException(sprintf("Valor do Imóvel deve ser maior que zero !"));

        if (!isset($values['city']) || trim($values['city']) === "")
            throw new DAOException(sprintf("Deve ser informado o nome da Cidade !"));
        else
            $values['city'] = trim($values['city']);

        if (!isset($values['state']) || trim($values['state']) === "")
            throw new DAOException(sprintf("Deve ser informado o Estado !"));
        else
            $values['state'] = strtoupper(trim($values['state']));

        if (!isset($values['address']) || trim($values['address']) === "")
            throw new DAOException(sprintf("O Endereço deve ser informado !"));
        else
            $values['address'] = trim($values['address']);

        if (!isset($values['description']))
            $values['description'] = "";

        $immobile->setData($values);
     	$immobile = parent::save($immobile);
    
        return $immobile;
    }

    public function remove (Entity $immobile) {
        foreach($immobile->items as $item)
            $this->getimmobileItemDAOService()->remove($item);

        return parent::remove($immobile);
    }
}