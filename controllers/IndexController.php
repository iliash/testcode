<?php

class AccountController extends Zend_Rest_Controller
{
    /**
     * Verify MSISDN
     * @url /v1/account/update-phone/
     *
     * @param String code
     * @param String phone
     */
    public function updatePhoneAction()
    {
        $responseHandler = V1_Model_Response::getInstance();
        $request = $this->getRequest();
        $code = trim($request->getParam('code'));
        $phone = trim($request->getParam('phone'));

        try {
            if (!Zend_Auth::getInstance()->hasIdentity()) {
                throw new Exception('User is unauthorized', 401);
            }

            $accountVerification = new Model_Phone();
            $accountVerification->updateMsisdn($code, $phone);
            $response = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'User phone number successfully updated'
            );
        } catch (Exception $ex) {
            $response = array(
                'status' => 'error',
                'code' => $ex->getCode(),
                'message' => $ex->getMessage()
            );
        }
        $responseHandler->setResponse($response);
    }
}