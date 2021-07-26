<?php


namespace App\Security\Utils;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class AuthenticationRemeberMeUtils extends AuthenticationUtils
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        parent::__construct($requestStack);

        $this->requestStack = $requestStack;
    }

    public function getRememberMe()
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request->hasSession() ? !empty($request->getSession()->get('_remember_me', '')) : false;
    }

}
