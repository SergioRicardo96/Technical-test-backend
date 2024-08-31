<?php

namespace App\Libs;

class Validator
{
    protected $data;
    protected $rules;
    protected $fieldNames;
    protected $errors = [];

    public function __construct($data, $rules, $fieldNames = [])
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->fieldNames = $fieldNames;
    }

    public function validate()
    {
        $this->errors = []; // Reset errors

        foreach ($this->rules as $field => $rule) {
            $fieldName = $this->fieldNames[$field] ?? $field;

            // Apply validation rules here
            if (isset($this->data[$field])) {
                $value = $this->data[$field];
                $ruleList = explode('|', $rule);

                foreach ($ruleList as $ruleItem) {
                    if (strpos($ruleItem, ':') !== false) {
                        list($ruleName, $ruleValue) = explode(':', $ruleItem);
                    } else {
                        $ruleName = $ruleItem;
                        $ruleValue = null;
                    }

                    $method = 'validate' . ucfirst($ruleName);
                    if (method_exists($this, $method)) {
                        if (!$this->$method($value, $ruleValue)) {
                            $this->errors[$field] = $this->errors[$field] ?? [];
                            $error = str_replace(':fieldname', $fieldName, $this->messages()[$ruleName]);
                            $error = str_replace(':value', $ruleValue ?? '', $error);
                            $this->errors[$field][] = $error;
                            break; // Stop after the first failed rule
                        }
                    }
                }
            } elseif (strpos($rule, 'required') !== false) {
                $this->errors[$field] = $this->errors[$field] ?? [];
                $this->errors[$field][] = str_replace(':fieldname', $fieldName, $this->messages()['required']);
            }
        }

        return empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    protected function messages()
    {
        return [
            'required' => Translation::trans('validator', 'required'),
            'min' => Translation::trans('validator', 'min'),
            'max' => Translation::trans('validator', 'max'),
            'exists' => Translation::trans('validator', 'exists'),
            'numeric' => Translation::trans('validator', 'numeric'),
        ];
    }

    protected function validateRequired($value)
    {
        return !empty($value);
    }

    protected function validateMin($value, $min)
    {
        return strlen($value) >= $min;
    }

    protected function validateMax($value, $max)
    {
        return strlen($value) <= $max;
    }

    protected function validateNumeric($value)
    {
        return is_numeric($value);
    }

    protected function validateExists($value, $params)
    {
        // Asegúrate de que $params esté en el formato 'table,column'
        if (is_string($params)) {
            list($table, $column) = explode(',', $params, 2);
            $model = new Model();
            return !$model->validateExists($table, $column, $value);
        }
        return false;
    }
}
