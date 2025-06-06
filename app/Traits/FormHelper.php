<?php

namespace App\Traits;

trait FormHelper
{
    protected function convertAllTranslatables(array $data): array
    {
        /*foreach ($this->getTranslatableFields() as $field) {
            $translations = [];
            foreach ($data[$field . '_translations'] ?? [] as $item) {
                $translations[$item['locale']] = $item['value'];
            }
            $data[$field] = $translations;
            unset($data[$field . '_translations']);
        }
        return $data;*/
        foreach ($this->getTranslatableFields() as $field => $valueKeys) {
            // دعم الشكل القديم إذا ما كان فيه keys
            if (is_int($field)) {
                $field = $valueKeys;
                $valueKeys = ['value'];
            }

            $translations = [];

            foreach ($data[$field . '_translations'] ?? [] as $item) {
                $value = null;

                foreach ($valueKeys as $key) {
                    if (!empty($item[$key])) {
                        $value = $item[$key];
                        break;
                    }
                }

                if (!empty($value)) {
                    $translations[$item['locale']] = $value;
                }
            }

            $data[$field] = $translations;
            unset($data[$field . '_translations']);
        }

        return $data;
    }

    // protected function revertAllTranslatables(array $data): array
    // {
    //     foreach ($this->getTranslatableFields() as $field) {
    //         $translations = [];
    //         foreach ($data[$field] ?? [] as $locale => $value) {
    //             $translations[] = [
    //                 'locale' => $locale,
    //                 'value' => $value,
    //             ];
    //         }
    //         $data[$field . '_translations'] = $translations;
    //     }
    //     return $data;
    // }
    protected function revertAllTranslatables(array $data): array
    {
        foreach ($this->getTranslatableFields() as $field => $valueKeys) {

            // دعم الشكل القديم إذا ما كان فيه keys
            if (is_int($field)) {
                $field = $valueKeys;
                $valueKeys = ['value'];
            }

            $translations = [];

            foreach ($data[$field] ?? [] as $locale => $value) {
                if (!is_array($value)) {
                    $translation = [
                        'locale' => $locale,
                        'value' => $value,
                    ];

                    foreach ($valueKeys as $key) {
                        if ($key !== 'value') {
                            $translation[$key] = $value; // نعيد القيمة نفسها في كل key
                        }
                    }

                    $translations[] = $translation;
                    continue;
                }

                $translation = ['locale' => $locale];

                foreach ($valueKeys as $key) {
                    $translation[$key] = $value[$key] ?? null;
                }

                $translations[] = $translation;
            }

            $data[$field . '_translations'] = $translations;
        }

        return $data;
    }
}
