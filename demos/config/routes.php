<?php

return [
    [
        ['GET', 'POST'],
        '/test',
        ['StandardClass', 'handleRequest'],
    ],
    [
        ['GET', 'POST'],
        '/test2',
        ['ATKView', ['text' => 'it works']],
    ],
    [
        ['GET', 'POST'],
        '/callable',
        ['handleWithFunction'],
    ],
];
