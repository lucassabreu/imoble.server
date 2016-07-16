<?php

namespace ImobleRest\Controller;

use ImobleRest\Entity\ImmobileItem;
use Exception;

class ImmobileItemRestController extends AbstractRestfulController
{
    public function formatImmobileItem ($ii) {
        if ($ii instanceof ImmobileItem) {
            $return = $ii->getData();
            $return['immobile'] = $ii->immobile->id;
            return $return;
        } else {
            $iis = [];
            foreach($ii as $item) {
                $iis[] = $this->formatImmobileItem($item);
            }
            return $iis;
        }
    }

    public function __construct() {
        $this->daoName = "ImobleRest\Service\ImmobileItemService";
    }

    public function options() {
        return [];
    }

    public function update($id, $data) {
    	$ImmobileItem = $this->dao()->findById($id);

        try {
            $ImmobileItem = $this->dao()->save($ImmobileItem,$data);
        } catch (Exception $e) {
            return $this->returnError(array (
                'msg' => $e->getMessage(),
            ));
        }

        return $this->formatImmobileItem($ImmobileItem);
    }

    public function create ($data) {
    	if (isset($data['id'])) {
    		return $this->update($data['id'], $data);
    	}

        try {
            $ImmobileItem = $this->dao()->save(null,$data);
        } catch (Exception $e) {
            return $this->returnError(array (
                'msg' => $e->getMessage(),
            ));
        }

        return $this->formatImmobileItem($ImmobileItem);
    }

    public function delete ($id) {
        $ImmobileItem = $this->dao()->findById($id);

        if ($ImmobileItem === null) {
            return $this->returnNotFound();
        }

        try {
            $this->dao()->remove($ImmobileItem);
        } catch (Exception $e) {
            return $this->returnError(array (
                'msg' => $e->getMessage(),
            ));
        }

        return $this->formatImmobileItem($ImmobileItem);
    }

    public function getList() {
        $immobileId = $this->params("immobile");

        if ($immobileId === null || trim($immobileId) === "")
            return $this->returnNotFound();

        $immobile = $this->dao()->getImmobile ($immobileId);

        $ImmobileItems = $this->dao()->getAllItemOfImmobile($immobile);
        return $this->formatImmobileItem($ImmobileItems);
    }
    
    public function get($id) {
        $immobileItem = $this->dao()->findById($id);

        if ($immobileItem === null) {
            return $this->returnNotFound();
        }

        return $this->formatImmobileItem($immobileItem);
    }

}

