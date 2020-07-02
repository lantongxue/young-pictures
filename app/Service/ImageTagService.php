<?php

declare(strict_types=1);

namespace App\Service;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Tiia\V20190529\TiiaClient;
use TencentCloud\Tiia\V20190529\Models\DetectLabelRequest;

class ImageTagService
{
    protected Credential $cred;
    protected HttpProfile $httpProfile;
    protected ClientProfile $clientProfile;
    protected TiiaClient $client;
    protected string $imageContent;

    public function __construct(string $imageContent)
    {
        $this->imageContent = $imageContent;
        $this->init();
    }

    protected function init() : void
    {
        $this->cred = new Credential(config('image_tag.qcloud.secretId'), config('image_tag.qcloud.secretKey'));
        $this->httpProfile = new HttpProfile();
        $this->httpProfile->setEndpoint("tiia.tencentcloudapi.com");
        $this->clientProfile = new ClientProfile();
        $this->clientProfile->setHttpProfile($this->httpProfile);
        $this->client = new TiiaClient($this->cred, config('image_tag.qcloud.region'), $this->clientProfile);
    }

    public function DetectLabel() : array
    {
        try
        {
            $req = new DetectLabelRequest();
            $base64 = base64_encode($this->imageContent);
            $req->setImageBase64($base64);
            $resp = $this->client->DetectLabel($req);
            return $resp->getLabels();
        }
        catch(TencentCloudSDKException $e)
        {
            echo $e;
            return [];
        }
    }
}