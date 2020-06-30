<?php

namespace Core\Form;

abstract class AbstractForm
{
    private $name;
    private $method;
    private $target;
    private $formElements;
    private $fieldsets = [];
    private $groups = [];

    private $class;

    public function __construct()
    {
        $this->init();
        return $this;
    }

    public function init()
    {
    }

    /**
     * @param string $type
     * @param string $name
     * @param array $classes
     * @param array $params
     * @param bool $required
     * @param string $value
     * @return AbstractForm
     */
    public function addElement(
        string $type,
        string $name,
        array $params,
        $value = null
    )
    {
        $element = new Element($type, $name, $params, $value);
        $this->setElement($element);
    }

    /**
     * @param array $values
     * @return bool
     */
    public function validate(array $values) : bool
    {
        $isValid = true;
        $required = $this->getRequiredElements();

        foreach ($required as $name => $Element) {
            /** @var Element $Element */
            if (!array_key_exists($name, $values)) {
                $isValid = false;
                break;
            }
            if (!$Element->validate($values[$name])) {
                $isValid = false;
                break;
            }
        }

        /** If the form is not valid - it should retain values and display errors */
        if (!$isValid) {
            $this->populate($values);
        }

        return $isValid;
    }

    public function filterData(array $values)
    {
        $newValues = [];
        foreach($values as $name => $value) {
            if (array_key_exists($name, $this->formElements)) {
                /** @var Element $Element */
                $Element = $this->formElements[$name];
                $newValues[$name] = $Element->filterValue($value);
            } else {
                return false;
            }
        }

        return $newValues;
    }

    public function populate(array $values)
    {
        /** @var  Element $Element */
        foreach($this->formElements as $element) {
            $Element = $element['element'];
            $name = $Element->getName();
            if (array_key_exists($name, $values)) {
                $Element->setValue($values[$name]);
            }
        }
        return $this;
    }

    public function removeElements(array $elements)
    {
        foreach($elements as $element) {
            $this->removeElement($element);
        }
    }

    public function disableElements(array $elements)
    {
        foreach($elements as $element) {
            $this->disableElement($element);
        }
    }

    public function removeElement(string $element)
    {
        if (array_key_exists($element, $this->formElements)) {
            unset($this->formElements[$element]);
        }
    }

    public function disableElement(string $element)
    {
        if (array_key_exists($element, $this->formElements)) {
            $this->formElements[$element]['element']->setDisabled(true);
        }
    }


    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target)
    {
        $this->target = $target;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $index => $option) {
            $method = 'set' . ucfirst($index);
            if (method_exists($this, $method)) {
                $this->$method($option);
            }
        }
    }

    /**
     * @param Element $element
     */
    public function setElement(Element $element)
    {
        $this->formElements[$element->getName()] = [
            'element' => $element,
            'group' => '',
            'fieldset' => ''
        ];
    }

    public function setClasses($classes)
    {
        if (is_array($classes)) {
            $this->class = implode(' ', $classes);
        } else {
            $this->class = $classes;
        }
    }

    public function addClass(string $class)
    {
        if (empty($this->class)) {
            $this->class = $class;
        } else {
            $this->class .= ' ' . $class;
        }
    }

    /**
     * @param string $fieldsetName
     * @param array $elements
     * @param null $label
     */
    public function createFieldset(string $fieldsetName, array $elements, $label = null)
    {
        $matches = false;
        foreach ($elements as $elementName) {
            if (array_key_exists($elementName, $this->formElements)) {
                $this->setFieldset($elementName, $fieldsetName);
                $matches = true;
            }
        }
        if ($matches) {
            $this->fieldsets[] = $fieldsetName;
        }
    }

    /**
     * @param $elementName
     * @param $fieldsetName
     */
    public function setFieldset($elementName, $fieldsetName)
    {
        $this->formElements[$elementName]['fieldset'] = $fieldsetName;
    }

    /**
     * @param string $groupName
     * @param array $elements
     * @param null $label
     */
    public function createGroup(string $groupName, array $elements, $label = null)
    {
        $matches = false;
        foreach ($elements as $elementName) {
            if (array_key_exists($elementName, $this->formElements)) {
                $this->setGroup($elementName, $groupName);
                $matches = true;
            }
        }
        if ($matches) {
            $this->groups[] = $groupName;
        }
    }

    /**
     * @param $elementName
     * @param $groupName
     */
    public function setGroup($elementName, $groupName)
    {
        $this->formElements[$elementName]['group'] = $groupName;
    }

    /**
     * @return array
     */
    public function getElements() : array
    {
        return $this->formElements;
    }

    /**
     * @return array|null
     */
    public function getAllFieldsetElements()
    {
        if (empty($this->fieldsets)) {
            return null;
        }
        $result = [];

        foreach ($this->fieldsets as $fieldsetName) {
            $result[$fieldsetName] = $this->getFieldsetElements($fieldsetName);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getNonFieldsetElements()
    {
        if (empty($this->fieldsets)) {
            return $this->formElements;
        }
        $result = [];

        foreach ($this->formElements as $name => $elementData) {
            if (empty($elementData['fieldset'])) {
                $result[$name] = $elementData;
            }
        }

        return $result;
    }

    /**
     * @param $fieldsetName
     * @return array|null
     */
    public function getFieldsetElements($fieldsetName)
    {
        if (!in_array($fieldsetName, $this->fieldsets)) {
            return null;
        }
        $result = [];
        foreach ($this->formElements as $elementName => $formData) {
            if ($this->_isInFieldset($elementName, $fieldsetName)) {
                $result[$elementName] = $formData;
            }
        }

        return $result;
    }

    ####################################################
    /**
     * @return array|null
     */
    public function getAllGroupElements()
    {
        if (empty($this->groups)) {
            return null;
        }
        $result = [];

        foreach ($this->groups as $groupName) {
            $result[$groupName] = $this->getGroupElements($groupName);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getNonGroupElements()
    {
        if (empty($this->groups)) {
            return $this->formElements;
        }
        $result = [];

        foreach ($this->formElements as $name => $elementData) {
            if (empty($elementData['group'])) {
                $result[$name] = $elementData;
            }
        }

        return $result;
    }

    /**
     * @param $groupName
     * @return array|null
     */
    public function getGroupElements($groupName)
    {
        if (!in_array($groupName, $this->groups)) {
            return null;
        }
        $result = [];
        foreach ($this->formElements as $elementName => $formData) {
            if ($this->_isGroupped($elementName, $groupName)) {
                $result[$elementName] = $formData;
            }
        }

        return $result;
    }
    ####################################################

    /**
     * @param string $name
     * @return Element|null
     */
    public function getElementByName(string $name)
    {
        if (array_key_exists($name, $this->formElements)) {
            return $this->formElements[$name]['element'];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getRequiredElements() : array
    {
        $result = [];
        /** @var Element $Element */
        foreach ($this->getElements() as $Element) {
            if ($Element->getRequired()) {
                $result[$Element->getName()]['element'] = $Element;
            }
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getValues()
    {
        $values = [];
        /** @var Element  $element */
        foreach ($this->formElements as $name => $elementData) {
            $element = $elementData['element'];
            $values[$name] = $element->getValue();
        }

        return $values;
    }

    /**
     * @param string $elementName
     * @param null $groupName
     * @return bool
     */
    public function _isGroupped(string $elementName, $groupName = null) : bool
    {
        if (!array_key_exists($elementName, $this->formElements)) {
            return false;
        }

        if (empty($this->formElements[$elementName]['group'])) {
            return false;
        }

        if (null !== $groupName && $this->formElements[$elementName]['group'] !== $groupName) {
            return false;
        }

        return true;
    }

    /**
     * @param string $elementName
     * @param null $fieldsetName
     * @return bool
     */
    public function _isInFieldset(string $elementName, $fieldsetName = null) : bool
    {
        if (!array_key_exists($elementName, $this->formElements)) {
            return false;
        }

        if (empty($this->formElements[$elementName]['fieldset'])) {
            return false;
        }

        if (null !== $fieldsetName && $this->formElements[$elementName]['fieldset'] !== $fieldsetName) {
            return false;
        }

        return true;
    }
}

