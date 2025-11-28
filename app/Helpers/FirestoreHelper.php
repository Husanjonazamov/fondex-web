<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FirestoreHelper
{
    protected static function baseUrl()
    {
        $projectId = env('FIREBASE_PROJECT_ID');
        return "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents";
    }

    /** Convert Firestore REST fields → clean PHP array */
    protected static function decodeFields($fields)
    {
        $result = [];
        foreach ($fields as $key => $value) {
            
            $type = array_key_first($value);
            $val = $value[$type];

            switch ($type) {
                case 'mapValue':
                    $result[$key] = self::decodeFields($val['fields'] ?? []);
                    break;

                case 'arrayValue':
                    $arr = [];
                    foreach ($val['values'] ?? [] as $v) {
                        $arr[] = self::decodeFields(['x' => $v])['x'];
                    }
                    $result[$key] = $arr;
                    break;

                case 'integerValue':
                    $result[$key] = (int) $val;
                    break;

                case 'doubleValue':
                    $result[$key] = (float) $val;
                    break;

                case 'booleanValue':
                    $result[$key] = (bool) $val;
                    break;

                default:
                    $result[$key] = $val;
            }
        }

        return $result;
    }

    /** Convert PHP array → Firestore REST fields */
    protected static function encodeFields(array $fields)
    {
        $formatted = [];

        foreach ($fields as $key => $value) {
            if (is_string($value)) {
                $formatted[$key] = ['stringValue' => $value];
            } elseif (is_int($value)) {
                $formatted[$key] = ['integerValue' => (string) $value];
            } elseif (is_float($value)) {
                $formatted[$key] = ['doubleValue' => $value];
            } elseif (is_bool($value)) {
                $formatted[$key] = ['booleanValue' => $value];
            } elseif (is_array($value)) {
                // associative → mapValue, numeric → arrayValue
                $isAssoc = array_keys($value) !== range(0, count($value) - 1);
                if ($isAssoc) {
                    $formatted[$key] = ['mapValue' => ['fields' => self::encodeFields($value)]];
                } else {
                    $formatted[$key] = [
                        'arrayValue' => ['values' => array_map(fn($v) => ['stringValue' => $v], $value)]
                    ];
                }
            } else {
                $formatted[$key] = ['stringValue' => (string) $value];
            }
        }

        return $formatted;
    }

    /** Get vakue of field based on data type */
    private static function getFirestoreValue($value)
    {
        if (is_int($value)) {
            return ['integerValue' => $value];
        } elseif (is_float($value)) {
            return ['doubleValue' => $value];
        } elseif (is_bool($value)) {
            return ['booleanValue' => $value];
        } elseif ($value instanceof \DateTime) {
            return ['timestampValue' => $value->format(\DateTime::ATOM)];
        } elseif (is_array($value)) {
            return ['arrayValue' => ['values' => array_map(fn($v) => self::getFirestoreValue($v), $value)]];
        } else {
            // Default: string
            return ['stringValue' => (string)$value];
        }
    }

    /** Get document as clean array */
    public static function getDocument($path)
    {
        $url = self::baseUrl() . "/{$path}";
        $response = Http::get($url);

        if (!$response->successful()) return null;

        $data = $response->json();

        return isset($data['fields']) ? self::decodeFields($data['fields']) : null;
    }

    /** Get all documents using collection clean array */
    public static function getCollection($collection)
    {
        $url = self::baseUrl() . "/{$collection}";
        $response = Http::get($url);

        if (!$response->successful()) return [];

        $data = $response->json();

        $documents = [];
        foreach ($data['documents'] ?? [] as $document) {
            $fields = $document['fields'] ?? [];
            $documents[] = self::decodeFields($fields);
        }

        return $documents;
    }

    /** Get document using query clean array */
    public static function queryCollection($collection, $field, $op, $value)
    {
        // Detect Firestore-compatible value type
        $firestoreValue = self::getFirestoreValue($value);
        $projectId = env('FIREBASE_PROJECT_ID');

        // Firestore operator mapping
        $mappedOp = match ($op) {
            '==' => 'EQUAL',
            '>'  => 'GREATER_THAN',
            '>=' => 'GREATER_THAN_OR_EQUAL',
            '<'  => 'LESS_THAN',
            '<=' => 'LESS_THAN_OR_EQUAL',
            '!=' => 'NOT_EQUAL',
            'array-contains' => 'ARRAY_CONTAINS',
            default => strtoupper($op),
        };
        
        $url = self::baseUrl() . ":runQuery";

        $query = [
            'parent' => "projects/".$projectId."/databases/(default)/documents",
            'structuredQuery' => [
                'from' => [['collectionId' => $collection]],
                'where' => [
                    'fieldFilter' => [
                        'field' => ['fieldPath' => $field],
                        'op' => $mappedOp,
                        'value' => $firestoreValue,
                    ],
                ],
            ],
        ];

        $response = Http::post($url, $query);

        if (!$response->successful()) {
            logger()->error('Firestore query failed', [
                'url' => $url,
                'query' => $query,
                'response' => $response->json(),
            ]);
            return null;
        }

        $documents = [];
        foreach ($response->json() as $doc) {
            if (isset($doc['document']['fields'])) {
                $documents[] = self::decodeFields($doc['document']['fields']);
            }
        }

        return $documents;
    }

    /** Set document from clean array */
    public static function setDocument($path, array $data)
    {
        $url = self::baseUrl() . "/{$path}";
        $payload = ['fields' => self::encodeFields($data)];

        $response = Http::patch($url, $payload);

        if (!$response->successful()) return null;

        $result = $response->json();
        return isset($result['fields']) ? self::decodeFields($result['fields']) : null;
    }
}