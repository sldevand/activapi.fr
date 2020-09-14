<?php

namespace App\Backend\Modules\User;

use Entity\User\User;
use Model\User\UsersManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use SFram\Helpers\Random;

/**
 * Class UserController
 * @package App\Backend\Modules\User
 */
class UserController extends BackController
{
    /** @var UsersManagerPDO */
    protected $manager;

    /**
     * UserController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws \Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('User\Users');
    }

    /**
     * @param HTTPRequest $request
     * @return \OCFram\Page
     */
    public function executeLogin(HTTPRequest $request)
    {
        if ($request->method() !== 'POST') {
            http_response_code(400);
            return $this->page()->addVar('data', ['error' => "Must be a POST method!"]);
        }
        $requiredParamKeys = ['email', 'password'];
        if (!$params = $this->checkRequiredParams($request, $requiredParamKeys)) {
            http_response_code(400);
            return $this->page()->addVar('data', ['error' => "A required parameter is missing"]);
        }

        try {
            /** @var User $user */
            $user = $this->manager->getUniqueBy('email', $params['email']);
            if (empty($user)) {
                return $this->page()->addVar('data', ['error' => "This user does not exist!"]);
            }
            if (!password_verify($params['password'], $user->getPassword())) {
                return $this->page()->addVar('data', ['error' => "The password is not valid!"]);
            }
        } catch (\Exception $e) {
            return $this->page()->addVar('data', ['error' => $e->getMessage()]);
        }

        return $this->page()->addVar('data', ['data' => "Successfully logged in"]);
    }

    /**
     * @param HTTPRequest $request
     * @return \OCFram\Page
     */
    public function executeRegister(HTTPRequest $request)
    {
        if ($request->method() !== 'POST') {
            http_response_code(400);
            return $this->page()->addVar('data', ['error' => "Must be a POST method!"]);
        }
        $requiredParamKeys = ['email', 'firstName', 'lastName', 'password', 'password-repeat'];
        if (!$params = $this->checkRequiredParams($request, $requiredParamKeys)) {
            http_response_code(400);
            return $this->page()->addVar('data', ['error' => "A required parameter is missing"]);
        }

        if ($params['password'] !== $params['password-repeat']) {
            return $this->page()->addVar('data', ['error' => "Passwords don't equal!"]);
        }

        try {
            /** @var User $user */
            $user = $this->manager->getUniqueBy('email', $params['email']);
            if ($user) {
                return $this->page()->addVar('data', ['error' => "This user already exists!"]);
            }

            unset($params['password-repeat']);

            $user = new User($params);
            $user->setRole('admin');
            $user->setActivationCode(Random::createRandomToken());
            $user->setActivated(false);

            $this->manager->save($user);
        } catch (\Exception $e) {
            return $this->page()->addVar('data', ['error' => $e->getMessage()]);
        }

        return $this->page()->addVar('data', ['data' => "Successfully registered"]);
    }

    /**
     * @param HTTPRequest $request
     * @param array $requiredParamKeys
     * @return array|\OCFram\Page
     */
    protected function checkRequiredParams(HTTPRequest $request, array $requiredParamKeys)
    {
        $validatedParams = [];
        foreach ($requiredParamKeys as $requiredParamKey) {
            if (!$request->postExists($requiredParamKey)) {
                return [];
            }
            $validatedParams[$requiredParamKey] = htmlspecialchars(
                $request->postData($requiredParamKey)
            );
        }

        return $validatedParams;
    }
}
