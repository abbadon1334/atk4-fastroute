<?php

declare(strict_types=1);

namespace Abbadon1334\ATKFastRoute\Handler;

use Abbadon1334\ATKFastRoute\Exception\StaticFileExtensionNotAllowed;
use Abbadon1334\ATKFastRoute\Exception\StaticFileNotExists;
use Abbadon1334\ATKFastRoute\Handler\Contracts\AfterRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\BeforeRoutableTrait;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iAfterRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iArrayable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iBeforeRoutable;
use Abbadon1334\ATKFastRoute\Handler\Contracts\iOnRoute;
use Mimey\MimeTypes;
use Throwable;

class RoutedServeStatic implements iOnRoute, iArrayable, iAfterRoutable, iBeforeRoutable
{
    use AfterRoutableTrait {
        OnAfterRoute as _OnAfterRoute;
    }

    use BeforeRoutableTrait {
        OnBeforeRoute as _OnBeforeRoute;
    }

    /** @var string */
    protected $path;

    /** @var array */
    protected $extensions = [];

    /**
     * RoutedCallable constructor.
     *
     * @param string $path       Base path for serving static files
     * @param array  $extensions
     */
    public function __construct(string $path, array $extensions)
    {
        $this->path       = $path;
        $this->extensions = $extensions;
    }

    public static function fromArray(array $array): iOnRoute
    {
        return new static(...$array);
    }

    public function toArray(): array
    {
        return [$this->path, $this->extensions];
    }

    /**
     * @param mixed ...$parameters
     *
     * @return mixed
     */
    public function onRoute(...$parameters)
    {
        $request_path = array_shift($parameters);

        // remove query part;
        $request_path = strtok($request_path, '?');

        // get path parts
        $path = pathinfo($request_path, PATHINFO_DIRNAME);
        $file = pathinfo($request_path, PATHINFO_BASENAME);

        $folder_path = $this->getFolderPath($path);

        try {
            $this->isDirAllowed($folder_path);

            $file_path = $folder_path.DIRECTORY_SEPARATOR.$file;
            $this->isFileAllowed($file_path);

            $this->serveFile($file_path);
        } catch (Throwable $t) {
            http_response_code(403);
            echo $t->getMessage();
        }
    }

    private function getFolderPath(string $path = null)
    {
        return null === $path || '.' === $path
            ? $this->path
            : implode(DIRECTORY_SEPARATOR, [$this->path, $path]);
    }

    private function isDirAllowed($path): void
    {
        $path  = realpath($path);
        $vroot = getcwd();

        if (substr(realpath($path), 0, strlen($vroot)) !== $vroot || !is_dir($path)) {
            throw (new StaticFileExtensionNotAllowed('Requested file folder is not allowed'))->addMoreInfo('path', $path)->addMoreInfo('fullpath', realpath($path));
        }
    }

    private function isFileAllowed($filepath): void
    {
        $ext = pathinfo($filepath, PATHINFO_EXTENSION);

        if (!$this->isExtensionAllowed($ext)) {
            throw (new StaticFileExtensionNotAllowed('Extension is not allowed'))->addMoreInfo('ext', $ext);
        }

        if (!file_exists($filepath)) {
            throw new StaticFileNotExists('Requested File extension not exists');
        }
    }

    private function isExtensionAllowed($ext)
    {
        return in_array($ext, $this->extensions);
    }

    private function serveFile(string $file_path): void
    {
        http_response_code(200);

        $filename = pathinfo($file_path, PATHINFO_BASENAME);
        $ext      = pathinfo($file_path, PATHINFO_EXTENSION);

        $mimeType = (new MimeTypes())->getMimeType($ext);

        header('Cache-Control: max-age=86400');
        header('X-Sendfile: '.$file_path);
        //header("Content-Type: application/octet-stream");
        header('Content-Type: '.$mimeType.'');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        readfile($file_path);
    }
}
