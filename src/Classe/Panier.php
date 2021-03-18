<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;

class Panier
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add($ref)
    {
        $panier = $this->session->get('panier', []);

        if (!empty($panier[$ref])) {
            $panier[$ref]++;
        } else {
            $panier[$ref] = 1;
        }

        $this->session->set('panier', $panier);
    }

    public function decrease($ref)
    {
        $panier = $this->session->get('panier', []);

        if ($panier[$ref] > 1) {
            $panier[$ref]--;
        } else {
            unset($panier[$ref]);
        }

        $this->session->set('panier', $panier);
    }

    public function get()
    {
        return $this->session->get('panier');
    }

    public function remove()
    {
        return $this->session->remove('panier');
    }

    public function delete($ref)
    {
        $panier = $this->session->get('panier', []);

        unset($panier[$ref]);

        return $this->session->set('panier', $panier);
    }
}
