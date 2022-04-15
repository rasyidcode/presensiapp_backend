<?php

namespace App\Filters;

use App\Exceptions\ApiAccessErrorException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Shared\Models\UserModel;

class LevelFilter implements FilterInterface
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
        /**
         * available arguments:
         * - only-admin
         * - only-mahasiswa
         * - only-dosen
         * - prevent-admin
         * - prevent-mahasiswa
         * - prevent-dosen
         * 
         * if you include more than one arguments, only the last one will be affected.
         */
        if ($arguments == null) {
            throw new ApiAccessErrorException(
                message: 'Internal error!',
                statusCode: ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        
        // todo: check route if login or not
        if ($request->getUri()->getSegments()[3] == 'login') {
            $username = $request->getVar()->username ?? null;
            if (is_null($username)) {
                throw new ApiAccessErrorException(
                    message: 'Request tidak valid!',
                    statusCode: ResponseInterface::HTTP_BAD_REQUEST
                );
            }
        }
        

        $levelRule = end($arguments);
        $userModel = new UserModel();
        $username = $request->getVar()->username;
        switch($levelRule) {
            case 'only-admin':
                if (!$userModel->isAdmin($username)) {
                    throw new ApiAccessErrorException(
                        message: 'Hanya Admin yang dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'only-mahasiswa':
                if (!$userModel->isMahasiswa($username)) {
                    throw new ApiAccessErrorException(
                        message: 'Hanya Mahasiswa yang dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'only-dosen':
                if (!$userModel->isDosen($username)) {
                    throw new ApiAccessErrorException(
                        message: 'Hanya Dosen yang dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'prevent-admin':
                if ($userModel->isAdmin($username)) {
                    throw new ApiAccessErrorException(
                        message: 'Admin tidak dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'prevent-mahasiswa':
                if ($userModel->isMahasiswa($username)) {
                    throw new ApiAccessErrorException(
                        message: 'Mahasiswa tidak dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'prevent-dosen':
                if ($userModel->isDosen($username)) {
                    throw new ApiAccessErrorException(
                        message: 'Dosen tidak dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            default:
                throw new ApiAccessErrorException(
                    message: 'Internal error!',
                    statusCode: ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
                );
                break;
        }

        $role = $userModel->getRole($username);
        $request->setHeader('User-Role', $role);

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
