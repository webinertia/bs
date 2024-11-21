<?php

declare(strict_types=1);

namespace Limatus;

use Fig\Http\Message\RequestMethodInterface as Http;

interface FormInterface
{
    public final const POST_METHOD  = Http::METHOD_POST;
    public final const PUT_METHOD   = Http::METHOD_PUT;
    public final const PATCH_METHOD = Http::METHOD_PATCH;
}
