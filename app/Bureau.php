<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use AWS;
use Config;


class Bureau extends Model
{
    protected $table = null;
    protected $client = null;
    protected $guarded = [];

    public function __construct()
	{
	    $this->table = env('BUREAUS_TABLE', 'applicant-bureau-api-data-prod');
        $this->client = AWS::createClient('DynamoDb');
	}

    public function search($document)
    {
        return $this->client->getIterator('Query', array(
            'TableName'     => $this->table,
            'KeyConditions' => array(
                'document' => array(
                    'AttributeValueList' => array(
                        array('S' => $document)
                    ),
                    'ComparisonOperator' => 'EQ'
                ),
            )
        ));
    }

    public function putItem(array $data, string $document, string $bureau, string $client, string $solicitation, string $type, string $dated)
    {
        $this->client->putItem(array(
            'TableName' => $this->table,
            'Item' => array(
                'document'      => array('S' => $document),
                'bureau_client_solicitation_ids'    => array('S' => "$bureau#$client#$solicitation"),
                'data'   => array('M' => json_decode($data['data'], true)),
                'type' => array('S' => $type),
                'dated' => array('S' => $dated)
            )
        ));
    }

    public function updateItem(array $data, string $document, string $bureau, string $client, string $solicitation)
    {
        $this->client->updateItem(array(
            'TableName' => $this->table,
            'Key' => array(
                'document'      => array('S' => $document),
                'bureau_client_solicitation_ids'    => array('S' => "$bureau#$client#$solicitation"),
            ),
        
            'AttributeUpdates' => array(
                'data'   => array('Value' => array('M' => json_decode($data['data'], true)), 
                                  'Action' => 'PUT'),
            )
        ));
    }

    public function deleteItem(string $document, string $bureau, string $client, string $solicitation)
    {
        $this->client->deleteItem(array(
            'TableName' => $this->table,
            'Key' => array(
                'document'   => array('S' => $document),
                'bureau_client_solicitation_ids' => array('S' => "$bureau#$client#$solicitation")
            )
        ));
    }

    public function getDocumentsWithProcesses()
    {
        $list = [];

        $lastEvaluatedKey = array('document' => array('S' => env('START_DOCUMENT')), 
                                  'bureau_client_solicitation_ids' => array('S' => env('START_BUREAU_ID')));
    
        while(!empty($lastEvaluatedKey) && count($list) < 10){
            $results = $this->client->scan(array(
                'TableName'     => $this->table,
                'ExclusiveStartKey' => $lastEvaluatedKey,
                'FilterExpression' => 'contains(#bcsid,:word)',
                'ExpressionAttributeNames' => ['#bcsid' => 'bureau_client_solicitation_ids'],
                'ExpressionAttributeValues' => [':word' => ['S' => 'bigdata']],            
            ));

            foreach($results['Items'] as $item)
            {
                $dated = strtotime($item['dated']['S']);
                $year = (int) date('Y',$dated);
                if(($year == date('Y') || $year == date('Y')-1) && !empty($item['data']['M']['Result']['L'][0]['M']['Processes']['M']['Lawsuits']['L'])){
                    array_push($list, $item);
                }
            }

            $lastEvaluatedKey = $results['LastEvaluatedKey'];        
        }

        return $list;
    }

    public function getDocumentsWithPendencies()
    {
        $list = [];

        $lastEvaluatedKey = array('document' => array('S' => env('START_DOCUMENT')), 
                                  'bureau_client_solicitation_ids' => array('S' => env('START_BUREAU_ID')));
    
        while(!empty($lastEvaluatedKey) && count($list) < 10){
            $results = $this->client->scan(array(
                'TableName'     => $this->table,
                'ExclusiveStartKey' => $lastEvaluatedKey,
                'FilterExpression' => 'contains(#bcsid,:word) ',
                'ExpressionAttributeNames' => ['#bcsid' => 'bureau_client_solicitation_ids'],
                'ExpressionAttributeValues' => [':word' => ['S' => 'serasa']],            
            ));

            foreach($results['Items'] as $item)
            {
                $dated = strtotime($item['dated']['S']);
                $year = (int) date('Y',$dated);
                if(($year == date('Y') || $year == date('Y')-1) 
                        && isset($item['data']['M']['pendencia_pagamento'])){
                    array_push($list, $item);
                }
            }

            $lastEvaluatedKey = $results['LastEvaluatedKey'];        
        }

        return $list;
    }

    public function getDocumentsWithProtests()
    {
        $list = [];

        $lastEvaluatedKey = array('document' => array('S' => env('START_DOCUMENT')), 
                                  'bureau_client_solicitation_ids' => array('S' => env('START_BUREAU_ID')));
    
        while(!empty($lastEvaluatedKey) && count($list) < 50){
            $results = $this->client->scan(array(
                'TableName'     => $this->table,
                'ExclusiveStartKey' => $lastEvaluatedKey,
                'FilterExpression' => 'contains(#bcsid,:word) ',
                'ExpressionAttributeNames' => ['#bcsid' => 'bureau_client_solicitation_ids'],
                'ExpressionAttributeValues' => [':word' => ['S' => 'bigdata']],            
            ));

            foreach($results['Items'] as $item)
            {
                $dated = strtotime($item['dated']['S']);
                $year = (int) date('Y',$dated);
                if(($year == date('Y')) 
                        && !empty($item['data']['M']['Result']['L'][0]['M']['OnlineCertificates']['L'][0]['M']['AdditionalOutputData']['M'])){
                    array_push($list, $item);
                }
            }

            $lastEvaluatedKey = $results['LastEvaluatedKey'];        
        }

        return $list;
    }

    public function getDocumentsWithChecks()
    {
        $list = [];

        $lastEvaluatedKey = array('document' => array('S' => env('START_DOCUMENT')), 
                                  'bureau_client_solicitation_ids' => array('S' => env('START_BUREAU_ID')));
    
        while(!empty($lastEvaluatedKey) && count($list) < 10){
            $results = $this->client->scan(array(
                'TableName'     => $this->table,
                'ExclusiveStartKey' => $lastEvaluatedKey,
                'FilterExpression' => 'contains(#bcsid,:word) ',
                'ExpressionAttributeNames' => ['#bcsid' => 'bureau_client_solicitation_ids'],
                'ExpressionAttributeValues' => [':word' => ['S' => 'serasa']],            
            ));

            foreach($results['Items'] as $item)
            {
                $dated = strtotime($item['dated']['S']);
                $year = (int) date('Y',$dated);
                if(($year == date('Y') || $year == date('Y')-1) 
                        && isset($item['data']['M']['cheques'])){
                    array_push($list, $item);
                }
            }

            $lastEvaluatedKey = $results['LastEvaluatedKey'];        
        }

        return $list;
    }

    public function getDocumentsWithInquiries()
    {
        $list = [];

        $lastEvaluatedKey = array('document' => array('S' => env('START_DOCUMENT')), 
                                  'bureau_client_solicitation_ids' => array('S' => env('START_BUREAU_ID')));
    
        while(!empty($lastEvaluatedKey) && count($list) < 10){
            $results = $this->client->scan(array(
                'TableName'     => $this->table,
                'ExclusiveStartKey' => $lastEvaluatedKey,
                'FilterExpression' => 'contains(#bcsid,:word) ',
                'ExpressionAttributeNames' => ['#bcsid' => 'bureau_client_solicitation_ids'],
                'ExpressionAttributeValues' => [':word' => ['S' => 'serasa']],            
            ));

            foreach($results['Items'] as $item)
            {
                $dated = strtotime($item['dated']['S']);
                $year = (int) date('Y',$dated);
                if(($year == date('Y') || $year == date('Y')-1) 
                    && (isset($item['data']['M']['registros']))
                    && (int) $item['data']['M']['registros']['M']['resumo']['M']['quantidade_consultas_credito']['N'] > 0){
                    array_push($list, $item);
                }
            }

            $lastEvaluatedKey = $results['LastEvaluatedKey'];        
        }

        return $list;
    }
}
