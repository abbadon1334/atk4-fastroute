<?php

declare(strict_types=1);

// DO NOT USE THIS
// is used only to trigger exception in PHUNIT Test

return [
    [
        '/routed_static',
        ['GET', 'POST'],
        ['function_not_exists_will_throw_exception'],
    ],
];
