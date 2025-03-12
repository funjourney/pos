<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use ReflectionClass;
use Exception;

class DataProcessing
{
   public static function ConvertStructToMap($data, $removeKeys = []) {
        // Jika data adalah Collection, ubah ke array
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        // Jika data berupa array, proses setiap elemen secara rekursif
        if (is_array($data)) {
            return array_map(function ($item) use ($removeKeys) {
                return DataProcessing::ConvertStructToMap($item, $removeKeys);
            }, DataProcessing::processMap($data, $removeKeys));
        }

        // Jika data berupa object, konversi ke array lalu proses
        if (is_object($data)) {
            return DataProcessing::ConvertStructToMap(DataProcessing::convertSingleStructToMap($data), $removeKeys);
        }

        return $data;
    }

   public static function processMap($data, $removeKeys = []) {
        // Hapus key yang tidak diinginkan
        foreach ($removeKeys as $key) {
            unset($data[$key]);
        }
        return $data;
    }

   public static function convertSingleStructToMap($object) {
        $result = [];

        try {
            $reflection = new ReflectionClass($object);
            $properties = $reflection->getProperties();

            foreach ($properties as $property) {
                $property->setAccessible(true);
                $key = $property->getName();
                $value = $property->getValue($object);

                // Periksa apakah ada anotasi @json untuk custom key name
                $docComment = $property->getDocComment();
                if ($docComment && preg_match('/@json\s+(\w+)/', $docComment, $matches)) {
                    $key = $matches[1];
                }

                $result[$key] = $value;
            }

            return $result;
        } catch (Exception $e) {
            Log::error('Error in convertSingleStructToMap: ' . $e->getMessage());
            return [];
        }
    }
}