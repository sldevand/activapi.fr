<?php

namespace App\Backend\Modules\User;

use Entity\User\User;
use Exception;
use Model\User\UsersManagerPDO;
use OCFram\Application;
use OCFram\BackController;
use OCFram\HTTPRequest;
use SFram\CsrfTokenManager;
use SFram\Helpers\Random;

/**
 * Class UserController
 * @package App\Backend\Modules\User
 */
class UserController extends BackController
{
    /** @var UsersManagerPDO */
    protected $manager;

    /** @var CsrfTokenManager */
    protected $csrfTokenManager;

    /**
     * UserController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws Exception
     */
    public function __construct(Application $app, string $module, string $action)
    {
        parent::__construct($app, $module, $action);
        $this->manager = $this->managers->getManagerOf('User\Users');
        $this->csrfTokenManager = new CsrfTokenManager();
    }

    /**
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executeLogin(HTTPRequest $request): \OCFram\Page
    {
        try {
            $this->checkMethod($request, 'POST');
            CsrfTokenManager::verify($request->getJsonPost()['token']);

            $requiredParamKeys = ['email', 'password'];
            $params = $this->getRequiredParams($request, $requiredParamKeys);

            /** @var User $user */
            if (empty($user = $this->manager->getUniqueBy('email', $params['email']))
                || !password_verify((string) $params['password'], (string) $user->getPassword())
            ) {
                throw new Exception("Invalid user/password");
            }
        } catch (Exception $e) {
            return $this->page()->addVar('data', ['error' => $e->getMessage()]);
        }

        $this->app()->user()->setAuthenticated(true);

        return $this->page()->addVar('data', ['data' => "Successfully logged in"]);
    }

    /**
     * @return \OCFram\Page
     * @throws Exception
     */
    public function executeRegister(HTTPRequest $request): \OCFram\Page
    {
        try {
            $this->checkMethod($request, 'POST');
            CsrfTokenManager::verify($request->getJsonPost()['token']);

            $requiredParamKeys = ['email', 'password', 'password-repeat'];
            $params = $this->getRequiredParams($request, $requiredParamKeys);

            if ($params['password'] !== $params['password-repeat']) {
                throw new Exception("Passwords don't equal!");
            }

            if ($this->manager->getAdminUser()) {
                throw new Exception('There is already one admin user');
            }

            $params['password'] = password_hash((string) $params['password'], PASSWORD_DEFAULT);
            unset($params['password-repeat']);

            $user = new User($params);
            $user->setRole('admin');
            $user->setActivationCode(Random::createRandomToken());
            $user->setActivated(false);

            $this->manager->save($user);
        } catch (Exception $e) {
            return $this->page()->addVar('data', ['error' => $e->getMessage()]);
        }

        $this->app()->user()->setAuthenticated(true);

        return $this->page()->addVar('data', ['data' => "Successfully registered"]);
    }

    /**
     * @throws \Exception
     */
    public function executeLogout(HTTPRequest $request)
    {
        $this->app()->user()->setAuthenticated(false);
        $this->app()->user()->setFlash('You have logged out');
        $this->app()->httpResponse()->redirect($this->baseAddress . 'user/login');
    }

    /**
     * @return array|\OCFram\Page
     * @throws Exception
     */
    protected function getRequiredParams(HTTPRequest $request, array $requiredParamKeys)
    {
        $validatedParams = [];
        $post = $request->getJsonPost();
        foreach ($requiredParamKeys as $requiredParamKey) {
            if (!$post[$requiredParamKey]) {
                throw new Exception("A required parameter is missing");
            }
            $validatedParams[$requiredParamKey] = htmlspecialchars(
                (string) $post[$requiredParamKey]
            );
        }

        return $validatedParams;
    }
}
