<?php

declare(strict_types=1);

namespace VisualCraft\RestBaseBundle\Problem;

use Symfony\Component\HttpFoundation\Response;
use VisualCraft\RestBaseBundle\Response\ResponseBuilderFactory;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class ProblemResponseFactory
{
    /**
     * @var ResponseBuilderFactory
     */
    private $responseFactory;

    /**
     * @var ExceptionToProblemConverterInterface[]|iterable|null
     * @psalm-var iterable<array-key, ExceptionToProblemConverterInterface>|null
     */
    private $exceptionToProblemConverters;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @param ExceptionToProblemConverterInterface[]|iterable|null $exceptionToProblemConverters
     * @psalm-param iterable<array-key, ExceptionToProblemConverterInterface>|null $exceptionToProblemConverters
     */
    public function __construct(
        ResponseBuilderFactory $responseFactory,
        ?iterable $exceptionToProblemConverters = null,
        bool $debug = false
    ) {
        $this->responseFactory = $responseFactory;
        $this->exceptionToProblemConverters = $exceptionToProblemConverters;
        $this->debug = $debug;
    }

    public function create(\Throwable $exception): Response
    {
        $problem = null;

        if ($this->exceptionToProblemConverters !== null) {
            foreach ($this->exceptionToProblemConverters as $problemBuilder) {
                $problem = $problemBuilder->convert($exception);

                if ($problem !== null) {
                    break;
                }
            }
        }

        if (!$problem) {
            $problem = $this->createFallbackProblem();
        }

        if ($this->debug) {
            $this->addDebugInformation($problem, $exception);
        }

        return $this->responseFactory->create($this->buildBody($problem))
            ->setStatusCode($problem->getStatusCode())
            ->setHeaders($problem->getHeaders())
            ->build()
        ;
    }

    private function createFallbackProblem(): Problem
    {
        return new Problem('Internal server error', 500, 'internal_error');
    }

    private function addDebugInformation(Problem $problem, \Throwable $exception): void
    {
        foreach ($this->getExceptionsChain($exception) as $index => $item) {
            $this->addExceptionDetails($problem, $item, $index);
        }
    }

    private function addExceptionDetails(Problem $problem, \Throwable $exception, int $index): void
    {
        $problem->addDetails($index === 0 ? 'exception' : ('previous_exception_' . $index), [
            'class' => \get_class($exception),
            'message' => $exception->getMessage(),
            'stack_trace' => $exception->getTraceAsString(),
        ]);
    }

    private function buildBody(Problem $problem): ProblemResponseBody
    {
        return new ProblemResponseBody(
            $problem->getTitle(),
            $problem->getType(),
            $problem->getStatusCode(),
            $problem->getDetails()
        );
    }

    /**
     * @return \Generator|\Throwable[]
     * @psalm-return \Generator<int, \Throwable>
     */
    private function getExceptionsChain(\Throwable $exception): \Generator
    {
        yield 0 => $exception;

        $index = 1;

        while ($previous = $exception->getPrevious()) {
            $exception = $previous;

            yield $index++ => $exception;
        }
    }
}
