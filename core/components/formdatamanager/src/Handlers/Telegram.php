<?php

namespace FormDataManager\Handlers;

use FormDataManager\Interfaces\HandlerInterface;
use MODX\Revolution\modX;
use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

class Telegram implements HandlerInterface
{
    private modX $modx;
    /**
     * @var array|mixed|string
     */
    private string $token;

    public function __construct(modX $modX)
    {
        $this->modx = $modX;
        $this->token = $modX->getOption('formdatamanager.token_tg');

    }

    static function getChatId(modX $modX) {
        return explode(",", $modX->getOption('formdatamanager.chat_id'));
    }
    public function run(xPDOSimpleObject $object): bool
    {
        $formData = '';

        foreach ($object->toArray() as $item=>$value) {
            if ($item === "status") continue;
            $formData .= "$item: $value\n";
        }

        return !$this->sendMessage($formData, self::getChatId($this->modx));
    }

    public function sendMessage ($messageData, array $to): int
    {
        $message = urlencode($messageData);

        foreach ($to as $chat) {
            $sh = curl_init("https://api.telegram.org/bot{$this->token}/sendMessage?chat_id=$chat&parse_mode=html&text=" . $message);
            curl_setopt($sh, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($sh);

            if (!$response) {
                $this->modx->log(xPDO::LOG_LEVEL_INFO, "\n\tОшибка подключения к телеграмм боту");
                $status[] = 1;
                continue;
            }

            $response = json_decode($response);

            if (!$response->ok) {
                $this->modx->log(xPDO::LOG_LEVEL_INFO, "\n\tCообщение не доставлено чату " . $chat . ': ' . $response->description);
                $status[] = 2;
                continue;
            }

            $status[] = 0;
        }

        sort($status);

        return $status[0];
    }

    private function success(): string
    {
        return json_encode([
            'success' => true,
        ]);
    }

    private function failure(string $message): string
    {
        return json_encode([
            'success' => false,
            'message' => $message
        ]);
    }
}