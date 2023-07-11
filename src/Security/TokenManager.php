<?php

namespace App\Security;

use Exception;
use Predis\Client;

class TokenManager
{
    private string $dataPrefix = 'sid_';
    private string $activeSidsPrefix = 'sids_';

    /** @var int Время жизни токена */
    private const TOKEN_LIFETIME = 2592000; // 30 суток

    /** @var int Максимальное кол-во sid'ов привязанных к пользователю */
    public const MAX_BIND_SIDS_TO_USER = 1;

    public function __construct(
        private Client $redis,
    ) {
    }

    public function getUserId(string $token): ?int
    {
        if (null === ($data = $this->getTokenInfo($token))) {
            return null;
        }

        $this->renewTtl($token, $data['uid']);

        return $data['uid'];
    }

    /**
     * @param string $token
     * @return null|array{uid: int, time: int}
     */
    private function getTokenInfo(string $token): ?array
    {
        $serializedTokenInfo = $this->redis->get($this->getKeyTokenToUser($token));
        if (null === $serializedTokenInfo) {
             return null;
        }

        $tokenInfo = unserialize($serializedTokenInfo, ['allowed_classes' => false]);
        if (!is_array($tokenInfo)) {
            return null;
        }

        return $tokenInfo;
    }

    public function deleteToken(string $token, int $userId): void
    {
        $this->redis->del($this->getKeyTokenToUser($token));
        $this->redis->lrem($this->getKeyUserToListToken($userId), 1, serialize($token));
    }

    public function deleteAllUserTokens(int $userId): void
    {
        foreach ($this->getAllUserTokens($userId) as $token) {
            $this->deleteToken($token, $userId);
        }
    }

    /**
     * @param int $userId
     * @return string
     * @throws UnableGenerateTokenException
     */
    public function createToken(int $userId, bool $checkMaxActiveSessions = true): string
    {
        $token = $this->generateToken();

        $this->redis->set(
            $this->getKeyTokenToUser($token),
            serialize(['uid' => $userId, 'time' => time()])
        );

        $this->redis->rpush($this->getKeyUserToListToken($userId), [serialize($token)]);
        $this->renewTtl($token, $userId);

        if ($checkMaxActiveSessions) {
            $maxActiveSessions = $this->getMaxActiveSessionsByUserId($userId);

            $this->removeOverCountTokens($userId, $maxActiveSessions);
        }

        return $token;
    }

    /**
     * Для случая если неизвестно есть ли такой пользователь, но генерить новый токен при каждом запросе это тупо
     */
    public function getExistsOrCreateToken(int $userId)
    {
        // Получим все токены из списка
        $tokens = $this->getAllUserTokens($userId);
        if (empty($tokens)) {
            return $this->createToken($userId);
        }

        $tokensByTtl = $this->sortTokensByTtl($tokens, 'desc');

        // вернем самый свежий токен
        return array_shift($tokensByTtl);
    }

    /**
     * @return string
     * @throws UnableGenerateTokenException
     * @throws Exception
     */
    private function generateToken(): string
    {
        for ($i = 1; $i < 10; $i++) {
            $token = md5(time() . uniqid('sid_', true) . random_int(0, 10000));

            if (null === $this->redis->get($this->getKeyTokenToUser($token))) {
                return $token;
            }
        }

        throw new UnableGenerateTokenException('Токен не уникальный');
    }

    private function renewTtl(string $token, int $userId): void
    {
        $this->redis->expire($this->getKeyTokenToUser($token), self::TOKEN_LIFETIME);
        $this->redis->expire($this->getKeyUserToListToken($userId), self::TOKEN_LIFETIME);
    }

    /** @var $userId int ID пользователя */
    /** @var $maxActiveSessions int Максимальное кол-во sid'ов привязанных к пользователю */
    private function removeOverCountTokens(int $userId, int $maxActiveSessions): void
    {
        // Получим все токены из списка
        $tokens = $this->getAllUserTokens($userId);
        $tokensByTtl = $this->sortTokensByTtl($tokens);

        // удалим лишние токены (сначала старые)
        $sortedTokens = array_values($tokensByTtl);
        while (count($sortedTokens) > $maxActiveSessions) {
            $this->deleteToken((string)array_shift($sortedTokens), $userId);
        }
    }

    private function getAllUserTokens(int $userId): array
    {
        return array_map(
            fn (string $serialized): string => unserialize($serialized),
            $this->redis->lrange($this->getKeyUserToListToken($userId), 0, self::MAX_BIND_SIDS_TO_USER * 2)
        );
    }

    private function getKeyTokenToUser(string $token): string
    {
        return $this->dataPrefix . $token;
    }

    private function getKeyUserToListToken(int $userId): string
    {
        return $this->activeSidsPrefix . $userId;
    }

    /**
     * сортирует токены по времени последнего использования
     * @var string $order - asc|desc - порядок сортировки токенов по ttl
     */
    private function sortTokensByTtl(array $tokens, string $order = 'asc'): array
    {
        $tokensByTtl = [];
        foreach ($tokens as $t) {
            $tokenTtl = $this->redis->ttl($this->getKeyTokenToUser($t));
            $tokensByTtl[$tokenTtl] = $t;
        }

        if ($order === 'desc') {
            krsort($tokensByTtl);
        } else {
            ksort($tokensByTtl);
        }

        return $tokensByTtl;
    }

    private function getMaxActiveSessionsByUserId()
    {
        return 1;
    }

}
