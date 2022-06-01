<?php
declare(strict_types=1);

namespace PmnGarage\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use PmnGarage\Domain\DomainException\DomainRecordNotFoundException;

abstract class Action
{
    protected LoggerInterface $logger;

    protected Request $request;

    protected Response $response;

    protected array $args;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (DomainRecordNotFoundException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }

    /**
     * @throws DomainRecordNotFoundException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     */
    protected function getFormData(): object|array
    {
        return $this->request->getParsedBody();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function resolveArg(string $name): mixed
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    protected function respond(string $payload): Response
    {
        $this->response->getBody()->write($payload);

        return $this->response->withHeader('Content-Type', 'application/json');
    }
}
