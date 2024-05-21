<?php

namespace App\Helpers\SMPush;

use App\Helpers\AppHelper;
use Illuminate\Http\JsonResponse;
use Exception;

class SMPushNotification
{
    protected string $serverKey = '';
    protected string $apiSendAddress = 'https://fcm.googleapis.com/fcm/send';

    /**
     * This type is used to play bell.mp3 sound and send customer calling waiter request.
     */
    public const C_TYPE_WAITER_CALL = "waiter_call";
    /**
     * Required parameters are 'setting' and 'menu' in $data variable.
     * These fields are required to make updates as necessary
     */
    public const C_TYPE_UPDATE = "update";
    /**
     * This is the default one where you just want to send push notification to tablet.
     */
    public const C_TYPE_NORMAL = "normal";

    public const C_TYPE_LEAVE = "leave";

    public const C_TYPE_NOTICE = "notice";

    public const C_TYPE_ATTENDANCE = "attendance";

    public const C_TYPE_NOTIFICATION = "notification";

    public const C_TYPE_TEAM_MEETING = "meeting";

    public const C_TYPE_SUPPORT = "support";

    public const C_TYPE_PROJECT_MANAGEMENT = "project management";






    /**
     * Constructor
     * @throws Exception
     */
    public function __construct()
    {
//        $this->serverKey = config('firebase.server_key') ?? '';

        $this->serverKey = AppHelper::getFirebaseServerKey() ?? '';
        if (!$this->serverKey) {
            throw new Exception('FIREBASE_SERVER_KEY missing.');
        }
    }

    /**
     * $isSilence -> default value is true as we have made all request silence for android app.
     * In android, when the app is in background or in closed state, it seems not possible
     * to show push notification popup with a custom sound play.
     * In silence mode, however, Google has provided much more power to control push notification.
     * So, here default value for `$isSilence` is `true`
     */
    public static function smSend(bool $isAndroid,
                                  string $title,
                                  string $message,
                                  string $type,
                                  array $data,
                                  array $recipients,
                                  bool $isSilence = false): void
    {
        $push = new SMPushNotification();

        $data['type'] = $type;

        $push->send(smPayload: new SMPushPayload(
            isAndroid: $isAndroid,
            title: $title,
            message: $message,
            data: $data,
            recipients: $recipients,
            isSilence: $isSilence
        ));
    }


    protected function send(SMPushPayload $smPayload): JsonResponse
    {
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . $this->serverKey;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->apiSendAddress);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $smPayload->getCurlPayload());

        $data = curl_exec($curl);
        $response = curl_getinfo($curl);

        curl_close($curl);

        return $response['http_code'] === 200
            ? AppHelper::sendSuccessResponse('Push sent.')
            : AppHelper::sendErrorResponse('Push not sent.');
    }
}
