<?php

namespace App\DTOs\Mra;

class InitialInventoryResponseAPIResponse
{
    public ?int $statusCode = null;
    public ?string $remark = null;
    public ?InitialInventoryResponse $data = null;
    public ?array $errors = null;

    public function toArray(): array
    {
        return [
            'statusCode' => $this->statusCode instanceof self ? $this->statusCode->toArray() : $this->statusCode,
            'remark' => $this->remark instanceof self ? $this->remark->toArray() : $this->remark,
            'data' => $this->data instanceof self ? $this->data->toArray() : $this->data,
            'errors' => $this->errors instanceof self ? $this->errors->toArray() : $this->errors,
        ];
    }

    public static function fromArray(array $data): self
    {
        $instance = new self();
        if (array_key_exists('statusCode', $data)) {
            $instance->statusCode = $data['statusCode'];
        }
        if (array_key_exists('remark', $data)) {
            $instance->remark = $data['remark'];
        }
        if (array_key_exists('data', $data)) {
            $instance->data = $data['data'];
        }
        if (array_key_exists('errors', $data)) {
            $instance->errors = $data['errors'];
        }
        return $instance;
    }
}
