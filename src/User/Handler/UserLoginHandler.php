<?php

declare(strict_types=1);

namespace GC\User\Handler;

use GC\App\Aware\RepositoryAwareTrait;
use GC\Universe\Handler\UniverseSelectHandler;
use Inferno\Inferno\Aware\HandlerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserLoginHandler implements RequestHandlerInterface
{
    use HandlerAwareTrait;
    use RepositoryAwareTrait;

    public const NAME = 'user.login';
    public const SESSION_KEY_USER_ID = 'userId';

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validator = $this->getValidatorWith($request->getParsedBody());
        $validator->context('mail')->isRequired()->isMail();
        $validator->context('password')->isRequired();

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        $isUserVerified = $this->verifyUser(
            (string) $this->getValue('mail', $request),
            (string) $this->getValue('password', $request)
        );

        if (!$isUserVerified) {
            $validator->addMessage('mail');
            $validator->addMessage('password');
        }

        if ($validator->failed()) {
            return $this->failedValidation($validator);
        }

        return $this->redirectJson(UniverseSelectHandler::NAME);
    }

    /**
     * @param string $mail
     * @param string $password
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return bool
     */
    private function verifyUser(string $mail, string $password): bool
    {
        $user = $this->getUserRepository()->findByMail($mail);
        if ($user === null) {
            return false;
        }

        $isVerified = \password_verify($password, $user->getPassword());
        if (! $isVerified) {
            return false;
        }

        $this->getAttributeBag()->set(static::SESSION_KEY_USER_ID, $user->getUserId());

        return true;
    }
}