<?php

namespace Revobot\Handlers;

use Revobot\Config;
use Revobot\RequestHandlerInterface;
use Revobot\Response;
use Revobot\Util\PMC;
use Revobot\Util\Strings;

class AdminHandler implements RequestHandlerInterface
{

    public const COOKIE = 'revosession';

    /** @kphp-required */
    public function handle($uri)
    {
        if (substr($uri, 0, 12) === 'admin/login') {
            return $this->login();
        }

        if ($_GET['logout']) {
            return $this->logout();
        }

        return $this->index();
    }

    private function login()
    {
        if ($this->checkTelegramAuthorization($_GET)) {
            $this->makeSession((int)$_GET['id']);
            $this->redirect();
            return;
        } else {
            if ($this->getAuthUser() !== false) {
                return $this->redirect();
            }
            return Response::html('Login Failed');
        }
    }

    private function index()
    {
        $user_id = $this->getAuthUser();
        if ($user_id !== false) {
            return Response::html("Login Successful: user_id={$user_id}<br><a href=\"/admin?logout=1\">Log out</a>");
        } else {
            return Response::html($this->loginSkin(Config::get('public_domain')));
        }
    }

    private function logout()
    {
        PMC::delete('auth_hash' . $_COOKIE[self::COOKIE]);
        setcookie(self::COOKIE, '');
        $this->redirect();
        return;
    }

    private function checkTelegramAuthorization($auth_data): bool
    {
        $bot_token = Config::get('tg_key');
        $check_hash = (string)$auth_data['hash'];
        unset($auth_data['hash']);

        ksort($auth_data);
        $data_check_string = urldecode(http_build_query($auth_data, "", "\n"));

        $secret_key = hash('sha256', $bot_token, true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);

        return hash_equals($hash, $check_hash);
    }

    private function getAuthUser(): mixed
    {
        $user_id = false;
        if (isset($_COOKIE[self::COOKIE])) {
            $user_id = PMC::get('auth_hash' . (string)$_COOKIE[self::COOKIE]);
        }
        return $user_id;
    }

    private function makeSession($user_id)
    {
        $session_key = Strings::random(64);
        PMC::set('auth_hash' . $session_key, (int)$user_id, 0, 60 * 60 * 24);
        setcookie(self::COOKIE, $session_key);
    }

    private function redirect()
    {
        header('Location: /admin');
        return;
    }

    /**
     * @kphp-inline
     */
    private function loginSkin($public_domain): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Telegram Login</title>
</head>
<body>
<script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="Therevoluciabot" data-size="large" data-auth-url="{$public_domain}/admin/login" data-request-access="write"></script>
</body>
</html>

HTML;
    }
}
