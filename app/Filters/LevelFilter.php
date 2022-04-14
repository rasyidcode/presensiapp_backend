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
        
        $username = $request->getVar()->username ?? null;
        if (is_null($username)) {
            throw new ApiAccessErrorException(
                message: 'Request tidak valid!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        }

        $levelRule = end($arguments);
        switch($levelRule) {
            case 'only-admin':
                if (!(new UserModel())->isAdmin($request->getVar()->username)) {
                    throw new ApiAccessErrorException(
                        message: 'Hanya Admin yang dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'only-mahasiswa':
                if (!(new UserModel())->isMahasiswa($request->getVar()->username)) {
                    throw new ApiAccessErrorException(
                        message: 'Hanya Mahasiswa yang dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'only-dosen':
                if (!(new UserModel())->isDosen($request->getVar()->username)) {
                    throw new ApiAccessErrorException(
                        message: 'Hanya Dosen yang dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'prevent-admin':
                if ((new UserModel())->isAdmin($request->getVar()->username)) {
                    throw new ApiAccessErrorException(
                        message: 'Admin dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'prevent-mahasiswa':
                if ((new UserModel())->isMahasiswa($request->getVar()->username)) {
                    throw new ApiAccessErrorException(
                        message: 'Mahasiswa dapat mengakses request ini!',
                        statusCode: ResponseInterface::HTTP_FORBIDDEN
                    );
                }
                break;
            case 'prevent-dosen':
                if ((new UserModel())->isDosen($request->getVar()->username)) {
                    throw new ApiAccessErrorException(
                        message: 'Dosen dapat mengakses request ini!',
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
