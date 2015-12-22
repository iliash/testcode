<?php

class Model_Phone extends Model_DbTable_Phone
{
    /**
     * Update user phone number before user finished of verification
     *
     * @param String $code
     * @param String $phone
     * @return string
     * @throws Exception
     */
    public function updateMsisdn($code, $phone)
    {
        $auth = Zend_Auth::getInstance()->getIdentity();
        $accountId = $auth->id;
        $code = preg_replace('/^\+\d+/', '', $code);

        if (!ctype_digit($code)) {
            throw new InvalidArgumentException('Invalid prefix was received', 402);
        }

        if (!ctype_digit($phone)) {
            throw new InvalidArgumentException('Invalid number was received', 402);
        }

        if ($accountId) {
            $account = new V1_Model_Account();

            if ($accountOfPhone = $account->checkPhone($code, $phone)) {
                if ($accountOfPhone != $accountId) {
                    throw new Exception('User with that phone number is exist', 411);
                }
            }

            if (!$account->checkPhoneVerification($accountId)) {
                return $account->updateAccountProfileById($accountId, array('phone_number' => $phone, 'phone_country_code' => $code));
            } else {
                throw new Exception('Account already verified', 400);
            }
        }
    }
}