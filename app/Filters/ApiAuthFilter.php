<?php

namespace App\Filters;

use App\Exceptions\ApiAccessErrorException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Shared\Models\UserModel;

class ApiAuthFilter implements FilterInterface
{

    public function __construct()
    {
        helper('jwt');
    }

    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHandler = $request->header('Authorization');
        if (is_null($authHandler))
            throw new ApiAccessErrorException('Bad Request', ResponseInterface::HTTP_BAD_REQUEST);

        $encodedToken = getJwtFromAuthHeader($authHandler->getValue());
        if (isBlacklisted($encodedToken))
            throw new ApiAccessErrorException('Token invalid', ResponseInterface::HTTP_UNAUTHORIZED);
        
        $decodedData = validateAccessToken($encodedToken);
        $userModel = new UserModel();
        if (!$userModel->checkUser($decodedData->data->username) )
            throw new ApiAccessErrorException('User not found', ResponseInterface::HTTP_NOT_FOUND);

        $request->setHeader('Access-Token', $encodedToken);
        // print_r($decodedData->data);die();
        $request->setHeader('User-Data', $decodedData->data);

        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
