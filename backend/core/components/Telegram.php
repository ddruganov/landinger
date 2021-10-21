<?php

namespace core\components;

use core\components\helpers\DateHelper;
use core\components\helpers\UserHelper;
use Exception;
use Throwable;
use Yii;

class Telegram
{
    public const ERROR = 'error';

    private string $title = '';
    private string $message = '';
    private string $trace = '';
    private string $type = self::ERROR;

    public function send(): bool
    {
        try {
            $token = Yii::$app->params['telegram_bot'][$this->type]['token'];

            $url = "https://api.telegram.org/bot$token/sendMessage?" . http_build_query([
                'chat_id' => Yii::$app->params['telegram_bot'][$this->type]['chatId'],
                'parse_mode' => 'html',
                'text' => $this->formatMessage()
            ]);

            file_get_contents($url);
        } catch (Throwable $t) {
            return false;
        }

        return true;
    }

    private function formatMessage(): string
    {
        $text = '<b>[' . DateHelper::now() . ']</b> ';
        $text .= $this->title;
        $text .= PHP_EOL;
        $text .= PHP_EOL;
        $text .= '<b>Message:</b> ';
        $text .= trim($this->message);
        $text .= PHP_EOL;
        $text .= PHP_EOL;

        if ($this->trace) {
            $text .= '<b>Trace:</b>';
            $text .= PHP_EOL;
            $text .= $this->trace;
            $text .= PHP_EOL;
            $text .= PHP_EOL;
        }

        if ($get_query_data = $_GET) {
            $text .= '<b>GET params: </b>';
            $text .= var_export($get_query_data, true);
            $text .= PHP_EOL;
            $text .= PHP_EOL;
        }

        if ($posted_data = $_POST ?: file_get_contents('php://input')) {
            $text .= '<b>POST params: </b>';
            $text .= var_export($posted_data, true);
            $text .= PHP_EOL;
            $text .= PHP_EOL;
        }

        if ($user = UserHelper::get()) {
            $text .= '<b>User: </b>';
            $text .= '(#' . $user->id . ') ' . $user->email;
            $text .= PHP_EOL;
            $text .= PHP_EOL;
        }

        if ($referrer = $_SERVER['HTTP_REFERER'] ?? null) {
            $text .= '<b>Http referer: </b>';
            $text .= $referrer;
            $text .= PHP_EOL;
        }

        return $text;
    }

    public function setTitle(string $value)
    {
        $this->message = $value;
        return $this;
    }

    public function setMessage(string $value)
    {
        $this->message = $value;
        return $this;
    }

    public function setTrace(string $value)
    {
        $this->trace = $value;
        return $this;
    }

    public function setType(string $value): static
    {
        if (!in_array($value, [self::ERROR])) {
            throw new Exception('Invalid telegram bot type');
        }
        $this->type = $value;
        return $this;
    }
}
