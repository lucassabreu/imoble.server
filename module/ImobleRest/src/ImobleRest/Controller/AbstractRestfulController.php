<?php

namespace ImobleRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as ParentController;
use Core\Model\Entity\Entity;

/**
 * Description of AbstractRestfulController
 *
 * @author lucas.s.abreu@gmail.com
 */
abstract class AbstractRestfulController extends ParentController {
	 
    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        $return = parent::onDispatch($e);
        
        $this->getResponse()->getHeaders()->addHeaders(array(
            'Access-Control-Allow-Origin' => "http://localhost",
            "Access-Control-Allow-Headers" => "Content-Type"
        ));
        
        if (is_array($return) || ($return instanceof Entity)) {
            if($return instanceof Entity)
                $return = $return->toArray();

            $return = new \Zend\View\Model\JsonModel ($return);
            $e->setResult($return);
            return $return;
        } 
        
        return $return;
    }

    public function returnNotFound() {
        $response = $this->getResponse();
        $response->setStatusCode(404); // not found
        $response->setContent("");
        return [];
    }

    public function returnError ($error = array()) {
        $response = $this->getResponse();
        $response->setStatusCode(403); // forbidden
        
        return array(
            "error" => $error
        );
    }

    /**
     * DAO instance for controller
     * 
     * @var DAOInterface
     */
    private $dao = null;

    /**
     * Class name of default DAO
     * @var string
     */
    protected $daoName = null;

    /**
     * Retrieves the of requested service by name
     * @param string $name
     * @return mixed|ServiceManagerAwareInterface|ServiceLocatorAwareInterface
     */
    public function getService($name) {
        return $this->getServiceLocator()->get($name);
    }

    /**
     * Render a page based at param <code>$model</code>
     * @param ViewModel|array|mixed $model
     * @param string $layout Layout to be used
     */
    public function renderModel($model) {

        if (is_array($model)) {
            $model = new ViewModel($model);
        }

        $viewManager = $this->getService('ViewManager');
        /* @var $viewManager ViewManager */

        $renderer = new PhpRenderer();
        $renderer->setResolver($viewManager->getResolver());
        $renderer->setHelperPluginManager($viewManager->getHelperManager());

        return $renderer->render($model);
    }

    /**
     * Retrieves a DAO instance
     * @return DAOInterface
     */
    public function dao($name = null) {

        if ($name === null) {
            if ($this->dao === null)
                $this->dao = $this->getService($this->daoName);

            return $this->dao;
        }
        else
            return $this->getService($name);
    }
}
