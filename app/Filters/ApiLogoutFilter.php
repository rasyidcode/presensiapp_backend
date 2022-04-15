<?php

namespace App\Filters;

use App\Exceptions\ApiAccessErrorException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\User\Models\UserModel;

class ApiLogoutFilter implements FilterInterface
{
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
        if ($request->getUri()->getSegments()[3] === 'signOut') {
            $refreshTokenHandler = $request->header('Refresh-Token');
            if (is_null($refreshTokenHandler))
                throw new ApiAccessErrorException('Please provide your refresh token', ResponseInterface::HTTP_BAD_REQUEST);
            
            $refreshToken = $refreshTokenHandler->getValue();
            if (empty($refreshToken))
                throw new ApiAccessErrorException('Please provide your refresh token', ResponseInterface::HTTP_BAD_REQUEST);

            $userModel = new UserModel();
            if (!$userModel->checkUserByRefreshToken($refreshToken))
                throw new ApiAccessErrorException('Refresh token not found', ResponseInterface::HTTP_BAD_REQUEST);

            $request->setHeader('refreshToken', $refreshToken);

            return $request;
        }
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
