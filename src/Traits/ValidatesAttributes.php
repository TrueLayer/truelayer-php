<?php

declare(strict_types=1);

namespace TrueLayer\Traits;

use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Contracts\Validation\Validator;

trait ValidatesAttributes
{
    /**
     * @var ValidatorFactory
     */
    protected ValidatorFactory $validatorFactory;

    /**
     * @var mixed[]
     */
    protected array $rules = [];

    /**
     * @return mixed[]
     */
    protected function rules(): array
    {
        return $this->rules;
    }

    /**
     * @throws \TrueLayer\Exceptions\ValidationException
     *
     * @return self
     */
    public function validate(): self
    {
        $this->validateData();

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return !$this->validator()->fails();
    }

    /**
     * @return mixed[]
     */
    public function errors(): array
    {
        return $this->validator()->errors()->toArray();
    }

    /**
     * @param mixed[]|null $data
     *
     * @throws \TrueLayer\Exceptions\ValidationException
     *
     * @return $this
     */
    protected function validateData(array $data = null): self
    {
        $validator = $this->validator($data);

        try {
            $validator->validate();

            return $this;
        } catch (\Exception $e) {
            throw new \TrueLayer\Exceptions\ValidationException($validator);
        }
    }

    /**
     * @param mixed[]|null $data
     *
     * @return Validator
     */
    protected function validator(array $data = null): Validator
    {
        return $this->validatorFactory->make(
            $data ?: $this->all(),
            $this->rules()
        );
    }
}
