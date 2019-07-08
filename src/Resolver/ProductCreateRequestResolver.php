<?php

namespace BoundaryWS\Resolver;

use Slim\Http\Request;
use BoundaryWS\Exception\ApiException;
use BoundaryWS\Factory\ApplicationErrorFactory;

// TODO remove duplication of error code details and refactor into something we can reuse

class ProductCreateRequestResolver {

    /**
     * Resolves the Product create request.
     *
     * @param Request $request
     * @return array
     */
    public function resolve(Request $request): array
    {
        $data = $request->getParsedBody();

        if (false === isset($data['data'])) {
            $error = ApplicationErrorFactory::create(
                'ERR-1',
                'Required Field',
                'Required field \'data\' is missing.',
                400,  
                ['pointer' => '/']    
            );
            $this->checkForErrors([$error]);
        }


        $requiredFields = [
            'display_name',
            'cost'
        ];

        $errors = [];
        foreach ($requiredFields as $field) {
            if (false === isset($data['data'][$field])) {
                $errors[] = ApplicationErrorFactory::create(
                    'ERR-1',
                    'Required Field',
                    "Required field '{$field}' is missing.",
                    400,
                    ['pointer' => '/data']
                );
            }
        }

        $this->checkForErrors($errors);

        foreach ($requiredFields as $field) {
            if ("" === $data['data'][$field]) {
                $errors[] = ApplicationErrorFactory::create(
                    'ERR-2',
                    'Required Field',
                    "Required field '{$field}' is empty.",
                    400,
                    ['pointer' => "/data/{$field}"]
                );
            }
        }

        $this->checkForErrors($errors);


        if (false == preg_match('/^[0-9]+(?:\.[0-9]{1,2})?$/', $data['data']['cost'])) {
            $error = ApplicationErrorFactory::create(
                'ERR-3',
                'Invalid Format',
                'Field \'cost\' has an invalid format.',
                400,
                ['pointer' => '/data/cost']
            );
            $this->checkForErrors([$error]);
        }

        return $data;
    }

    /**
     * Checks if we have errors and throws ApiException
     *
     * @param array $errors
     * @return void
     * @throws ApiException
     */
    private function checkForErrors(array $errors): void
    {
        if (false === empty($errors)) {
            throw new ApiException($errors, $errors[0]->getStatus());
        }
    }
}
