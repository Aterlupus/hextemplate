<?php
declare(strict_types=1);

namespace Test\Functional\Shared;

use App\Core\Suit\ResponseCodesSuit;
use App\Core\Format\StackTraceFormatter;
use App\Core\Util\TypeInspector;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

trait FunctionalExecutionTrait
{
    private ?Exception $erroneousResponse = null;

    private bool $catchException = true;


    abstract protected function getClientBrowser(): KernelBrowser;

    protected static function json(): RequestJson
    {
        return new RequestJson;
    }

    protected function executeRequest(
        string $method,
        string $route,
        ?RequestJson $jsonRequest = null,
        ?array $files = null,
        ?array $headers = null
    ): mixed {
        $this->erroneousResponse = null;
        $body = null !== $jsonRequest ? (string) $jsonRequest : null;
        $files = null !== $files ? $files : [];
        $headers = null !== $headers ? static::getClientHeaders($headers) : [];
        $server = array_merge(
            [
                'CONTENT_TYPE' => HttpMethodToContentTypeAssocSuit::getValue($method),
                'HTTPS' => true
            ],
            $headers
        );

        try {
            static::getClientBrowser()->catchExceptions($this->catchException);
            static::getClientBrowser()->request(
                $method, $route, [], $files, $server, $body
            );
        } catch (Exception $e) {
            $this->erroneousResponse = $e;
        }

        return $this->getResponseJson();
    }

    protected function get(string $route, ?RequestJson $requestJson = null, ?array $files = null, ?array $headers = null): mixed
    {
        return $this->executeRequest('get', $route, $requestJson, $files, $headers);
    }

    protected function put(string $route, ?RequestJson $requestJson = null, ?array $files = null, ?array $headers = null): mixed
    {
        return $this->executeRequest('put', $route, $requestJson, $files, $headers);
    }

    protected function post(string $route, ?RequestJson $requestJson = null, ?array $files = null, ?array $headers = null): mixed
    {
        return $this->executeRequest('post', $route, $requestJson, $files, $headers);
    }

    protected function patch(string $route, ?RequestJson $requestJson = null, ?array $files = null, ?array $headers = null): mixed
    {
        return $this->executeRequest('patch', $route, $requestJson, $files, $headers);
    }

    protected function delete(string $route, ?RequestJson $requestJson = null, ?array $files = null, ?array $headers = null): mixed
    {
        return $this->executeRequest('delete', $route, $requestJson, $files, $headers);
    }

    protected function getResponseJson()
    {
        if (null === $this->erroneousResponse) {
            $content = static::getClientBrowser()->getResponse()->getContent();
            return json_decode($content, true);
        } else {
            return $this->erroneousResponse;
        }
    }

    protected function getResponseCode(): int
    {
        if (null !== $this->erroneousResponse) {
            return -1; //TODO: Unknown, since ignored exception doesn't resolve to status code. Reconsider
        } else {
            return static::getClientBrowser()->getResponse()->getStatusCode();
        }
    }

    protected function getResponseHeaders(): ResponseHeaderBag
    {
        return $this->client->getResponse()->headers;
    }

    protected function getResponseHeader(string $name): ?string
    {
        return $this->getResponseHeaders()->get($name);
    }

    protected function getHeaderLocation(): array|string|null
    {
        return str_replace('/api', '', $this->getResponseHeader('location'));
    }

    private static function getClientHeaders(array $headers): array
    {
        $clientHeaders = [];

        foreach ($headers as $headerKey => $headerValue) {
            $clientHeaders[sprintf('HTTP_%s', $headerKey)] = $headerValue;
        }

        return $clientHeaders;
    }

    protected function assertResponseCode(int $code, ?string $message = ''): void
    {
        self::assertEquals($code, $this->getResponseCode(), $message);
    }

    protected function throwError(): never
    {
        if (null !== $this->erroneousResponse) {
            $exception = $this->erroneousResponse;
            do {
                dump(sprintf('Exception %s caught with message: "%s" and StackTrace:', TypeInspector::getClassName($exception), $exception->getMessage()));
                dump(StackTraceFormatter::format($exception->getTrace()));
                $exception = $exception->getPrevious();
            } while (null !== $exception);
            die;
        } else {
            $response = $this->getResponseJson();

            if (isset($response['trace'])) {
                dump($response['detail']);
                die(StackTraceFormatter::format($response['trace']));
            } else if (isset($response['status'])) {
                dump($response);
                dd(sprintf('Execution finished with %d status code', $response['status']));
            } else if (null === $response) {
                dd($response);
            } else if (is_string($response)) {
                dd(sprintf('Response message for code %d: %s', $this->getResponseCode(), $response));
            } else {
                dump($response);
                die('unknown response format passed to throwError');
            }
        }
    }

    protected function conditionalThrowError(): void
    {
        if (
            ResponseCodesSuit::isClientError($this->getResponseCode()) ||
            ResponseCodesSuit::isServerError($this->getResponseCode())
        ) {
            $this->throwError();
        } else {
            var_dump(sprintf('Remaining %s call', __METHOD__));
        }
    }

    public function setCatchException(bool $catchException): void
    {
        $this->catchException = $catchException;
    }
}
