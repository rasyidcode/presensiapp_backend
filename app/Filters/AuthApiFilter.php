<?php

namespace App\Filters;

use App\Exceptions\ApiAccessErrorException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthApiFilter implements FilterInterface
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
            throw new ApiAccessErrorException('Unauthorized', ResponseInterface::HTTP_UNAUTHORIZED);
        
        $encodedToken = getJwtFromAuthHeader($authHandler->getValue());
        $validateResult = validateAccessToken($encodedToken);
        if (!$validateResult)
            throw new ApiAccessErrorException('User not found', ResponseInterface::HTTP_UNAUTHORIZED);

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
