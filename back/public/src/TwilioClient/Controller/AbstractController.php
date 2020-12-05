<?php
declare(strict_types=1);

namespace TwilioClient\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TwilioClient\AnnotationReader;
use TwilioClient\Exception\NotFoundException;

class AbstractController
{
    private const RUNNABLE_ANNOTATION = 'Api';

    public function runMethod(RequestInterface $request, string $method): ResponseInterface
    {
        if (!method_exists($this, $method)) {
            throw new NotFoundException();
        }

        $annotationReader = new AnnotationReader();

        if (!$annotationReader->doesMethodHaveAnnotation(static::class, $method, self::RUNNABLE_ANNOTATION)) {
            throw new NotFoundException();
        }

        return $this->$method($request);
    }
}
