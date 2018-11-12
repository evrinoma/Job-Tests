<?php
if (!defined('SITE_IS_AUTH')) {
    die('No direct script access allowed');
}

class EntityDto
{
//region SECTION: Fields
    /**
     * @var null
     */
    private $child = null;
    /**
     * @var null
     */
    private $parent = null;
    /**
     * @var null
     */
    private $key = null;
    /**
     * @var null
     */
    private $value = null;

    /**
     * @var array
     */
    private $printR = null;
//endregion Fields

//region SECTION: Constructor
    /**
     * EntityDto constructor.
     *
     * @param      $key
     * @param null $value
     */
    public function __construct($key, $value = null)
    {
        $this->key    = $key;
        $this->value  = $value;
        $this->printR = $this->toAssociativeArray();
    }
//endregion Constructor

//region SECTION: Protected
    /**
     * @return EntityDto
     */
    protected function getParent()
    {
        return $this->parent;
    }

    /**
     * @param null $printR
     */
    protected function setPrintR($printR)
    {
        $this->printR = $printR;
    }

    /**
     * @param null $printR
     */
    protected function setPrintRByKey($key, $printR)
    {
        $this->printR[$key] = $printR;
    }

    /**
     * @param null $child
     */
    protected function setChild($child)
    {
        $this->child = $child;
    }

    /**
     * @param null $parent
     */
    protected function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param array $value
     */
    protected function setValue($value = array())
    {
        $this->value = $value;
    }

    /**
     * @return null
     */
    protected function getKey()
    {
        return $this->key;
    }
//endregion Protected

//region SECTION: Public
    /**
     * @param EntityDto $parent
     *
     * @return $this
     */
    public function addParent(EntityDto $parent)
    {
        $parent->setValue();
        $parent->setChild($this);
        $parent->printR[$parent->getKey()] = $this->getPrintR();
        $this->parent                      = $parent;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRootNode()
    {
        return is_null($this->parent);
    }
//endregion Public

//region SECTION: Private
    private function toAssociativeArray()
    {
        return array($this->getKey() => $this->getValue());
    }

    /**
     * @return EntityDto
     */
    private function getChild()
    {
        return $this->child;
    }

    /**
     * @return null
     */
    private function getValue()
    {
        return $this->value;
    }

    /**
     * @param null $key
     */
    private function setKey($key)
    {
        $this->key = $key;
    }
//endregion Private

//region SECTION: Getters/Setters
    /**
     * @return null
     */
    public function getPrintR()
    {
        return $this->printR;
    }
//endregion Getters/Setters
}

class Test1
{
//region SECTION: Fields
    /**
     * @var EntityDto | null
     */
    private $elements = null;

    private $data = null;
//endregion Fields

//region SECTION: Constructor
    public function __construct(array $x)
    {
        foreach ($x as $value) {
            $parent = new EntityDto($value);
            if ($this->elements) {
                $this->elements->addParent($parent);
            }
            $this->elements = $parent;
        }
    }

//endregion Constructor

//region SECTION: Public
    public function print_r()
    {
        $this->printR();
    }

//endregion Public

//region SECTION: Private
    private function printR()
    {
        print_r($this->elements->getPrintR());
    }

//endregion Private
}

class EntityTest2Dto extends EntityDto
{
//region SECTION: Protected
    protected function pushPrintR($value)
    {
        //$this->printR[$this->key] = $value;
        $this->setPrintRByKey($this->getKey(), $value);
        if ($this->getParent()) {
            $this->getParent()->pushPrintR($this->getPrintR());
        }
    }
//endregion Protected

//region SECTION: Public
    /**
     * @param EntityTest2Dto $child
     *
     * @return $this
     */
    public function addChild(EntityTest2Dto $child)
    {
        $this->setChild($child);
        $child->setParent($this);
        $this->setValue();
        $child->setPrintR($this->pushPrintR($child->getPrintR()));

        return $this;
    }
//endregion Public
//region SECTION: Private
//endregion Getters/Setters
}

class EntityTest2Parser
{
//region SECTION: Fields
    const PREFIX_DELIMITER = '.';

    private $source  = null;
    private $element = null;
//endregion Fields
//region SECTION: Constructor

    /**
     * EntityTest2Dto constructor.
     *
     * @param $key
     */
    public function __construct($key, $value)
    {
        $this->source = array($key => $value);
        $stream       = explode(self::PREFIX_DELIMITER, $key);
        /** @var EntityTest2Dto $element */
        $element = null;
        /** @var EntityTest2Dto $root */
        $root = null;
        foreach ($stream as $item) {
            $element = new EntityTest2Dto($item, $value);
            if ($root) {
                $root->addChild($element);
            } else {
                $this->element = $element;
            }
            $root = $element;
        }
    }
//endregion Constructor

//region SECTION: Public
    public function toArray()
    {
        return $this->element->getPrintR();
    }

    public function toSource()
    {
        return $this->source;
    }
//endregion Public

}

class Test2
{
//region SECTION: Fields
    private $data = array();
//endregion Fields

//region SECTION: Constructor
    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            $convert    = new EntityTest2Parser($key, $value);
            $this->data = array_merge_recursive($this->data, $convert->toArray());
        }
    }
//endregion Constructor

//region SECTION: Public
    public function printR()
    {
        print_r($this->data);
    }
//endregion Public

//region SECTION: Private
    private function reverse($data, $converted = array(), $keyString = "")
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $converted = array_merge($this->reverse($item, $converted, $keyString.$key."."), $converted);
            } else {
                $converted[$keyString.$key] = $item;
            }
        }

        return $converted;
    }
//endregion Private

//region SECTION: Getters/Setters
    public function getReverse()
    {
        $this->data = $this->reverse($this->data);

        return $this;
    }
//endregion Getters/Setters
}