<?php


namespace App\Services;


use App\User;
use GuzzleHttp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CrmService
{
//    const $this->direct_crm_url = 'https://api.mindbox.ru/v3/operations/sync';
    const AUTH_METHOD = 'CustomerPasswordAuthorization';
    const REGISTER_METHOD = 'CustomerRegistration';
    const RESET_METHOD = 'SendEmailWithPasswordReminder';
    const UPLOAD_METHOD = 'DuSoleil2019.Upload';
    const UPDATE_USER = 'EditCustomerData';
    const CHECK_TICKET = 'CustomerTicketAuthorization';
    const GET_DATA = 'SearchAndGetCustomerData';
    const REFUSE_JOB = 'DuSoleil2019.Reject';
    const SHARE_OK = 'DuSoleil2019.ShareOK';
    const SHARE_FB = 'DuSoleil2019.ShareFB';
    const SHARE_VK = 'DuSoleil2019.ShareVK';
    public $data;
    public $client;
    public $headers;
    private $endpoint;
    private $secretKey;
    private $direct_crm_url;

    public function __construct()
    {
        $this->direct_crm_url = env('DIRECT_CRM', '');
        $this->endpoint = ENV('CRM_ENDPOINT', '');
        $this->secretKey = ENV('CRM_SECRET_KEY', '');
        $this->client = new GuzzleHttp\Client(['headers' => $this->getHeaders()]);
    }

    public function getHeaders()
    {
        return array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Mindbox secretKey="' . $this->secretKey . '"',
            'X-Customer-IP' => $_SERVER['REMOTE_ADDR']
        );
    }

    public function setData(array $data, $inData = false)
    {
        $inData ? $this->data["customer"] = $data : $this->data = $data;
    }

    public function getRequest($method, $excludeDevice = false)
    {
        $headers = array(
            'endpointId' => $this->endpoint,
            'operation' => $method
        );
        if (!$excludeDevice) {
            $headers['deviceUUID'] = $_COOKIE['deviceUUID'] ?? '661d5b4c-2c0f-416a-89f0-e2d4107ecf65';
        }
        return http_build_query(
            $headers
        );
    }

    /*
        * @method refuseJob
        *
        * @param Request $request
        *
        * @return boolean
        *
        * @throws Exception
        *
        */
    public function refuseJob(Request $request)
    {
        /* @var User $user */
        $user = $request->user();
        try {
            $this->setData(
                array(
                    'ids' => array(
                        'mindboxId' => $user->mindbox_id
                    ),
                )
                , true);
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::REFUSE_JOB, true), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            if ($response->getStatusCode() == 200) {
                $resData = json_decode($response->getBody()->getContents(), true);
                if ($resData['status'] == "Success") {
                    return true;
                }
            }
            throw new Exception(__('auth_failed'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * @method checkTicket
     *
     * @param Request $request
     *
     * @return boolean
     *
     * @throws Exception
     *
     */
    public function checkTicket(Request $request)
    {
        /* @var User $user */
        try {
            $this->setData(
                array(
                    'customer' => array(
                        'authenticationTicket' => $request->ticket,
                    )
                )
                , false);
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::CHECK_TICKET, false), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            if ($response->getStatusCode() == 200) {
                $resData = json_decode($response->getBody()->getContents(), true);
                if (isset($resData['customer'])) {
                    if ($resData['customer']['processingStatus'] == "Found") {
                        return $resData['customer'];
                    }
                }
            }
            throw new Exception(__('auth_failed'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function resetPassword($email)
    {
        $error = 1;
        try {
            $this->setData(
                array(
                    'email' => $email
                )
                , true);
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::RESET_METHOD), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            if ($response->getStatusCode() == 200) {
                $resData = json_decode($response->getBody()->getContents(), true);
                if (isset($resData['customer'])) {
                    if ($resData['customer']['processingStatus'] == "Found") {
                        $error = 0;
                    }
                }
            }
        } catch (Exception $e) {
        }
        return array(
            'error' => $error
        );
    }

    /*
     * @method updateUser
     *
     * @param User $user
     * @param User $oldUser
     *
     * @return boolean
     *
     * @throws Exception
     *
     */
    public function updateUser(User $user, User $oldUser, $password = '')
    {
        try {
            /* @var User $user */
            $this->setData(
                array(
                    'ids' => array(
                        'mindboxId' => $user->mindbox_id
                    ),
                    'firstName' => $user->first_name ?? $oldUser->first_name ?? '',
                    'lastName' => $user->second_name ?? $oldUser->second_name ?? '',
                    'birthDate' => $user->birth_date ?? $oldUser->birth_date ?? '',
                    'email' => $user->email ?? $oldUser->email ?? '',
                    'sex' => $user->sex ?? $oldUser->gender ?? ''
                )
                , true);
            if ($password) {
                $this->data["customer"]["password"] = $password;
            }
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::UPDATE_USER, true), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            $resData = json_decode($response->getBody()->getContents(), true);
            if ($response->getStatusCode() == 200) {
                if (isset($resData['customer'])) {
                    return ($resData['customer']['processingStatus'] == "Changed");
                }
            }
            if ($resData["status"] == "ValidationError" && $resData["validationMessages"]) {
                throw new Exception($resData["validationMessages"][0]["message"]);
            }
            throw new Exception(__('app.cant_save'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function register($email, $password)
    {
        $error = 1;
        try {
            $this->setData(
                array(
                    'email' => $email,
                    'password' => $password
                )
                , true);
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::REGISTER_METHOD), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            $resData = json_decode($response->getBody()->getContents(), true);
            if ($response->getStatusCode() == 200) {
                if (isset($resData['customer'])) {
                    if ($resData['customer']['processingStatus'] == "Created") {
                        $error = 0;
                    }
                }
            }
        } catch (Exception $e) {

        }
        return array(
            'error' => $error,
            'data' => $resData
        );
    }

    public function upload(Request $request)
    {
        $error = 1;
        $data = array();
        /* @var User $user */
        $user = $request->user();
        try {
            if (!$user->mindbox_id) {
                throw new \Exception();
            }
            $this->setData(
                array(
                    'ids' => array(
                        'mindboxId' => $user->mindbox_id
                    )
                )
                , true);
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::UPLOAD_METHOD, true), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            if ($response->getStatusCode() == 200) {
                $resData = json_decode($response->getBody()->getContents(), true);
                if ($resData['status'] == "Success") {
                    $data = $resData;
                    $error = 0;
                }
            }
        } catch (Exception $e) {

        }
        return array(
            'error' => $error,
            'data' => $data
        );
    }

    /*
     * @method updateUser
     *
     * @param Request $request
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function auth(Request $request)
    {
        try {
            $this->setData(
                array(
                    'email' => $request->email,
                    'password' => $request->password
                )
                , true);
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::AUTH_METHOD), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            if ($response->getStatusCode() == 200) {
                $resData = json_decode($response->getBody()->getContents(), true);
                if (isset($resData['customer'])) {
                    if ($resData['customer']['processingStatus'] == "AuthenticationSucceeded") {
                        return $resData['customer'];
                    }
                }
            }
            throw new Exception(__('app.auth_failed'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * @method SearchAndGetCustomerData
     *
     * @param Request $request
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function SearchAndGetCustomerData(Request $request)
    {
        try {
            $this->setData(
                array(
                    'email' => $request->email
                )
                , true);
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($this::GET_DATA, true), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            if ($response->getStatusCode() == 200) {
                $resData = json_decode($response->getBody()->getContents(), true);
                if (isset($resData['customer'])) {
                    if ($resData['customer']['processingStatus'] == "Found") {
                        return $resData['customer'];
                    }
                }
            }
            throw new Exception(__('app.user_not_found'));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * @method share
     *
     * @param Request $request
     *
     * @return array
     *
     * @throws Exception
     *
     */
    public function share(Request $request)
    {
        /* @var User $user */
        $user = $request->user();
        try {
            if (!$user->mindbox_id) {
                throw new \Exception();
            }
            $this->setData(
                array(
                    'ids' => array(
                        'mindboxId' => $user->mindbox_id
                    )
                )
                , true);
            switch ($request->provider) {
                case 'ok':
                    $method = $this::SHARE_OK;
                    break;
                case 'fb':
                    $method = $this::SHARE_FB;
                    break;
                case 'vk':
                    $method = $this::SHARE_VK;
                    break;
                default:
                    throw new Exception(__('app.auth_failed'));
            }
            $response = $this->client->post($this->direct_crm_url . '?' . $this->getRequest($method, true), [GuzzleHttp\RequestOptions::JSON => $this->data]);
            if ($response->getStatusCode() == 200) {
                $resData = json_decode($response->getBody()->getContents(), true);
                if ($resData['status'] == "Success") {
                    return true;
                }
            }
            throw new Exception(__('app.auth_failed'));
        } catch (Exception $e) {
            return false;
        }
    }
}