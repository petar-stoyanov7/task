<?php

namespace Core\Form;

use Core\Config;

class Element
{
    private $type;
    private $name;
    private $options;
    private $value;

    private $class;
    private $placeholder;
    private $buttonType;
    private $onClick;
    private $label;
    private $required;
    private $disabled;
    private $filters;
    private $validators;
    private $errors;

    /** validators */
    const VALIDATOR_CHARS_ONLY = 'charsOnly';
    const VALIDATOR_LATIN_CHARS_ONLY = 'latinCharsOnly';
    const VALIDATOR_CHARS_AND_NUM = 'charsAndNum';
    const VALIDATOR_LATIN_CHARS_AND_NUM = 'latinCharsAndNum';
    const VALIDATOR_IS_TRUE = 'isTrue';

    /** validator errors */
    private $errorMessages = [];

    /** filters */
    const FILTER_STRING_TRIM = 'stringTrim';
    const FILTER_LOWERCASE = 'lowercase';
    const FILTER_UPPERCASE = 'uppercase';
    const FILTER_UCFIRST = 'ucfirst';
    const FILTER_NULLIFY = 'nullify';

    private $validFilters = [
        self::FILTER_STRING_TRIM,
        self::FILTER_LOWERCASE,
        self::FILTER_UPPERCASE,
        self::FILTER_UCFIRST,
        self::FILTER_NULLIFY,
    ];

    private $validValidators = [
        self::VALIDATOR_CHARS_ONLY,
        self::VALIDATOR_LATIN_CHARS_ONLY,
        self::VALIDATOR_CHARS_AND_NUM,
        self::VALIDATOR_LATIN_CHARS_AND_NUM,
        self::VALIDATOR_IS_TRUE,
    ];

    public function __construct($type = null, $name = null, $params = null, $value = null)
    {
        if (
            null !== $type &&
            null !== $name &&
            null !== $params
        ) {
            $this->createElement($type, $name, $params, $value);
        }
        $Config = new Config();
        $errorsMessages = $Config->getConfigDetail('form-error-messages');
        if (!empty($errorsMessages)) {
            $this->errorMessages = $errorsMessages;
        }
        $this->errorMessages['default'] = 'Invalid values';
    }


    public function createElement(
        string $type,
        string $name,
        array $params,
        $value = null
    )
    {
        $this->setType($type);
        $this->setName($name);
        $this->setParams($params);
        $this->setValue($value);
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setParams(array $params)
    {
        foreach ($params as $key => $param) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method) && !empty($param)) {
                $this->$method($param);
            }
        }
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function setFilters(array $filters)
    {
        foreach ($filters as $index => $filter) {
            if (!in_array($filter, $this->validFilters)) {
                unset($filters[$index]);
            }
        }
        $this->filters = $filters;
    }

    public function setValidators(array $validators)
    {
        foreach ($validators as $index => $validator) {
            if (!in_array($validator, $this->validValidators)) {
                unset($validators[$index]);
            }
        }
        $this->validators = $validators;
    }

    public function setClasses($classes)
    {
        if (is_array($classes)) {
            $this->class = implode(' ', $classes);
        } else {
            $this->class = $classes;
        }
    }

    public function setDisabled($disabled = true)
    {
        $this->disabled = $disabled ? 'disabled' : null;
    }

    public function setPlaceholder($text)
    {
        $this->placeholder = $text;
    }

    public function setButtonType(string $type)
    {
        $this->buttonType = $type;
    }

    public function setOnClick(string $onClick)
    {
        $this->onClick = $onClick;
    }

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function setRequired(bool $required)
    {
        $this->required = $required;
    }

    public function setErrors($error)
    {
        if (is_array($error)) {
            $this->errors = $error;
        } else {
            $this->errors = [$error];
        }
    }

    public function addErrors($error)
    {
        if (is_array($error)) {
            $this->errors = array_merge($this->errors, $error);
        } else {
            $this->errors[] = $error;
        }
    }

    public function addClass($class)
    {
        if (empty($this->class)) {
            $this->class = $class;
        } else {
            $this->class .= " {$class}";
        }
    }

    public function validate(string $value) : bool
    {
        $this->checkValidators($value);
        $this->value = $this->filterValue($value);

        if (!(bool)$this->required) {
            return true;
        }

        if (null === $value || '' === $value) {
            return false;
        }

        if ($this->isSelect()) {
            if (!array_key_exists($value, $this->options)) {
                return false;
            }
        }

        if (!empty($this->errors)) {
            return false;
        }

        return true;
    }

    public function checkValidators($value)
    {
        if (empty($this->validators)) {
            return true;
        }

        foreach ($this->validators as $validator) {
            switch($validator) {
                case self::VALIDATOR_CHARS_ONLY:
                    if (preg_match('/[^a-zA-Zа-яА-Я]/', $value)) {
                        $this->_generateErrorMessage(self::VALIDATOR_CHARS_ONLY);
                    }
                    break;
                case self::VALIDATOR_LATIN_CHARS_ONLY:
                    if (preg_match('/[^a-zA-Z]/', $value)) {
                        $this->_generateErrorMessage(self::VALIDATOR_LATIN_CHARS_ONLY);
                    }
                    break;
                case self::VALIDATOR_CHARS_AND_NUM:
                    if (preg_match('/[^a-zA-Zа-яА-Я0-9]/', $value)) {
                        $this->_generateErrorMessage(self::VALIDATOR_CHARS_AND_NUM);
                    }
                    break;
                case self::VALIDATOR_LATIN_CHARS_AND_NUM:
                    if (preg_match('/[^a-zA-Z0-9]/', $value)) {
                        $this->_generateErrorMessage(self::VALIDATOR_LATIN_CHARS_AND_NUM);
                    }
                    break;
                case self::VALIDATOR_IS_TRUE:
                    if (!(bool)$value) {
                        $this->_generateErrorMessage(self::VALIDATOR_IS_TRUE);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    public function filterValue($value)
    {
        if (empty($this->filters)) {
            return $value;
        }

        $newValue = $value;

        foreach ($this->filters as $filter) {
            switch($filter) {
                case self::FILTER_STRING_TRIM:
                    $newValue = htmlspecialchars($newValue);
                    break;
                case self::FILTER_LOWERCASE:
                    $newValue = strtolower($newValue);
                    break;
                case self::FILTER_UPPERCASE:
                    $newValue = strtoupper($newValue);
                    break;
                case self::FILTER_UCFIRST:
                    $newValue = ucfirst(strtolower($newValue));
                    break;
                case self::FILTER_NULLIFY:
                    $newValue = ('' === $newValue) ? null : $newValue;
                    break;
                default:
                    break;
            }
        }

        return $newValue;
    }

    public function isSelect() : bool
    {
        if ($this->type === 'select' || $this->type === 'multiselect') {
            return true;
        }
        return false;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getButtonType()
    {
        return $this->buttonType;
    }

    public function getOnClick()
    {
        return $this->onClick;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getRequired() : bool
    {
        return (bool)$this->required;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getValidators()
    {
        return $this->validators;
    }

    private function _generateErrorMessage($validator)
    {
        if (array_key_exists($validator, $this->errorMessages)) {
            $this->addErrors($this->errorMessages[$validator]);
        } else {
            $this->addErrors($this->errorMessages['default']);
        }
    }

}