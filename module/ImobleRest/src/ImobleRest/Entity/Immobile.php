<?php

namespace ImobleRest\Entity;

use Doctrine\ORM\Mapping as ORM;
use Core\Model\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table (name="immobile")
 *
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string $state
 * @property string $address
 * @property string $description
 * @property string $value
 * @property ImmobileItem[] $items
 */
class Immobile extends Entity {
    
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 */
	private $name;

	/**
	 * @ORM\Column(type="string")
	 */
	private $city;
	/**
	 * @ORM\Column(type="string")
	 */
	private $state;
	/**
	 * @ORM\Column(type="string")
	 */
	private $address;

	/**
	 * @ORM\Column(type="string")
	 */
	private $description;

	/**
	 * @ORM\Column(type="string")
	 */
	private $type;

	/**
	 * @ORM\Column(type="string")
	 */
	private $status;

	/**
	 * @ORM\Column(type="decimal")
	 */
	private $value;

	/**
     * @ORM\OneToMany(targetEntity="ImmobileItem", mappedBy="immobile")
     */
    private $items;

    public function __construct () {
        $this->items = new ArrayCollection();
    }

    /**
     * Return all entity data in array format
     *
     * @return array
     */
    public function getData() {
        $data = get_object_vars($this);
        unset($data['inputFilter']);
        return $data;
    }

    /**
     * Set all entity data based in an array with data
     *
     * @param array $data
     * @return void
     */
    public function setData($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }
    
    /**
     * Set and validate field values
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function __set($key, $value) {
        $this->$key = $this->valid($key, $value);
    }

    /**
     * @param string $key
     * @return mixed 
     */
    public function __get($key) {
        return $this->$key;
    }
}