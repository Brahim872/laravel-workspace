<?php

namespace App\Traits;

use App\Helpers\Tools;

trait Validatorable
{

    /**
     * Extra fields not in fillable to be added to nice names (translation).
     *
     * @var array
     */
    protected $addToNiceNames = [];

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $this->fill($data);
        $validator = \Validator::make($data, $this->rules());
        $validator->setAttributeNames($this->niceNames());

        return $validator;
    }

    /**
     * Translate attributes names.
     *
     * @return array
     */
    protected function niceNames()
    {
        $niceNames = [];
        if (isset($this->fillable)) {
            foreach ($this->fillable as $attribute) {
                $niceNames[$attribute] = $this->niceName($attribute);
            }
        }
        if (isset($this->addToNiceNames)) {
            foreach ($this->addToNiceNames as $attribute) {
                $niceNames[$attribute] = $this->niceName($attribute);
            }
        }
        return $niceNames;
    }

    /**
     *
     * @param string $attribute
     * @return string the name translated using the associated translation file
     */
    protected function niceName($attribute)
    {
        $translationFile = $this->getName();

        return \Lang::get($translationFile . '.' . $attribute);
    }

    /**
     * Get model name to be used in files (translation ...).
     *
     * @return string
     */
    public function getName()
    {
        $classArr = explode('\\', get_class($this));

        return (new \App\Helpers\Tools)->camelCaseToUnderscoredCase(end($classArr));
    }

}
