<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\View;

use Atk4\Ui\View;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractView extends View
{
    /** @var ServerRequestInterface */
    protected $request;

    public function __construct(ServerRequestInterface $request, array $defaults = null)
    {
        $this->request = $request;
        parent::__construct($defaults ?? []);
    }
}
