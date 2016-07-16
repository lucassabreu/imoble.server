<?php

namespace ImobleRest\Controller;

use ImobleRest\Entity\Immobile;
use ImobleRest\Entity\ImmobileItem;
use Exception;

class ImmobileRestController extends AbstractRestfulController
{
    public function formatImmobile ($immobile) {
        if ($immobile instanceof Immobile) {
            $return = $immobile->getData();
            $return['items'] = $this->formatImmobileItem($return['items']);
            return $return;
        } else {
            $immobiles = [];
            foreach ($immobile as $i) {
                $immobiles[] = $this->formatImmobile($i);
            }
            return $immobiles;
        }
    }

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
        $this->daoName = "ImobleRest\Service\ImmobileService";
    }

    public function options() {
        return [];
    }

    public function update($id, $data) {
    	$immobile = $this->dao()->findById($id);

        try {
            $immobile = $this->dao()->save($immobile,$data);
        } catch (Exception $e) {
            return $this->returnError(array (
                'msg' => $e->getMessage(),
            ));
        }

        return $this->formatImmobile($immobile);
    }

    public function create ($data) {
    	if (isset($data['id'])) {
    		return $this->update($data['id'], $data);
    	}

        try {
            $immobile = $this->dao()->save(null,$data);
        } catch (Exception $e) {
            return $this->returnError(array (
                'msg' => $e->getMessage(),
            ));
        }

        return $this->formatImmobile($immobile);
    }

    public function delete ($id) {
        $immobile = $this->dao()->findById($id);

        if ($immobile === null) {
            return $this->returnNotFound();
        }

        try {
            $this->dao()->remove($immobile);
        } catch (Exception $e) {
            return $this->returnError(array (
                'msg' => $e->getMessage(),
            ));
        }

        return $this->formatImmobile($immobile);
    }

    public function getList() {
        $immobiles = $this->dao()->fetchAll();
        return $this->formatImmobile($immobiles);
    }
    
    public function get($id) {
        $immobile = $this->dao()->findById($id);

        if ($immobile === null)
            return $this->returnNotFound();

        return $this->formatImmobile($immobile);
    }

}

