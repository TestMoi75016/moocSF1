<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface; // Assurez-vous d'importer le générateur d'URL

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        // add a custom flash message and redirect to the login page
        $request->getSession()->getFlashBag()->add('danger', 'Vous devez avoir les droits administrateur pour accéder à cette page.');

        return new RedirectResponse($this->urlGenerator->generate('app_jeu'));

    }
}