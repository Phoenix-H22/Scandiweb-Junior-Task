<?php

namespace App\Controllers;

use App\GraphQL\GraphQLSchema;
use GraphQL\GraphQL;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;

class GraphQLController
{
    public function handle(): void
    {
        try {
            $schema = GraphQLSchema::createSchema();
            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $query = $input['query'] ?? '';
            $variables = $input['variables'] ?? null;

            $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
            $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);

            header('Content-Type: application/json');
            echo json_encode($output);
        } catch (\Throwable $e) {
            $error = FormattedError::createFromException($e);
            $output = [
                'errors' => [$error]
            ];
            header('Content-Type: application/json');
            echo json_encode($output);
        }
    }
}