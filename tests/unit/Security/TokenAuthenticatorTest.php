<?php

namespace App\Tests\unit\Security;

use App\Http\Response\JsonResponse;
use App\Security\TokenAuthenticator;
use App\Security\TokenManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticatorTest extends TestCase
{
    private TokenAuthenticator $authenticator;
    private TokenManager $manager;

    private AuthenticationException $authenticationException;
    private Request $request;

    private string $validToken;
    private UserProviderInterface $userProvider;
    private array $userCredentials;

    public function setUp(): void
    {
        $this->manager = $this->createMock(TokenManager::class);
        $this->authenticator = new TokenAuthenticator(
            $this->manager,
        );

        $this->authenticationException = new AuthenticationException();
        $this->request = new Request();

        // Случайный валидный по структуре токен
        $this->validToken = '79f8cebdc3a0e6dd4cf68576d81522d9';
        $this->userProvider = $this->createMock(UserProviderInterface::class);
        $this->userCredentials = [
            'token' => 'Bearer ' . $this->validToken
        ];
    }

    public function testStart(): void
    {
        $result = $this->authenticator->start($this->request);
        $resultData = json_decode($result->getContent(), true);
        $this->assertArrayHasKey('details', $resultData);
        $this->assertArrayHasKey('message', $resultData);

        $this->assertSame('Authentication Required', $resultData['message']);
        $this->assertNull($resultData['details']);

        $this->assertSame(JsonResponse::HTTP_UNAUTHORIZED, $result->getStatusCode());
    }

    public function testSupportsRememberMe(): void
    {
        $this->assertFalse($this->authenticator->supportsRememberMe());
    }

    public function testOnAuthenticationFailure(): void
    {
        $result = $this->authenticator->onAuthenticationFailure(
            $this->request,
            $this->authenticationException
        );
        $resultData = json_decode($result->getContent(), true);
        $this->assertArrayHasKey('details', $resultData);
        $this->assertArrayHasKey('message', $resultData);

        $this->assertSame('Access denied', $resultData['message']);
        $this->assertNull($resultData['details']);

        $this->assertSame(JsonResponse::HTTP_FORBIDDEN, $result->getStatusCode());
    }

    public function testOnAuthenticationFailureGraphql(): void
    {
        $request = Request::create('/graphql');
        $result = $this->authenticator->onAuthenticationFailure(
            $request,
            $this->authenticationException
        );

        $this->assertNull($result);
    }

    public function testCheckCredentials(): void
    {
        $this->assertTrue(
            $this->authenticator->checkCredentials(
                null,
                $this->createMock(UserInterface::class)
            )
        );
    }

    public function testGetCredentialsEmpty(): void
    {
        $result = $this->authenticator->getCredentials($this->request);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals("", $result['token']);
    }

    public function testGetCredentials(): void
    {
        $expectedToken = 'token';
        $request = new Request();
        $request->headers = new HeaderBag([
            'Authorization' => 'Bearer ' . $expectedToken
        ]);
        $result = $this->authenticator->getCredentials($request);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($expectedToken, $result['token']);
    }

    public function testSupports(): void
    {
        $request = new Request();
        $request->headers = new HeaderBag([
            'Authorization' => 'Bearer ' . $this->validToken
        ]);
        $this->assertTrue($this->authenticator->supports($request));

        $request->headers = new HeaderBag([
            'Authorization' => $this->validToken
        ]);
        $this->assertFalse($this->authenticator->supports($request));
    }

    public function testOnAuthenticationSuccess(): void
    {
        $this->assertNull(
            $this->authenticator->onAuthenticationSuccess(
                $this->request,
                $this->createMock(TokenInterface::class),
                ''
            )
        );
    }

    public function testGetUserNoCredentials(): void
    {
        $this->assertNull($this->authenticator->getUser(["token" => null], $this->userProvider));
    }

    public function testGetUserIdNotFound(): void
    {
        $this->assertNull(
            $this->authenticator->getUser(
                $this->userCredentials,
                $this->userProvider
            )
        );
    }
}
