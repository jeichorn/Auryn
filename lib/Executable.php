<?php

namespace Auryn;

class Executable {
    private $callableReflection;
    private $methodInvocationObject;
    private $isMethod;

    public function __construct(\ReflectionFunctionAbstract $reflection, $invocationObject = NULL) {
        if ($reflection instanceof \ReflectionMethod) {
            $this->isMethod = TRUE;
            $this->setMethodCallable($reflection, $invocationObject);
        } else {
            $this->isMethod = FALSE;
            $this->callableReflection = $reflection;
        }
    }

    private function setMethodCallable(\ReflectionMethod $reflection, $invocationObject) {
        if (is_object($invocationObject)) {
            $this->callableReflection = $reflection;
            $this->methodInvocationObject = $invocationObject;
        } elseif ($reflection->isStatic()) {
            $this->callableReflection = $reflection;
        } else {
            throw new \InvalidArgumentException(
                'ReflectionMethod callables must specify an invocation object'
            );
        }
    }

    public function getCallableReflection() {
        return $this->callableReflection;
    }

    public function getInvocationObject() {
        return $this->methodInvocationObject;
    }

    public function __invoke() {
        $args = func_get_args();

        return $this->isMethod
            ? $this->callableReflection->invokeArgs($this->methodInvocationObject, $args)
            : $this->callableReflection->invokeArgs($args);
    }
}
