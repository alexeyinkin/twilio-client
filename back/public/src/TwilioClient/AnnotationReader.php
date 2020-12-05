<?php
declare(strict_types=1);

namespace TwilioClient;

use ReflectionClass;

class AnnotationReader
{
    public function doesMethodHaveAnnotation(string $class, string $method, string $annotation): bool
    {
        $annotations = $this->getMethodAnnotations($class, $method);
        return in_array($annotation, $annotations);
    }

    /**
     * @return string[]
     */
    private function getMethodAnnotations(string $class, string $method): array
    {
        $classReflection = new ReflectionClass($class);
        $methodReflection = $classReflection->getMethod($method);
        $doc = $methodReflection->getDocComment();

        return is_string($doc)
            ? $this->getAnnotationsFromString($doc)
            : [];
    }

    /**
     * @return string[]
     */
    private function getAnnotationsFromString(string $doc): array
    {
        preg_match_all('#@(.*?)\n#s', $doc, $annotations);
        return $annotations[1];
    }
}
